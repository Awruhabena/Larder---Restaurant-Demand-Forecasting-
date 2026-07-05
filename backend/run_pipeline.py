"""
run_pipeline.py
---------------
Runs the full pipeline end to end, three stages:

  1. INGEST   messy restaurant CSV  ->  Gemini schema mapper
  2. FORECAST clean orders          ->  weekly ARIMA per item
  3. PLAN     weekly forecast       ->  buy plan (units + safety stock + cedis)

Run:
    python run_pipeline.py                      # uses the messy sample CSV
    python run_pipeline.py my_export.csv        # any restaurant's messy export

Gemini behaviour:
  * If GEMINI_API_KEY is set and reachable, stage 1 makes the live call.
  * If not, it falls back to a deterministic mapping so the pipeline still
    completes end to end. Either way the LLM only proposes the mapping;
    deterministic code applies it and verifies no quantities were altered.

Output:
    Pipeline_Output.xlsx  (Clean Orders | Weekly Schedule | Per-Item Summary)

Requires: pandas, numpy, statsmodels, pmdarima, openpyxl
          google-genai  (only if using the live Gemini call)
"""

import sys
import json
import pandas as pd

from llm_schema_mapper import (
    load_menu, apply_mapping, get_mapping_from_gemini, CANONICAL_COLUMNS,
)
from forecast_core import load_data, build_plan_from_orders, savings_vs_naive

MENU_XLSX = "Restaurant_Data_Patterned.xlsx"
DEFAULT_MESSY = "messy_restaurant_sample.csv"


# Deterministic fallback mapping, used only if the Gemini call is unavailable.
FALLBACK_MAPPING = {
    "column_map": {"Order Dt": "Date", "Dish": "Item",
                   "Qty": "Count", "Ticket#": "Order Number"},
    "item_map": {
        "spring rolls (app)": 104, "springroll": 104, "spring rolls": 104,
        "chicken soup": 101, "chkn soup": 101, "garden salad": 102,
        "grilled fish": 207, "cocktails": 509, "choc cake": 401,
        "tiramisu": 404, "french fries": 302,
    },
    "date_format_hint": None,
    "notes": "Deterministic fallback mapping (no live Gemini call).",
}


def banner(txt):
    print("\n" + "=" * 64 + f"\n{txt}\n" + "=" * 64)


def stage1_ingest(messy_path):
    banner("STAGE 1 — INGEST (Gemini schema mapper)")
    messy = pd.read_csv(messy_path)
    print(f"Loaded messy export: {messy_path}  ({len(messy)} rows)")
    print("Raw columns:", list(messy.columns))

    menu = load_menu(MENU_XLSX)
    try:
        mapping = get_mapping_from_gemini(messy, menu)
        print("Gemini mapping: LIVE call succeeded.")
    except Exception as e:
        mapping = FALLBACK_MAPPING
        print(f"Gemini mapping: fallback used ({type(e).__name__}: {e}).")

    clean, report = apply_mapping(messy, mapping, menu)
    print("\nIntegrity report:")
    print(json.dumps(report, indent=2, default=str))
    return clean


def sample_size_note(clean):
    """The bundled sample CSV is too small to forecast from directly.
    Ingestion is proven on that file; the forecast runs on the full
    patterned dataset, which is what a live deployment accumulates
    over time."""
    print(f"\nClean rows from messy sample: {len(clean)} "
          "(enough to prove ingestion, too few to forecast).")
    print("Forecast stage runs on the full patterned year "
          "(what a deployment accumulates).")


def stage2_and_3(orders_full, items):
    banner("STAGE 2 — FORECAST (weekly ARIMA per item)")
    schedule, summary = build_plan_from_orders(orders_full, items)
    avg_mape = summary["MAPE_%"].dropna().mean()
    print(f"Items forecast: {len(summary)} | avg hold-out MAPE: {avg_mape:.1f}%")

    banner("STAGE 3 — WEEKLY BUY PLAN")
    saved = savings_vs_naive(summary, orders_full, items)
    plan_total = summary["Plan_total_GHS"].sum()
    print(f"4-week buy plan total: GHS {plan_total:,.2f}")
    print(f"Avoidable over-purchase vs naive re-order: GHS {saved:,.2f}\n")

    first_week = schedule["Week_of"].min()
    cols = ["Item Name", "Week_of", "Forecast_units",
            "Recommended_buy_units", "Buy_cost_GHS"]
    print(f"First upcoming week ({first_week}) — top 8 by spend:")
    print(schedule[schedule["Week_of"] == first_week][cols]
          .head(8).to_string(index=False))
    return schedule, summary


def main():
    messy_path = sys.argv[1] if len(sys.argv) > 1 else DEFAULT_MESSY

    clean = stage1_ingest(messy_path)
    orders_full, items = load_data(MENU_XLSX)
    sample_size_note(clean)
    schedule, summary = stage2_and_3(orders_full, items)

    with pd.ExcelWriter("Pipeline_Output.xlsx") as xl:
        clean.to_excel(xl, sheet_name="Clean Orders (from messy)", index=False)
        schedule.to_excel(xl, sheet_name="Weekly Schedule", index=False)
        summary.to_excel(xl, sheet_name="Per-Item Summary", index=False)
    banner("DONE")
    print("Saved -> Pipeline_Output.xlsx "
          "(Clean Orders | Weekly Schedule | Per-Item Summary)")


if __name__ == "__main__":
    main()

"""
live_recompute.py
------------------
Turns an uploaded, cleaned CSV into a genuinely recomputed forecast.

This is the piece that used to be faked: previously, an upload was mapped
onto the canonical schema (real, live) but the dashboard afterward always
showed a pre-computed snapshot (demo_data.json), never a forecast that had
actually run against the uploaded rows.

What this module does instead:
  1. Takes the cleaned rows an upload produced (Date, Time, Order Number,
     Item, Count — same schema as the base dataset).
  2. Appends them to the real order history (a single upload is too small
     to forecast alone, so it genuinely extends the history rather than
     replacing it — exactly what a real deployment does over time).
  3. Re-runs the real forecasting engine (`build_plan_from_orders`,
     unmodified) across the combined history.
  4. Shapes the result into the same JSON structure the API already
     serves for `mode=real`, so the frontend needs no new data model.

Timing (measured on the full 71-item dataset, 8-thread pool): roughly 1-3 minutes.
That's long enough to need a background thread + polling, short enough
that no job queue system (Celery/Redis) is warranted for this scale.
"""

import threading
import time
import uuid

import numpy as np
import pandas as pd

from forecast_core import (
    load_data, build_plan_from_orders, savings_vs_naive,
    weekly_series, forecast_item, DATA_XLSX, PLAN_WEEKS,
)

# In-memory job store. Fine for a single-process demo/hackathon deployment;
# a multi-worker production deployment would back this with Redis instead
# of a queue system entirely, since the compute itself is still a single
# job, not a persistent worker fleet.
_JOBS = {}
_JOBS_LOCK = threading.Lock()


def _set_job(job_id, **fields):
    with _JOBS_LOCK:
        _JOBS.setdefault(job_id, {}).update(fields)


def get_job(job_id):
    with _JOBS_LOCK:
        job = _JOBS.get(job_id)
        return dict(job) if job else None


def merge_uploaded_orders(clean_df: pd.DataFrame) -> pd.DataFrame:
    """Append the uploaded, cleaned rows onto the real base order history.

    A single upload (dozens-to-hundreds of rows) is too sparse to forecast
    from alone — this is stated plainly elsewhere in the codebase. Merging
    it into the full year of history is what makes the recompute genuine:
    the forecast reflects the base history it always did, PLUS whatever
    real signal the new upload adds, rather than a synthetic stand-in.
    """
    base_orders, _ = load_data(DATA_XLSX)
    clean_df = clean_df.copy()
    clean_df["Date"] = pd.to_datetime(clean_df["Date"])
    combined = pd.concat([base_orders, clean_df], ignore_index=True, sort=False)
    return combined


def _build_hero(orders, items, top_item_id):
    """Rebuild the hero-item history+forecast block for one item,
    matching the exact shape the API already serves."""
    cost = items.set_index("Item")["Cost"].to_dict()
    name = items.set_index("Item")["Item Name"].to_dict()
    series = weekly_series(orders, top_item_id)
    res = forecast_item(series)
    if res is None:
        return None

    history = [{"week": str(wk.date()), "units": int(v)}
               for wk, v in series.items()]
    forecast = [{"week": str(d.date()), "units": int(u)}
                for d, u in zip(res["future_dates"], res["future_weeks"])]

    return {
        "name": name.get(top_item_id, str(top_item_id)),
        "arima_order": str(res["order"]),
        "accuracy_pct": float(round(100 - res["mape"], 1)) if pd.notna(res["mape"]) else None,
        "history": history,
        "forecast": forecast,
    }


def _shape_result(schedule: pd.DataFrame, summary: pd.DataFrame,
                   orders: pd.DataFrame, items: pd.DataFrame,
                   saved: float) -> dict:
    """Convert the engine's raw dataframes into the same JSON shape the
    API already serves for mode=real (see demo_data.json)."""
    plan_total = float(summary["Plan_total_GHS"].sum())
    avg_accuracy = float(round(100 - summary["MAPE_%"].dropna().mean(), 1)) \
        if summary["MAPE_%"].notna().any() else None

    headline = {
        "plan_total_ghs": round(plan_total, 2),
        "savings_ghs": round(float(saved), 2),
        "accuracy_pct": avg_accuracy,
        "items_count": int(len(summary)),
        "plan_weeks": PLAN_WEEKS,
        "currency": "GHS",
    }

    weeks = sorted({str(w) for w in schedule["Week_of"]})

    buy_plan = []
    for _, r in schedule.iterrows():
        buy_plan.append({
            "item": r["Item Name"],
            "week_of": str(r["Week_of"]),
            "forecast_units": int(r["Forecast_units"]),
            "buy_units": int(r["Recommended_buy_units"]),
            "unit_cost": float(r["Unit_cost"]),
            "buy_cost_ghs": float(r["Buy_cost_GHS"]),
            "change": r.get("Change_vs_prev", "steady"),
            "change_pct": float(r.get("Change_pct", 0.0)),
        })

    summ = []
    for _, r in summary.iterrows():
        mape = r["MAPE_%"]
        summ.append({
            "item": r["Item Name"],
            "mape_pct": float(mape) if pd.notna(mape) else None,
            "accuracy_pct": float(round(100 - mape, 1)) if pd.notna(mape) else None,
            "arima_order": r["ARIMA_order"],
            "avg_weekly_forecast": float(r["Avg_weekly_forecast"]),
            "plan_total_units": int(r["Plan_total_units"]),
            "plan_total_ghs": float(r["Plan_total_GHS"]),
        })

    # Hero = the item with the largest plan spend, same rule the original
    # demo_data.json used (Chicken Breast, by inspection).
    top_row = summary.iloc[0]
    top_item_id = items[items["Item Name"] == top_row["Item Name"]]["Item"].iloc[0]
    hero = _build_hero(orders, items, int(top_item_id))

    return {
        "headline": headline,
        "weeks": weeks,
        "buy_plan": buy_plan,
        "summary": summ,
        "hero_item": hero,
    }


def start_recompute(clean_df: pd.DataFrame) -> str:
    """Kicks off a real recompute in a background thread and returns a
    job_id immediately. Non-blocking — the caller (the /upload endpoint)
    returns to the frontend right away; the frontend polls job status."""
    job_id = str(uuid.uuid4())
    _set_job(job_id, status="running", started_at=time.time(), error=None,
              result=None)

    def _run():
        try:
            orders = merge_uploaded_orders(clean_df)
            _, items = load_data(DATA_XLSX)
            schedule, summary = build_plan_from_orders(orders, items)
            if schedule.empty:
                raise RuntimeError("Recompute produced no forecastable items.")
            saved = savings_vs_naive(summary, orders, items)
            result = _shape_result(schedule, summary, orders, items, saved)
            _set_job(job_id, status="done", finished_at=time.time(),
                      result=result)
        except Exception as e:
            _set_job(job_id, status="error", finished_at=time.time(),
                      error=str(e))

    threading.Thread(target=_run, daemon=True).start()
    return job_id

"""
forecast_core.py
----------------
Restaurant demand-forecast — FORECASTING CORE + WEEKLY BUY-PLAN.

Design decision (industry-aligned): plan on a WEEKLY basis. Restaurants
reorder perishables weekly, so the deliverable is a forward WEEKLY schedule
per item — a separate forecast and buy recommendation for each of the next
N weeks — not a single monthly lump.

Why weekly is the right resolution here:
  * Matches real perishable reorder cycles.
  * Short horizon (next 1-4 weeks) is where the model is most accurate.
  * Natively 53 weekly points/item — enough to fit, unlike 12 monthly points.

Honest scope note:
  With ONE year of data, annual seasonality (period-52) cannot be learned —
  the cycle never repeats. The weekly model captures LEVEL + recent TREND +
  short autocorrelation. Some items legitimately come back as ARIMA (0,0,0)
  (mean + noise) because at weekly resolution their demand really is flat;
  that is honest, not a bug. Multi-year data would unlock a seasonal term.

Pipeline per item:
  daily orders -> weekly series -> train/test split -> auto_arima fit
  -> hold-out metrics (MAPE/MAE/RMSE) -> forecast next N weeks
  -> per-week buy plan (units + safety stock + cedis)

Requires: pandas, numpy, statsmodels, pmdarima, openpyxl
"""

import warnings
import numpy as np
import pandas as pd
import concurrent.futures

warnings.filterwarnings("ignore")

DATA_XLSX = "Restaurant_Data_Patterned.xlsx"
SAFETY_STOCK = 0.15          # 15% buffer on the buy plan
TEST_WEEKS = 8               # hold-out weeks for honest evaluation
PLAN_WEEKS = 4               # weekly buy plan covers the next N weeks


# --------------------------------------------------------------------------- #
# Load + shape
# --------------------------------------------------------------------------- #
def load_data(path=DATA_XLSX):
    orders = pd.read_excel(path, sheet_name="Orders")
    items = pd.read_excel(path, sheet_name="Items")
    orders["Date"] = pd.to_datetime(orders["Date"])
    # canonical names for the header items (100/300/400/500 have NaN names)
    fallback = {100: "Basic Appetizer", 300: "Basic Side Dish",
                400: "Basic Dessert", 500: "Basic Beverage"}
    items["Item Name"] = items.apply(
        lambda r: fallback.get(int(r["Item"]), r["Item Name"]), axis=1)
    return orders, items


def weekly_series(orders, item_id):
    """Return a weekly demand series (indexed by week-start) for one item."""
    d = orders[orders["Item"] == item_id].copy()
    d["Week"] = d["Date"].dt.to_period("W").dt.start_time
    s = d.groupby("Week")["Count"].sum().sort_index()
    s = s.asfreq("W-MON", fill_value=0)   # regular weekly index, gaps -> 0
    return s


# --------------------------------------------------------------------------- #
# Forecast one item
# --------------------------------------------------------------------------- #
def forecast_item(series, test_weeks=TEST_WEEKS, horizon=PLAN_WEEKS):
    """Fit auto_arima on the weekly series, evaluate on a hold-out, and
    forecast `horizon` future weeks. Returns dict of results with the full
    per-week forecast (not a monthly sum)."""
    import pmdarima as pm

    if len(series) < test_weeks + 8 or series.sum() == 0:
        return None  # too little signal to forecast honestly

    train = series.iloc[:-test_weeks]
    test = series.iloc[-test_weeks:]

    model = pm.auto_arima(
        train, seasonal=False,       # 1 year -> no learnable annual cycle
        start_p=0, max_p=5, start_q=0, max_q=5, d=None,
        stepwise=True, suppress_warnings=True, error_action="ignore",
    )

    # hold-out evaluation
    pred_test = model.predict(n_periods=test_weeks)
    pred_test = np.clip(np.round(pred_test), 0, None)
    actual = test.values.astype(float)
    mask = actual != 0
    mape = (np.abs((actual[mask] - pred_test[mask]) / actual[mask]).mean() * 100
            if mask.any() else np.nan)
    mae = np.abs(actual - pred_test).mean()
    rmse = np.sqrt(((actual - pred_test) ** 2).mean())

    # refit on ALL data, forecast the future weeks
    final = pm.auto_arima(series, seasonal=False, start_p=0, max_p=5,
                          start_q=0, max_q=5, d=None, stepwise=True,
                          suppress_warnings=True, error_action="ignore")
    future = final.predict(n_periods=horizon)
    future = np.clip(np.round(future), 0, None).astype(int)

    # forward week-start dates for labelling the schedule
    last_week = series.index[-1]
    future_dates = pd.date_range(last_week + pd.Timedelta(weeks=1),
                                 periods=horizon, freq="W-MON")

    return {
        "order": final.order,
        "mape": mape, "mae": mae, "rmse": rmse,
        "future_weeks": future,                 # array, one value per week
        "future_dates": future_dates,
    }


# --------------------------------------------------------------------------- #
# Build the weekly buy plan across all items
# --------------------------------------------------------------------------- #
def build_buy_plan(path=DATA_XLSX, safety=SAFETY_STOCK):
    """Weekly buy plan: one row per item PER upcoming week.
    Returns (schedule_df, summary_df, orders, items)."""
    orders, items = load_data(path)
    cost = items.set_index("Item")["Cost"].to_dict()
    name = items.set_index("Item")["Item Name"].to_dict()

    sched_rows = []
    summ_rows = []
    for item_id in sorted(orders["Item"].unique()):
        s = weekly_series(orders, item_id)
        res = forecast_item(s)
        if res is None:
            continue
        unit_cost = float(cost.get(item_id, 0) or 0)
        item_name = name.get(item_id, str(item_id))

        # one row per forecast week
        for wk_date, fc_units in zip(res["future_dates"], res["future_weeks"]):
            buy_qty = int(np.ceil(fc_units * (1 + safety)))
            sched_rows.append({
                "Item": item_id,
                "Item Name": item_name,
                "Week_of": wk_date.date(),
                "Forecast_units": int(fc_units),
                "Safety_stock_%": int(safety * 100),
                "Recommended_buy_units": buy_qty,
                "Unit_cost": unit_cost,
                "Buy_cost_GHS": round(buy_qty * unit_cost, 2),
            })

        # per-item summary across the plan window
        total_fc = int(res["future_weeks"].sum())
        total_buy = int(np.ceil(total_fc * (1 + safety)))
        summ_rows.append({
            "Item": item_id,
            "Item Name": item_name,
            "ARIMA_order": str(res["order"]),
            "MAPE_%": round(res["mape"], 1) if pd.notna(res["mape"]) else None,
            "MAE": round(res["mae"], 2),
            "Avg_weekly_forecast": round(res["future_weeks"].mean(), 1),
            "Plan_total_units": total_buy,
            "Plan_total_GHS": round(total_buy * unit_cost, 2),
        })

    schedule = pd.DataFrame(sched_rows).sort_values(["Week_of", "Buy_cost_GHS"],
                                                    ascending=[True, False])
    summary = pd.DataFrame(summ_rows).sort_values("Plan_total_GHS",
                                                  ascending=False)
    return schedule, summary, orders, items


# --------------------------------------------------------------------------- #
# Avoidable over-purchase vs naive re-ordering
# --------------------------------------------------------------------------- #
def savings_vs_naive(summary, orders, items, plan_weeks=PLAN_WEEKS):
    """Naive baseline = repeat last week's actual for every upcoming week.
    Estimate avoidable over-purchase the weekly forecast prevents."""
    cost = items.set_index("Item")["Cost"].to_dict()
    orders = orders.copy()
    orders["Week"] = orders["Date"].dt.to_period("W").dt.start_time
    last_week = orders["Week"].max()
    naive = orders[orders["Week"] == last_week].groupby("Item")["Count"].sum()

    total = 0.0
    for _, r in summary.iterrows():
        it = r["Item"]
        naive_plan = int(naive.get(it, 0)) * plan_weeks   # repeat last week x N
        fc_plan = r["Plan_total_units"]
        unit_cost = float(cost.get(it, 0) or 0)
        total += max(naive_plan - fc_plan, 0) * unit_cost
    return round(total, 2)

def _process_single_item(item_id, series, unit_cost, item_name, safety, last_actual):
    """Worker function to forecast and build rows for a single item.

    `last_actual` = the item's most recent actual weekly demand, used as the
    baseline for week-1's change. Each subsequent week compares to the prior
    forecast week. Change flagged only past +/-20% so small wobbles read as
    'steady' — the indicator surfaces only items worth acting on.
    """
    import numpy as np
    import pandas as pd
    
    res = forecast_item(series)
    if res is None:
        return [], []

    sched_rows = []
    summ_rows = []

    prev_units = int(last_actual)   # baseline for the first forecast week
    for wk_date, fc_units in zip(res["future_dates"], res["future_weeks"]):
        fc_units = int(fc_units)
        buy_qty = int(np.ceil(fc_units * (1 + safety)))
        # week-over-week change vs the previous week (threshold +/-20%)
        if prev_units <= 0:
            pct = 0.0
        else:
            pct = (fc_units - prev_units) / prev_units * 100
        if pct >= 20:
            change = "up"
        elif pct <= -20:
            change = "down"
        else:
            change = "steady"
        sched_rows.append({
            "Item": item_id, "Item Name": item_name,
            "Week_of": wk_date.date(), "Forecast_units": fc_units,
            "Safety_stock_%": int(safety * 100),
            "Recommended_buy_units": buy_qty, "Unit_cost": unit_cost,
            "Buy_cost_GHS": round(buy_qty * unit_cost, 2),
            "Change_vs_prev": change,
            "Change_pct": round(pct, 1),
        })
        prev_units = fc_units   # next week compares to this week
        
    total_fc = int(res["future_weeks"].sum())
    total_buy = int(np.ceil(total_fc * (1 + safety)))
    summ_rows.append({
        "Item": item_id, "Item Name": item_name,
        "ARIMA_order": str(res["order"]),
        "MAPE_%": round(res["mape"], 1) if pd.notna(res["mape"]) else None,
        "MAE": round(res["mae"], 2) if pd.notna(res["mae"]) else None,
        "Avg_weekly_forecast": round(res["future_weeks"].mean(), 1),
        "Plan_total_units": total_buy,
        "Plan_total_GHS": round(total_buy * unit_cost, 2),
    })
    
    return sched_rows, summ_rows

def build_plan_from_orders(orders, items, safety=SAFETY_STOCK, prices=None):
    """Weekly buy plan using multithreading to optimize ARIMA fits.

    The MODEL predicts QUANTITY only. Price is never predicted — it is applied
    afterwards as a simple multiply (buy_units x unit_price) to cost the plan.
    `prices` is an optional {item_id: unit_price} dict of USER-ENTERED prices;
    where a user hasn't supplied one, we fall back to the dataset Cost column.
    This keeps the cedis figures honest: quantity is forecast, price is the
    restaurant's own current price, not a prediction.
    """
    cost = items.set_index("Item")["Cost"].to_dict()
    name = items.set_index("Item")["Item Name"].to_dict()
    prices = prices or {}
    orders = orders.copy()
    orders["Date"] = pd.to_datetime(orders["Date"])

    unique_items = sorted(orders["Item"].dropna().unique())
    item_tasks = []
    
    for item_id in unique_items:
        item_id = int(item_id)
        series = weekly_series(orders, item_id)
        # user-entered price wins; else fall back to dataset cost
        unit_cost = float(prices.get(item_id, cost.get(item_id, 0)) or 0)
        item_name = name.get(item_id, str(item_id))
        last_actual = int(series.iloc[-1]) if len(series) else 0
        item_tasks.append((item_id, series, unit_cost, item_name, safety, last_actual))

    sched_rows = []
    summ_rows = []

    # A thread pool is used because each ARIMA fit releases the GIL during
    # its C-level computation, so I/O-bound waiting overlaps across items.
    with concurrent.futures.ThreadPoolExecutor(max_workers=8) as executor:
        future_to_item = {
            executor.submit(_process_single_item, *task): task[3] 
            for task in item_tasks
        }
        
        for future in concurrent.futures.as_completed(future_to_item):
            item_name = future_to_item[future]
            try:
                s_rows, sum_rows = future.result()
                sched_rows.extend(s_rows)
                summ_rows.extend(sum_rows)
            except Exception as e:
                # A single item's forecast failure is logged and skipped
                # rather than aborting the whole run.
                print(f"Warning: Forecast failed for {item_name}: {e}")

    # Safety check in case ALL items failed or dataset is empty
    if not sched_rows:
        return pd.DataFrame(), pd.DataFrame()

    schedule = pd.DataFrame(sched_rows).sort_values(
        ["Week_of", "Buy_cost_GHS"], ascending=[True, False])
    summary = pd.DataFrame(summ_rows).sort_values(
        "Plan_total_GHS", ascending=False)
        
    return schedule, summary

if __name__ == "__main__":
    schedule, summary, orders, items = build_buy_plan()
    avg_mape = summary["MAPE_%"].dropna().mean()
    plan_total = summary["Plan_total_GHS"].sum()
    saved = savings_vs_naive(summary, orders, items)

    print(f"Items forecast: {len(summary)}")
    print(f"Planning horizon: {PLAN_WEEKS} weeks")
    print(f"Average hold-out MAPE: {avg_mape:.1f}%")
    print(f"Total {PLAN_WEEKS}-week buy plan: GHS {plan_total:,.2f}")
    print(f"Est. avoidable over-purchase vs naive weekly re-order: GHS {saved:,.2f}\n")

    print("WEEKLY SCHEDULE — first upcoming week, top 8 items by spend:")
    first_week = schedule["Week_of"].min()
    cols = ["Item Name", "Week_of", "Forecast_units",
            "Recommended_buy_units", "Buy_cost_GHS"]
    print(schedule[schedule["Week_of"] == first_week][cols].head(8).to_string(index=False))

    print("\nPER-ITEM SUMMARY (top 8 by plan spend):")
    scols = ["Item Name", "ARIMA_order", "MAPE_%",
             "Avg_weekly_forecast", "Plan_total_units", "Plan_total_GHS"]
    print(summary[scols].head(8).to_string(index=False))

    with pd.ExcelWriter("Weekly_Buy_Plan.xlsx") as xl:
        schedule.to_excel(xl, sheet_name="Weekly Schedule", index=False)
        summary.to_excel(xl, sheet_name="Per-Item Summary", index=False)
    print("\nSaved -> Weekly_Buy_Plan.xlsx (Weekly Schedule + Per-Item Summary)")

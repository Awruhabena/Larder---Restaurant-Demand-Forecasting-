# Larder — Backend API Reference

The contract between the frontend and the backend. Every endpoint, its exact response shape, and which screen consumes it.

---

## How the frontend talks to the backend

The backend is a Python (FastAPI) server. The frontend talks to it over plain HTTP and receives JSON.

- **Base URL (local):** `http://localhost:8000`
- **Data format:** all responses are JSON.

### Running the backend

From the `backend/` folder:

```bash
python runner.py
```

This installs dependencies, verifies the dataset, runs the pipeline, and starts the API on port 8000. The interactive API explorer is at `http://localhost:8000/docs`.

Dependencies: pandas, numpy, statsmodels, pmdarima, openpyxl, google-genai, fastapi, uvicorn, python-multipart.

---

## Endpoints

### `GET /headline`

The top-line numbers for the Dashboard.

```json
{
  "plan_total_ghs": 572960.41,
  "savings_ghs": 62262.63,
  "accuracy_pct": 75.5,
  "items_count": 71,
  "plan_weeks": 4,
  "currency": "GHS"
}
```

- `plan_total_ghs` — total buy-plan cost across the plan window, costed at the user's own prices.
- `savings_ghs` — estimated avoidable over-purchase versus naive re-ordering.
- `accuracy_pct` — average forecast accuracy across items.
- `items_count` — number of items forecast.
- `plan_weeks` — how many weeks the plan covers.

Accepts an optional `?mode=preview|real|live` parameter (default `real`) — `preview` returns a smaller, generic sample dataset; `real` returns the verified pre-computed snapshot; `live` returns the result of a genuine recompute job (requires `&job_id=...`, see `POST /upload` and `GET /recompute-status/{job_id}` below) and falls back to `real` if that job isn't done yet.

---

### `GET /weeks`

The list of weeks the plan covers, for the week selector.

```json
{ "weeks": ["2023-01-02", "2023-01-09", "2023-01-16", "2023-01-23"] }
```

Also accepts `?mode=preview|real|live` (with `&job_id=...` for `live`).

---

### `GET /buy-plan`

The per-item buy plan. Optional `week` query filters to one week.

```json
{
  "item": "Chicken Breast",
  "week_of": "2023-01-02",
  "forecast_units": 43,
  "buy_units": 50,
  "unit_cost": 174.24,
  "buy_cost_ghs": 8712.0,
  "change": "down",
  "change_pct": -25.9
}
```

Per row:
- `item` — menu item name.
- `forecast_units` — predicted demand.
- `buy_units` — recommended purchase (forecast plus a 15% safety-stock buffer).
- `unit_cost` — the user's price per unit.
- `buy_cost_ghs` — `buy_units × unit_cost`, costed at the user's price, never a predicted price.
- `change` — direction versus the previous week: `up`, `down`, or `steady`. Only moves past ±20% are flagged; smaller ones read as steady.
- `change_pct` — the percentage change.

Query parameters:
- `week` — filter to one week (e.g. `?week=2023-01-02`).
- `changed_only` — `true` returns only items that moved (not steady).
- `mode` — `preview`, `real`, or `live` (default `real`).
- `job_id` — required when `mode=live`; the recompute job to read from.

---

### `GET /summary`

Per-item summary with model detail.

```json
{
  "item": "Chicken Breast",
  "mape_pct": 31.0,
  "accuracy_pct": 69.0,
  "arima_order": "(5, 1, 0)",
  "avg_weekly_forecast": 42.8,
  "plan_total_units": 197,
  "plan_total_ghs": 34325.28
}
```

- `accuracy_pct` — per-item accuracy, the figure surfaced to users.
- `arima_order`, `mape_pct` — model internals, kept out of the main UI but available for reference.
- `plan_total_units` / `plan_total_ghs` — totals across the plan window for that item.

Accepts `?mode=preview|real|live` (with `&job_id=...` for `live`).

---

### `GET /hero`

The full actual-vs-forecast series for the chart on the Forecast Detail screen.

```json
{
  "name": "Chicken Breast",
  "arima_order": "(5, 1, 0)",
  "accuracy_pct": 69.0,
  "history": [{ "week": "2021-12-27", "units": 10 }],
  "forecast": [{ "week": "2023-01-02", "units": 43 }]
}
```

- `history` — around 53 weeks of actual past demand, rendered as a solid line.
- `forecast` — the next 4 weeks predicted, rendered as a dashed line.
- The boundary between the two arrays is where the "Now" divider sits on the chart.
- No confidence band is returned — the solid/dashed distinction is treated as sufficient to communicate known versus predicted; adding upper/lower prediction bounds is a natural extension once needed.

This endpoint currently returns full chart data for one representative item (Chicken Breast). Extending it to every menu item is a deferred backend step — see the limitations section of the main README.

Accepts `?mode=preview|real|live` (with `&job_id=...` for `live`).

---

### `POST /upload`

Upload a CSV; receive the clean-mapping result, an integrity report, and a `job_id` for a genuine recompute that starts immediately in the background.

Request: `multipart/form-data` with a `file` field.

```json
{
  "gemini_live": true,
  "raw_columns": ["Order Dt", "Dish", "Qty", "Ticket#"],
  "clean_preview": [
    { "date": "2022-01-02 00:00:00", "item": "Spring Rolls", "count": 2 }
  ],
  "integrity": {
    "original_rows": 12,
    "rows_kept": 11,
    "rows_dropped_unmatched_item": 1,
    "counts_preserved_for_matched_rows": true,
    "notes": "..."
  },
  "job_id": "7ddd0704-926a-486c-a517-e801892fea8b",
  "recompute_error": null
}
```

- `gemini_live` — `true` if the live Gemini call ran, `false` if the deterministic fallback was used. Either way the response is valid.
- `raw_columns` — the uploaded file's original columns.
- `clean_preview` — up to 20 mapped, clean rows.
- `integrity` — rows matched, rows skipped, and confirmation that quantities were not altered.
- `job_id` — the mapped rows are merged into the real order history and the real forecasting engine (`build_plan_from_orders`) starts recomputing across every item, in a background thread. This call returns immediately (≈0.1s) — the recompute itself takes roughly 1-3 minutes (measured, 71 items, 8-thread pool). Poll `GET /recompute-status/{job_id}` for completion, then request any of the endpoints above with `?mode=live&job_id=...`.
- `recompute_error` — set if the recompute job couldn't even be started (e.g. malformed clean data); `null` otherwise. A `job_id` can still fail *during* the run — check `/recompute-status` for that case.

The mapper matches item text against the existing menu (`Restaurant_Data_Patterned.xlsx`'s Items sheet). Text it cannot confidently match is skipped rather than guessed at, which is why `rows_dropped_unmatched_item` can be non-zero on an otherwise valid file.

---

### `GET /recompute-status/{job_id}`

Poll this while waiting for a recompute to finish.

```json
{ "status": "running", "elapsed_seconds": 47.1, "error": null }
```

- `status` — `running`, `done`, or `error` (or `not_found` with HTTP 404 for an unknown/expired job_id).
- `elapsed_seconds` — real wall-clock time since the job started (while running) or its total run time (once finished) — not a synthetic progress estimate.
- `error` — the exception message if the job failed; `null` otherwise.

Once `status` is `done`, every data endpoint above will serve the fresh result under `?mode=live&job_id=...`. There is no separate "fetch result" endpoint — the same `/headline`, `/weeks`, `/buy-plan`, `/summary`, `/hero` endpoints serve it, keeping one data shape regardless of mode.

---

### `GET /`

Health check. Returns `{ "ok": true, "endpoints": [...] }`.

---

## Endpoint → screen map

| Screen | Endpoint(s) |
|---|---|
| Dashboard | `GET /headline` |
| This Week's Buy | `GET /weeks`, `GET /buy-plan` |
| Forecast Detail | `GET /hero` |
| Data Upload | `POST /upload` |
| Processing (post-upload transition) | `GET /recompute-status/{job_id}` — real polling, not a timed animation |
| Settings, Pricing, Billing, Alerts, Register | No backend connection — demonstrated in the frontend directly |

---

## What has no backend

These screens are not wired to the API — they demonstrate the user journey using PHP sessions rather than a real backend service:

- **Register / Login** — no authentication backend.
- **Pricing, Billing** — no payment processor; the payment flow is real (a genuine form and confirmation step) but not connected to a real payment gateway.
- **Settings price entry** — the backend's forecasting functions already accept a user-supplied price override; there is no endpoint yet to persist it, so entered prices are held in the session for now.
- **Alerts** — a static notification feed.

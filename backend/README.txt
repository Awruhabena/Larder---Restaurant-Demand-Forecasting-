LARDER — BACKEND
=================

The forecasting engine and API for Larder.

WHAT'S HERE
-----------
  Larder_API_Reference.md         Every endpoint, its response shape, and
                                   which screen consumes it. Start here.

  runner.py                       Installs dependencies, checks the dataset,
                                   runs the pipeline, starts the API on :8000.
  api.py                          The FastAPI server the frontend calls.
  forecast_core.py                Forecasting engine and weekly buy plan.
  live_recompute.py               Merges an upload into the real order
                                   history and re-runs the real engine in a
                                   background thread (mode=live). Roughly
                                   1-3 minutes, varies by machine.
  llm_schema_mapper.py             Gemini schema mapper, with a deterministic
                                   safety check.
  run_pipeline.py                 End-to-end pipeline (messy CSV to buy plan),
                                   runnable directly from the terminal.

  demo_data.json                  Pre-computed forecast data (mode=real).
  preview_data.json                Generic sample data (mode=preview).
  Restaurant_Data_Patterned.xlsx  The 71-item menu and a year of order data.
  messy_restaurant_sample.csv     A small messy export, for testing ingestion.
  messy_full_year.csv             A full year of messy data that cleans to
                                   the same figures as demo_data.json.

QUICK START
-----------
  1. (optional) set a Gemini key for the live upload path:
       macOS/Linux:  export GEMINI_API_KEY="your-key"
       Windows CMD:  set GEMINI_API_KEY=your-key
     Without a key, the upload endpoint falls back to a deterministic
     mapping — the response is still valid.
  2. python runner.py
  3. Open http://localhost:8000/docs to explore the API directly.
  4. The frontend (../frontend) points at http://localhost:8000.

NOTES
-----
  * /upload always makes a live Gemini call (or deterministic fallback),
    AND now kicks off a genuine recompute job in the background: the
    uploaded rows are merged into the real order history and the real
    engine (forecast_core.build_plan_from_orders) re-runs across every
    item. That takes roughly 1-3 minutes — poll GET /recompute-status/{job_id},
    then request mode=live&job_id=... on any endpoint below.
  * mode=preview and mode=real remain pre-computed snapshots requiring no
    recomputation, exactly as before — mode=live is the new, genuinely
    computed third option.
  * The change indicator (change / change_pct on every buy-plan row, plus
    ?changed_only=true) is supported.
  * /hero currently returns full chart data for one representative item
    (Chicken Breast). Extending it to every menu item is a deferred step —
    see the main README's limitations section.
  * Every GET endpoint accepts an optional ?mode=preview|real|live
    parameter (default real); live requires &job_id=... and falls back
    to real if that job isn't done yet.

TESTING WITH A DIFFERENT DATASET
---------------------------------
Run the pipeline directly against any CSV:

    python run_pipeline.py your_file.csv

Item names in the file are matched against the existing menu
(Restaurant_Data_Patterned.xlsx's Items sheet). Text that cannot be
confidently matched is skipped rather than guessed at — see the main
README's limitations section for what this means for testing with a
genuinely different restaurant's menu.

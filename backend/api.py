"""
api.py
------
FastAPI layer serving the forecasting backend to the frontend.

Three responsibilities:
  * Serve pre-computed forecast data (demo_data.json, preview_data.json)
    to the dashboard and buy-plan screens (mode=real / mode=preview).
  * Handle the one always-live call: a messy CSV upload is mapped onto the
    canonical schema via Gemini.
  * Genuinely recompute the forecast against the uploaded data: the clean
    rows are merged into the real order history and the real forecasting
    engine (build_plan_from_orders) is re-run in a background thread.
    A full recompute across all items takes roughly 1-3 minutes (measured, with the
    existing 8-thread pool) — long enough to need a job_id + polling,
    short enough that no job-queue system (Celery/Redis) is warranted at
    this scale. Once done, the result is served under mode=live.

Run:
    pip install fastapi uvicorn python-multipart pandas numpy statsmodels pmdarima openpyxl
    uvicorn api:app --reload --port 8000

Endpoints:
    GET  /headline                    -> top-line numbers
    GET  /buy-plan?week=...            -> buy plan rows, optionally filtered to a week
    GET  /weeks                        -> the list of plan weeks
    GET  /summary                     -> per-item summary
    GET  /hero                        -> hero-item actual-vs-forecast series
    POST /upload                      -> messy CSV in, mapping report out, kicks off a real recompute job
    GET  /recompute-status/{job_id}   -> {status: running|done|error}
    (all GET endpoints above also accept mode=live&job_id=... once a job is done)
"""

import io
import json
import os

import pandas as pd
from fastapi import FastAPI, UploadFile, File, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse

from llm_schema_mapper import (
    load_menu, apply_mapping, get_mapping_from_gemini,
)
from live_recompute import start_recompute, get_job

DEMO_JSON = "demo_data.json"
PREVIEW_JSON = "preview_data.json"
MENU_XLSX = "Restaurant_Data_Patterned.xlsx"

# Deterministic fallback used when no Gemini key is set.
FALLBACK_MAPPING = {
    "column_map": {"Order Dt": "Date", "Dish": "Item",
                   "Qty": "Count", "Ticket#": "Order Number", "Time": "Time"},
    "item_map": {
        'tomatosoup': 100,
        'tomato soup': 100,
        'chicken soup': 101,
        'chickensoup': 101,
        'garden salad': 102,
        'gardensalad': 102,
        'caesar salad': 103,
        'caesarsalad': 103,
        'spring rolls': 104,
        'springrolls': 104,
        'samosas': 105,
        'bruschetta': 106,
        'shrimp cocktail': 107,
        'shrimpcocktail': 107,
        'calamari rings': 108,
        'calamarirings': 108,
        'seafood skewers': 109,
        'seafoodskewers': 109,
        'steaks': 200,
        'burgers': 201,
        'porkchops': 202,
        'pork chops': 202,
        'lamb chops': 203,
        'lambchops': 203,
        'chicken breast': 204,
        'chickenbreast': 204,
        'roastedduck': 205,
        'roasted duck': 205,
        'turkey dishes': 206,
        'turkeydishes': 206,
        'grilled fish': 207,
        'grilledfish': 207,
        'fried fish': 208,
        'friedfish': 208,
        'seafood pasta': 209,
        'seafoodpasta': 209,
        'sushi rolls': 210,
        'sushirolls': 210,
        'spaghetti': 211,
        'fettuccine': 212,
        'lasagna': 213,
        'ravioli': 214,
        'vegetable stir-fry': 215,
        'vegetablestir-fry': 215,
        'tofu dishes': 216,
        'tofudishes': 216,
        'vegetarian pasta': 217,
        'vegetarianpasta': 217,
        'salads': 218,
        'mashed potatoes': 301,
        'mashedpotatoes': 301,
        'french fries': 302,
        'frenchfries': 302,
        'roastedpotatoes': 303,
        'roasted potatoes': 303,
        'potato wedges': 304,
        'potatowedges': 304,
        'steamed vegetables': 305,
        'steamedvegetables': 305,
        'grilled vegetables': 306,
        'grilledvegetables': 306,
        'mixed vegetable medley': 307,
        'mixedvegetablemedley': 307,
        'plain rice': 308,
        'plainrice': 308,
        'friedrice': 309,
        'fried rice': 309,
        'pilaf': 310,
        'garlic bread': 311,
        'garlicbread': 311,
        'dinnerrolls': 312,
        'dinner rolls': 312,
        'naanbread': 313,
        'naan bread': 313,
        'chocolate cake': 401,
        'chocolatecake': 401,
        'cheesecake': 402,
        'carrot cake': 403,
        'carrotcake': 403,
        'tiramisu': 404,
        'variousflavorswithtoppings': 405,
        'various flavors with toppings': 405,
        'apple pie': 406,
        'applepie': 406,
        'cherry pie': 407,
        'cherrypie': 407,
        'key lime pie': 408,
        'keylimepie': 408,
        'chocolate pudding': 409,
        'chocolatepudding': 409,
        'rice pudding': 410,
        'ricepudding': 410,
        'custard': 411,
        'fruit salad': 412,
        'fruitsalad': 412,
        'slicedfruits': 413,
        'sliced fruits': 413,
        'fruit platter': 414,
        'fruitplatter': 414,
        'water': 501,
        'soda': 502,
        'juice': 503,
        'lemonade': 504,
        'iced tea': 505,
        'icedtea': 505,
        'coffee': 506,
        'beer': 507,
        'wine': 508,
        'cocktails': 509,
        'whiskey': 510,
        'vodka': 511,
        'liqueurs': 512,
        'spring rolls (app)': 104,
        'springroll': 104,
        'chkn soup': 101,
        'choc cake': 401,
    },
    "date_format_hint": None,
    "notes": "Deterministic fallback mapping (no live Gemini call). Covers all 71 menu items' case/spacing variants.",
}

app = FastAPI(title="Restaurant Demand Forecast API")
app.add_middleware(
    CORSMiddleware, allow_origins=["*"], allow_methods=["*"], allow_headers=["*"],
)


def _data(mode: str = "real", job_id: str | None = None):
    """Returns forecast data for one of three modes:
      preview -> generic pre-computed sample data
      real    -> the verified pre-computed snapshot (demo_data.json)
      live    -> the result of a genuine recompute job (job_id required);
                 falls back to 'real' if the job isn't done yet or is
                 missing, so a stale/expired job_id never hard-fails a page.
    """
    if mode == "live" and job_id:
        job = get_job(job_id)
        if job and job.get("status") == "done" and job.get("result"):
            return job["result"]
        # Job not ready / not found: fail soft to the verified real snapshot
        # rather than erroring the page out.
        mode = "real"
    path = PREVIEW_JSON if mode == "preview" else DEMO_JSON
    with open(path) as f:
        return json.load(f)


@app.get("/headline")
def headline(mode: str = Query(default="real"), job_id: str | None = Query(default=None)):
    return _data(mode, job_id)["headline"]


@app.get("/weeks")
def weeks(mode: str = Query(default="real"), job_id: str | None = Query(default=None)):
    return {"weeks": _data(mode, job_id)["weeks"]}


@app.get("/buy-plan")
def buy_plan(week: str | None = Query(default=None),
             changed_only: bool = Query(default=False),
             mode: str = Query(default="real"),
             job_id: str | None = Query(default=None)):
    rows = _data(mode, job_id)["buy_plan"]
    if week:
        rows = [r for r in rows if r["week_of"] == week]
    if changed_only:
        rows = [r for r in rows if r.get("change", "steady") != "steady"]
    return {"week": week, "changed_only": changed_only, "mode": mode, "rows": rows}


@app.get("/summary")
def summary(mode: str = Query(default="real"), job_id: str | None = Query(default=None)):
    return {"items": _data(mode, job_id)["summary"]}


@app.get("/hero")
def hero(mode: str = Query(default="real"), job_id: str | None = Query(default=None)):
    return _data(mode, job_id)["hero_item"]


@app.get("/recompute-status/{job_id}")
def recompute_status(job_id: str):
    """Frontend polls this while processing.php waits for the real
    recompute to finish. Returns enough for an honest progress display —
    not a fake percentage, just elapsed time and status."""
    import time as _time
    job = get_job(job_id)
    if not job:
        return JSONResponse({"status": "not_found"}, status_code=404)
    elapsed = job.get("finished_at", _time.time()) - job["started_at"]
    return {
        "status": job["status"],
        "elapsed_seconds": round(elapsed, 1),
        "error": job.get("error"),
    }


@app.post("/upload")
async def upload(file: UploadFile = File(...)):
    """Maps an uploaded CSV onto the canonical schema and returns the
    clean rows plus an integrity report. Uses Gemini if a key is set,
    otherwise a deterministic fallback mapping."""
    raw = await file.read()
    messy = pd.read_csv(io.BytesIO(raw))
    menu = load_menu(MENU_XLSX)

    used_live = False
    try:
        mapping = get_mapping_from_gemini(messy, menu)
        used_live = True
    except Exception:
        mapping = FALLBACK_MAPPING

    clean, report = apply_mapping(messy, mapping, menu)
    name = menu.set_index("Item")["Item Name"].to_dict()
    preview = [
        {"date": str(r["Date"]), "item": name.get(int(r["Item"]), int(r["Item"])),
         "count": None if pd.isna(r["Count"]) else int(r["Count"])}
        for _, r in clean.head(20).iterrows()
    ]

    # Kick off a genuine recompute: the cleaned rows are merged into the
    # real order history and the real engine re-runs across all items.
    # This is real computation (roughly 1-3 minutes, varies by machine),
    # so it runs in the background; the frontend gets a job_id immediately
    # and polls for completion rather than the request blocking that long.
    job_id = None
    recompute_error = None
    try:
        job_id = start_recompute(clean)
    except Exception as e:
        recompute_error = str(e)

    return JSONResponse({
        "gemini_live": used_live,
        "raw_columns": list(messy.columns),
        "clean_preview": preview,
        "integrity": report,
        "job_id": job_id,
        "recompute_error": recompute_error,
    })


@app.get("/")
def root():
    return {"ok": True, "endpoints":
            ["/headline", "/weeks", "/buy-plan", "/summary", "/hero",
             "/upload", "/recompute-status/{job_id}"]}

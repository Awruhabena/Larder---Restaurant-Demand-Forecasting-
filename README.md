# Larder — Restaurant Demand Forecasting

Predicts how much of each menu item a restaurant should buy each week, and turns that forecast into a costed purchasing plan — so restaurants stop guessing, stop over-ordering, and stop running out.

---

## Problem Statement

**What is the problem?** Restaurants make their biggest recurring cost decision — how much food to buy each week — by guessing. Every restaurant already generates the sales data that would answer this precisely, but small and independent restaurants rarely have the tools to use it.

**Who is affected?** Independent restaurant owners and managers, most acutely in markets like Ghana where margins are thin and food waste is unaffordable. The effect reaches further: suppliers who could plan better with visibility into real demand, and communities where food waste and food insecurity exist side by side.

**Why does it matter?** Buying too much means perishables spoil — direct, avoidable financial loss. Buying too little means running out on the restaurant's busiest days — lost sales and disappointed customers. Both failure modes are common, and both are preventable with the same missing piece: a reliable forecast of next week's demand.

**What happens if this isn't solved?** The waste and the lost sales keep recurring, week after week, at every restaurant that lacks forecasting — a small, constant leak that adds up to a meaningful share of a thin-margin business's losses over a year.

---

## Proposed Solution

**What it does.** Larder ingests a restaurant's own sales history, forecasts demand for every menu item for the coming weeks, and turns that forecast into a costed weekly buy plan — how many units of each item to buy, with a safety-stock buffer, priced at the restaurant's own current prices.

**How users interact with it.** A restaurant owner registers, sees the product working on sample data immediately (no payment required to explore), and once ready, connects their own sales export. From then on, they open Larder weekly to see exactly what to buy and what it will cost.

**What makes it useful.** The output is not a model score or a chart to interpret — it is a plain, actionable purchasing list in the restaurant's own currency, built from the restaurant's own history.

**What makes it different.** Most forecasting tools stop at a number. Larder is deliberately scoped around one honest boundary: it predicts *quantity*, because that is what a restaurant's own sales history genuinely supports — and it never predicts *price*, because price moves on supplier and market forces no model should claim to know. Costs are calculated at the restaurant's own entered prices. This is a stated design choice, not a limitation discovered later.

---

## How AI Is Used

**What AI model or tool.** Google's Gemini is used for one specific task: mapping a restaurant's raw sales export — however inconsistent its column names, date formats, or item spellings — onto the clean schema the forecasting engine needs.

**What task the AI performs.** Given a messy file (for example, a dish appearing as "Spring Rolls", "SPRING ROLLS", and "SpringRolls" across different rows), Gemini proposes how the source columns and item names map onto the system's canonical structure.

**Why AI is needed here.** Resolving messy, inconsistent real-world text is a language-understanding problem, which is exactly what a language model is suited for — and exactly what would otherwise require a person to manually clean every new restaurant's export by hand.

**How it improves the outcome, and the boundary that keeps it trustworthy.** The AI's role is deliberately limited to proposing the mapping. It never touches a quantity. Deterministic code applies the mapping and then verifies that no quantity was altered and no row was silently dropped — every upload returns an integrity report showing exactly what matched and what was skipped. This means a wrong mapping is caught and visible; a silently corrupted number is not possible. The forecasting itself (the actual prediction of demand) is intentionally *not* done by the AI — it runs on a statistical time-series model (ARIMA) evaluated on real held-out data, so its accuracy figure is measured, not assumed.

---

## Installation & Setup

This README continues below with exact setup instructions. The system has two parts running side by side: a Python backend (the forecasting engine and API) and a PHP frontend (the web app) — not a single install command, since the two are genuinely separate services.

## What you need installed

- **Python 3.9+** (with `pip`)
- **A PHP server** — [XAMPP](https://www.apachefriends.org/) is the simplest option and what these instructions assume
- No database is required. No paid API key is required (see the optional Gemini step below).

---

## Step 1 — Run the backend (Python)

Open a terminal in the `backend/` folder.

```bash
cd backend
python3 -m venv venv
source venv/bin/activate          # Windows: venv\Scripts\activate

pip install -r ../requirements.txt

python runner.py
```

`runner.py` will:
1. Verify the real dataset is present (`Restaurant_Data_Patterned.xlsx`, 71 menu items — if it warns about a smaller file, something's wrong with the copy).
2. Run the forecasting pipeline once.
3. Start the API at **`http://localhost:8000`**.

Leave this terminal running for the rest of the session. You can sanity-check it directly in a browser at `http://localhost:8000/docs` — an interactive page listing every endpoint.

**Optional — enables the live Gemini call for data ingestion.** Without this, ingestion still works via a deterministic fallback; only the "live Gemini" indicator on the upload page will read false instead of true. (This key is unrelated to the forecast recompute triggered by an upload — that's a real statistical model, not an LLM call, and runs regardless of whether this key is set.)
```bash
export GEMINI_API_KEY="your-key"   # Windows CMD: set GEMINI_API_KEY=your-key
```
Set this *before* running `python runner.py`, in the same terminal.

---

## Step 2 — Run the frontend (PHP)

1. Copy the entire `frontend/` folder into your PHP server's web root.
   - XAMPP default location: `C:\xampp\htdocs\larder\` (Windows) or `/Applications/XAMPP/htdocs/larder/` (macOS).
2. Open the **XAMPP Control Panel** and click **Start** next to **Apache** only. (MySQL is not needed — Larder has no database — leave it off.)
3. With the backend from Step 1 still running, open your browser to:
   ```
   http://localhost/larder/register.php
   ```

**Both servers run at the same time**, side by side: Apache serves the web pages, the Python API (Step 1) answers their data requests. If a page loads but shows "Could not reach the Larder API," the Step 1 terminal has stopped — restart it.

---

## Walking through the app

1. **Register** with any email — no verification, it just starts the session.
2. You land on the **Dashboard** with sample data — this proves the product works before any payment is required.
3. Browse **This Week's Buy** and **Forecasts** on the sample data.
4. Try to open **Upload Data** — it's locked, with a link to **Pricing**.
5. Pick a plan → **Billing** → enter any mobile-money-style number → **Pay & subscribe** → see the confirmation screen → continue.
6. **Upload Data** is now unlocked. Upload `backend/messy_full_year.csv` — this is a deliberately messy, realistic export; watch it get cleaned live and see the integrity report.
7. A **real processing screen** — it polls the actual recompute job and takes roughly 1-3 minutes (fitting a statistical model per menu item across the merged order history), not a staged animation. Then the **Dashboard shows the recomputed forecast** — 71 items, a genuine weekly buy plan, actually computed from that data (`mode=live`). If you navigate away before it finishes, or the job fails, the dashboard falls back to the verified pre-computed snapshot (`mode=real`) rather than hanging.

If you want to restart the journey from the top without clearing your browser's cookies, visit `http://localhost/larder/reset-journey.php`.

---

## Demo Video
▶️ **[Watch the demo](https://www.loom.com/share/a9b4d57301684bb384ba7a375457fbac)**

---

## Troubleshooting

| Symptom | Likely cause |
|---|---|
| A page loads with "Could not reach the Larder API" | The Step 1 terminal isn't running. Restart `python runner.py`. |
| `runner.py` warns about item count (e.g. "8 items" instead of 71) | The dataset file got swapped for a smaller stand-in — check `backend/Restaurant_Data_Patterned.xlsx` is the ~1.5MB real file. |
| Dashboard shows real data immediately after registering, or Upload is unlocked without paying | A leftover browser session from earlier testing. Visit `reset-journey.php`, or open the site in an incognito window. |
| Dashboard shows `mode=real` (the snapshot) instead of your own recomputed numbers after uploading | Expected if the recompute job hasn't finished, failed, or the API was restarted mid-poll — this is the designed fallback, not a bug. Check `http://localhost:8000/recompute-status/{job_id}` (the job_id from the `/upload` response) directly, or re-upload to start a fresh job. |
| MySQL fails to start in XAMPP | Ignore it — Larder doesn't use a database. Only start Apache. |
| `pip` not recognized | Use `python3 -m pip install ...` instead, or ensure Python was installed with "Add to PATH" checked. |

---

## Repository structure

```
larder/
├── backend/          Python — the forecasting engine + API (FastAPI)
├── frontend/         PHP/HTML/CSS — the web app
├── docs/             Project narrative, journey map, demo script
├── demo/             Pitch deck; pointer to the live-demo walkthrough below
└── requirements.txt  Python dependencies (also listed in Step 1 below)
```

## Further documentation

| File | What it covers |
|------|-----------------|
| `backend/Larder_API_Reference.md` | Every API endpoint, its exact response shape, which screen calls it |
| `frontend/PHP_Conversion_Guide.md` | How the frontend is wired to the API, the session-based payment gate |
| `docs/User_Journey_Map.md` | The full navigation flow, screen to screen |
| `docs/Project_Master_Document.md` | The problem, solution, design decisions, business model |

---

## How it works, briefly

1. **Ingest** — a messy sales export (any column names, date formats, item spelling) is mapped onto a clean schema by Google Gemini; deterministic code then verifies no quantity was altered.
2. **Forecast** — a weekly ARIMA model per menu item, evaluated on data it never saw, so its accuracy figure is honest.
3. **Plan** — the forecast becomes a costed weekly buy plan, priced at the restaurant's own entered prices — Larder predicts *quantity*, never price.

## Known limitations (stated deliberately)

- No real authentication, database, or payment processor — the full user journey (including a real payment-entry form and confirmation screen) is demonstrated using PHP sessions; real persistence is the natural next step.
- The Forecast Detail chart currently has full historical data for one representative item; extending it to every menu item is a small, deliberately deferred backend step.
- **Uploading your own data genuinely triggers a recompute, not a synchronous one.** A full recompute — fitting a statistical model per menu item across the merged order history — takes real time (roughly 1-3 minutes, varies by machine, even with an 8-thread pool). Rather than block a web request for that long, the upload kicks off a real background job and returns immediately with a job ID; the frontend polls it for genuine completion and only then shows the recomputed forecast (`mode=live`). This mirrors the correct production pattern — compute-heavy work runs asynchronously — rather than faking a result. Until that job finishes (or if it fails), the dashboard shows the verified pre-computed snapshot (`mode=real`) instead of hanging or erroring.
- **The "savings vs. naive re-order" figure can read low immediately after a small upload.** That figure compares the forecast to a naive baseline built from the most recent week of order history. If an upload's most recent week only contains a handful of items (rather than a full day/week of orders across the whole menu), the naive baseline for the untouched items is near zero, which understates the estimated savings until a fuller week of real data accumulates. This is an honest property of the metric on sparse data, not a computation bug.
- **Single-restaurant menu, by design of this build.** The forecasting engine itself is restaurant-agnostic — it fits a model to whatever quantity-over-time series it's given, regardless of what the items are. The constraint is upstream: the schema mapper matches uploaded item names against one fixed menu list (`Restaurant_Data_Patterned.xlsx`'s Items sheet), so it can resolve any messy formatting of *those* items, but has nothing to map an entirely different restaurant's dishes onto — it correctly declines to guess rather than invent a match. Supporting a new restaurant today means adding its items to that sheet first. The natural next step is to let the mapper register genuinely new items automatically (with a placeholder price the restaurant corrects in Settings), noting that a brand-new item still needs several weeks of real orders before there's enough history to forecast from — a true limit of time-series forecasting, not an engineering gap.

## Tech stack

**Backend:** Python, FastAPI, statsmodels/pmdarima (ARIMA), pandas, Google Gemini (`google-genai`)
**Frontend:** PHP, HTML, CSS — server-rendered, vanilla JS for client-side interactivity
**Data:** A real 71-item restaurant menu with a full year of demand history, patterned with realistic weekend/payday/seasonal effects

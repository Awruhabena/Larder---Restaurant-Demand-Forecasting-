# Larder — Frontend Architecture Guide

The frontend is built in PHP, HTML, and CSS, wired to the Python API. This guide explains how the pieces fit together.

The Python API (`api.py`, `forecast_core.py`, `llm_schema_mapper.py`) is unaffected by the frontend implementation — it runs on `http://localhost:8000` regardless of what calls it.

Interactive screens (Buy Plan, Forecast Detail, Upload, Processing, Settings) are built with plain PHP and vanilla JavaScript rather than any UI framework, so the logic — the ±20% change threshold, the chart coordinate math, the week/filter switching — is directly readable and maintainable without framework-specific knowledge.

---

## THE JOURNEY GATE — how "generic data first, real data after payment" is enforced

This is the most important thing to understand before touching any file. The rule is enforced with three PHP session variables, set and checked across specific files — **not decorative, genuinely load-bearing:**

| Session variable | Set by | Checked by | Effect |
|---|---|---|---|
| `$_SESSION['subscribed']` | `billing.php` (on "complete payment") | `upload.php`, `upload-locked.php` | Unlocks the real upload form |
| `$_SESSION['just_uploaded']` | `upload.php` (on successful `/upload` call) | `processing.php` | Confirms an upload actually happened |
| `$_SESSION['recompute_job_id']` | `upload.php` (from `/upload`'s response) | `processing.php`, `mark-live-ready.php` | The real background recompute job to poll |
| `$_SESSION['data_ready']` | `processing.php` (only if BOTH of the first two above are set) | `dashboard.php`, `buy-plan.php`, `forecast-detail.php` | The floor: allows `mode=real` (the verified pre-computed snapshot) |
| `$_SESSION['live_job_ready']` | `mark-live-ready.php` (only once `/recompute-status` has genuinely confirmed the job is `done`) | `dashboard.php`, `buy-plan.php`, `forecast-detail.php` | The ceiling: upgrades those pages from `mode=real` to `mode=live` — the actual recomputed forecast for this user's own upload |

**The critical rule in `dashboard.php`, `buy-plan.php`, and `forecast-detail.php`:** none of them trust a `?mode=` typed in the URL to grant `real` or `live`. They check session state first, in this order: `live_job_ready` → `mode=live`; else `data_ready` → `mode=real`; else `mode=preview`. Only an explicit `?mode=preview` (e.g. a "view sample" link) is ever honoured directly from the URL. This means a user cannot skip payment, upload, or the real recompute by guessing a URL.

**The full chain:** `register.php` → `dashboard.php` (preview, `data_ready` unset) → `buy-plan.php` (preview) → `forecast-detail.php` → `upload-locked.php` → `pricing.php` → `billing.php` (sets `subscribed`) → `upload.php` (now unlocked; on real upload, sets `just_uploaded` + `recompute_job_id`) → `processing.php` (sets `data_ready`; polls `/recompute-status` for real completion, then calls `mark-live-ready.php` which sets `live_job_ready`) → `dashboard.php` (now shows `real` immediately, upgrading to `live` — this user's own recomputed forecast — once the poll confirms it's done).

---

## Files in this folder

| File | Connects to API? | Notes |
|---|---|---|
| `register.php` | No | Starts the PHP session; lands on Dashboard (preview) |
| `dashboard.php` | Yes — `GET /headline` | Journey-gated mode: `preview` → `real` → `live` (see above) |
| `buy-plan.php` | Yes — `GET /weeks`, `GET /buy-plan` | Journey-gated mode; week tabs + changed-only filter run client-side on data PHP already fetched |
| `forecast-detail.php` | Yes — `GET /hero` | Chart coordinates computed server-side from the history/forecast arrays; only Chicken Breast has full data (see API reference) |
| `upload-locked.php` | No | Pre-payment state; links to `pricing.php` |
| `upload.php` | Yes — `POST /upload` | Live Gemini mapping AND kicks off a genuine background recompute job (`job_id` stored in session) |
| `pricing.php` | No | Links each "Choose X" to `billing.php?plan=X` |
| `billing.php` | No | Simulated checkout; sets `$_SESSION['subscribed']` |
| `processing.php` | Yes — `GET /recompute-status/{job_id}` | Real polling, not a timed animation — waits for the actual recompute to finish (roughly 1-3 minutes) before proceeding; sets `$_SESSION['data_ready']` up front, and (via `mark-live-ready.php`) `live_job_ready` once genuinely confirmed done |
| `mark-live-ready.php` | No (session-only) | Tiny endpoint `processing.php` calls client-side once polling confirms `status: done`; sets `$_SESSION['live_job_ready']` |
| `settings.php` | No | Price-entry area; saved to session for now (see below) |
| `alerts.php` | No | Static notification feed |
| `design-system.php` | No | Static colour/type reference (also see `larder-tokens.css`) |

---

## Running it (XAMPP)

1. Copy this whole folder into `htdocs/larder/` (or wherever XAMPP serves from).
2. Start Apache in XAMPP as usual.
3. **Separately**, start the Python API — XAMPP does not run Python:
   ```
   cd ../backend
   pip install pandas numpy statsmodels pmdarima openpyxl google-genai fastapi uvicorn python-multipart
   python runner.py
   ```
   This must stay running in its own terminal at `http://localhost:8000` while you use the app.
4. Visit `http://localhost/larder/register.php` to walk the full journey.

**Two servers, one machine.** If pages hang or show "Could not reach the Larder API," the Python server isn't running — start it first.

---

## Known gaps (see the API reference for detail)

- **Forecast Detail** only has full chart data for Chicken Breast. `forecast-detail.php` shows a clear notice if a different item was requested, rather than silently mislabeling the chart.
- **Settings** prices save to the PHP session, not to the backend yet. The backend's `build_plan_from_orders(prices=...)` already accepts a price override — wiring settings.php's saved prices into that call is the natural next step once real persistence (a database) is added.
- **No real auth, billing, or database** — by design for this build. `register.php` and `billing.php` are functional enough to drive the session-based journey gate, not real account/payment systems.

---

## Fonts

Newsreader, Hanken Grotesk, IBM Plex Mono — loaded via Google Fonts CDN `<link>` tags already included at the top of every page. `larder-tokens.css` is linked in every page for consistent colours/spacing/radii.


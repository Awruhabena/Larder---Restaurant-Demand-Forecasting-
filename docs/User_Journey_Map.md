# Larder — User Journey Map

The complete navigation flow, annotated with journey stages. This is the map the Dashboard/Buy Plan `mode=preview|real` and Upload locked/unlocked states are built around.

---

## The flow

```
Register
   ↓
Dashboard (mode=preview)
   ↓
Buy Plan (preview)
   ↓  tap an item
Forecast Detail
   ↓  back
Upload — LOCKED state
   ↓  tap "Subscribe"
Pricing (Basic / Plus / Pro)
   ↓  choose tier
Billing (Paystack / mobile money)
   ↓  complete payment
Upload — UNLOCKED state
   ↓  choose file
Processing screen (real recompute, polled — not staged)
   ↓
Dashboard (mode=real, upgrading to mode=live once the recompute confirms done)
   ↓
Buy Plan (real/live, changed-only filter)
   ↓
Alerts / Settings — supporting links, reachable throughout
```

---

## Journey stages (the "why" behind the flow)

**🔍 DISCOVER** — Register. First contact, minimal friction, no payment required to start.

**✨ FIRST VALUE** — Dashboard (preview) → Buy Plan (preview) → Forecast Detail. The user sees the product genuinely work — real screens, a real (sample) forecast — *before* being asked to pay. This is the value-first principle: never ask for commitment before the user has perceived value.

**💳 CONVERT** — Upload (locked) → Pricing → Billing → Upload (unlocked). Only once the user has seen the value does the paywall appear. It's visible-but-blocked, not hidden — the user understands exactly what they're unlocking, then chooses a tier and pays.

**🔄 THE REVEAL** — Upload (unlocked) → choose file → Processing → Dashboard (mode=real, upgrading to mode=live). Gemini ingestion is genuinely live, and so is the forecast: the uploaded rows are merged into the real order history and the real engine recomputes across every item (roughly 1-3 minutes, measured). The Processing screen polls the real job for completion rather than staging a fixed animation; if the job hasn't finished (or fails) by the time the user reaches the dashboard, it shows the verified pre-computed snapshot (`mode=real`) instead of hanging, and upgrades to the fresh result (`mode=live`) once confirmed. See `backend/live_recompute.py` and the "Known limitations" section of the main README for the exact mechanics and honest edge cases.

**🔁 WEEKLY HABIT** — Dashboard (real/live) → Buy Plan (real/live, changed-only). This is the loop the subscription business depends on: the user returns weekly, checks what changed, acts on it.

**⚙️ ONGOING** — Alerts (the retention nudge) and Settings (price entry, keeping the cedis figures honest) are reachable throughout, supporting the weekly habit rather than sitting in the main line.

---

## Key structural notes for implementation

- **Dashboard and Buy Plan are ONE screen each**, not duplicated — driven by the `mode=preview` / `mode=real` / `mode=live` query parameter on the API. See `Larder_API_Reference.md`.
- **Upload is ONE screen with two states** (locked / unlocked), gated by subscription status — not two separate screens.
- **Processing is a single-purpose screen** — one entry (from Upload unlocked, file chosen), one exit (to Dashboard). It polls the real recompute job (`GET /recompute-status/{job_id}`) rather than staging a fixed-length animation, and communicates the actual state: still running, done, or (if the job errors) falling back to the verified pre-computed snapshot rather than hanging — see `backend/live_recompute.py` and the compute-time note in the project document.
- **Forecast Detail** is a template opened from any Buy Plan item — currently only Chicken Breast has full chart data behind it (see API reference "known gap").

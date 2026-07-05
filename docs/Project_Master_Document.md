# Restaurant Demand Forecasting — Master Project Document

*A complete reference for what was built, why, and how it works.*

---

## 1. One-line summary

An AI tool that predicts how much of each menu item a restaurant should buy each week, and turns that forecast into a costed purchasing plan — so restaurants stop guessing, stop over-ordering, and stop running out.

---

## 2. The problem

Restaurants live or die on purchasing decisions, and most make them blind.

- Food is a restaurant's largest controllable cost. Buy too much and it spoils — money in the bin. Buy too little and you run out — lost sales and disappointed customers.
- The decision is made **weekly**, by hand, from memory or gut feel. "How much chicken do I need next week?" is answered by a stressed owner guessing.
- The information to answer it well *exists* — it's sitting in the restaurant's own sales history — but no small restaurant has the time or tooling to turn months of receipts into a reliable forecast.
- This is especially acute in markets like Ghana, where margins are thin and waste is unaffordable, yet forecasting tools are built for large chains with data teams, not independent restaurants.

The gap: every restaurant is sitting on the data that would tell them what to buy, and none of them can use it.

---

## 3. The solution

A demand-forecasting and purchasing tool that does three things:

1. **Ingests** the restaurant's own sales data — however messy their point-of-sale export is.
2. **Forecasts** weekly demand for each menu item, learning the patterns in their history.
3. **Delivers a buy plan** — for each upcoming week, exactly how many units of each item to buy, with a safety-stock buffer, costed in cedis.

The user opens it weekly, sees what to buy and what it costs, and acts. The product answers one question cleanly: *"What do I buy this week, and what will it cost me?"*

---

## 4. How it works — the three stages

### Stage 1 — Ingest (the onboarding edge)
Every restaurant's data looks different: inconsistent column names, dishes spelled three different ways, mixed date formats. We use Google Gemini to **map** any messy export onto our clean schema — recognising that "spring rolls (app)", "Springroll", and "SPRING ROLLS" are the same item.

The critical design principle: **the AI only proposes the mapping; deterministic code applies it and verifies nothing was altered.** The language model never touches a quantity. A safety check confirms no numbers changed and no rows were silently dropped. This makes onboarding effortless *without* risking the data the forecast depends on.

### Stage 2 — Forecast (the intelligence)
Each item's sales history is aggregated to a weekly series and fitted with an automatically-selected ARIMA time-series model. Every item is evaluated on a held-out test window — weeks the model never saw — so the accuracy figure is honest, not inflated.

The model predicts **quantity** — how many units will sell. It deliberately does **not** predict price (see design decisions).

### Stage 3 — Plan (the value)
The forecast becomes a purchasing plan: for each of the next four weeks, the recommended buy quantity per item (forecast plus a 15% safety-stock buffer), costed at the restaurant's own prices. Plus a week-over-week change indicator so the owner sees at a glance which items moved.

---

## 5. Key design decisions (the ones worth defending)

**Weekly, not monthly.** Restaurants reorder perishables weekly, so the plan matches their real cycle. Weekly resolution also gives the model ~53 data points per item to learn from, versus only 12 if we aggregated monthly — enough to fit an honest model.

**Predict quantity, not price.** Price changes are driven by suppliers, inflation, and season — forces outside the sales data that no honest model can forecast. So we predict *quantity* (which the data genuinely supports) and cost it at the restaurant's own current prices, which they enter. The cedis figures are real, but they're quantity × the user's price, never a predicted price. This is a deliberate honesty boundary — and a stronger position than claiming to predict everything.

**The AI maps, code does the maths.** The language model is used only where language reasoning is the task — matching messy item names to the menu. It never computes or alters a number. A deterministic check guarantees it. This keeps the AI's power without its risk.

**The system degrades gracefully.** The live Gemini call has a deterministic fallback. If the network or an API quota fails, the system completes anyway on the fallback mapping — identical downstream. Robustness over fragility.

---

## 6. The technology

- **Forecasting:** ARIMA time-series models (auto-selected per item), evaluated on held-out data.
- **Data ingestion:** Google Gemini for schema mapping, with a deterministic safety layer.
- **Backend:** Python — a forecasting core, a thin API serving the app, and an end-to-end pipeline.
- **Data flow:** messy CSV → clean schema → weekly forecast → costed buy plan.
- **Frontend:** a desktop application — dashboard, buy plan, forecast detail, upload, and the subscription screens.

The architecture cleanly separates the AI (which reasons about messy language) from the deterministic maths (which owns every number), and separates the prediction (quantity) from the user's own input (price).

---

## 7. The product — what the user sees

**Dashboard** — the weekly landing. The value at a glance: total buy plan cost, estimated savings, forecast accuracy, items tracked. Leads to the buy plan.

**Buy Plan** — the hero screen. For the selected week, every item's recommended buy quantity and cost, sorted by spend, with a change-vs-last-week indicator and a "changed items only" filter so a long list collapses to the few items that need a decision.

**Forecast Detail** — per item, a chart of demand history flowing into the forecast, with history and prediction visually distinct so the user can see what's known versus predicted, plus a plain-language read and the item's accuracy.

**Data Upload** — where the restaurant connects its own data; the messy-to-clean transformation made visible and reassuring.

**Pricing, Billing, Settings, Alerts** — the subscription product around the tool.

---

## 8. The business model

**Subscription SaaS**, tiered by capability:

- **Basic** — the weekly buy plan and forecasts. The core purchasing tool.
- **Plus** — adds weekly demand alerts and a longer forecast horizon. (The anchor tier.)
- **Pro** — adds multi-location support, team members, and automated data ingestion at scale.

Any size of restaurant can choose any tier — the tiers gate *capability*, not restaurant size. The value proposition is direct: the tool saves the restaurant more than it costs, by cutting waste and preventing stockouts.

**Entry flow is value-first:** a new user registers, lands on a working dashboard populated with sample data so they *see* the value, and only then pays to connect their own restaurant's data. We never ask for payment before the user has perceived what they're paying for.

---

## 9. Trust and honesty — why this AI is credible

This is an AI product, and its credibility rests on being honest about what it can and cannot do.

- **The accuracy number is real** — measured on data the model never saw, not on the data it trained on.
- **The AI never touches the numbers** — it maps language; code owns every quantity, guaranteed by a verification step.
- **We predict only what the data supports** — quantity, not price. We say so plainly rather than overclaiming.
- **The user keeps agency** — they see the forecast against their own history and can judge it, enter their own prices, and act on their own decision.

For a restaurant owner deciding where real money goes, this honesty is the product's foundation, not a footnote.

---

## 10. Honest limitations (stated, not hidden)

- **The demonstration data has modelled demand patterns.** It is built on a real restaurant menu dataset (real items, prices, costs) and layered realistic demand behaviour — weekend, payday, and festive-season effects — so the model has genuine patterns to discover. It discovers them from the data alone, exactly as it would from a real restaurant's history; real point-of-sale data changes nothing in the pipeline.
- **Some items forecast as steady.** At weekly resolution, several items have genuinely flat demand, so the model correctly predicts a stable level rather than inventing a pattern. Honest behaviour, not a failure.
- **One year of data means no annual seasonality yet.** The model captures level, recent trend, and short-term patterns. Multi-year data would unlock yearly seasonal effects. The short forecast horizon is exactly where the model is most accurate.
- **The subscription backend is illustrated in the product, not fully built.** The forecasting engine is real and runs live; auth, billing, and persistence are demonstrated through the user journey, with real implementations of each as a natural next step.

---

## 11. Impact and vision

**Immediate:** a small restaurant stops throwing away money on spoilage and stops losing sales to stockouts, using data it already generates. The tool pays for itself.

**The data compounds.** Every week of use makes the forecast better and builds a picture of demand no individual restaurant could see alone. Over time this becomes a demand-intelligence layer for the food sector — valuable to suppliers, and a moat that grows with use.

**The vision:** bring the forecasting power that large chains take for granted to the independent restaurants that make up most of the market — starting in Ghana, where the need is sharpest and the impact most direct.

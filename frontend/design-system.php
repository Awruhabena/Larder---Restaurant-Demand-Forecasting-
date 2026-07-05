<?php
/**
 * design-system.php
 * ------------------
 * Static reference page for the design system: colours, type, spacing,
 * and components. Not part of the app flow. See also larder-tokens.css
 * for the same values as CSS variables.
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Design System</title>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="larder-tokens.css">
</head>
<body style="margin:0;">


<div style="font-family:'Hanken Grotesk',sans-serif; color:#1B1A16; background:#EDE9E1; padding:56px 40px 96px; display:flex; justify-content:center;">
<div style="width:100%; max-width:1360px; display:flex; flex-direction:column; gap:64px;">

  <!-- MASTHEAD -->
  <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:40px; border-bottom:1px solid #D3CCBE; padding-bottom:32px;">
    <div style="display:flex; flex-direction:column; gap:16px;">
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:34px; height:34px; border-radius:8px; background:#1E4A38; display:flex; align-items:center; justify-content:center;">
          <div style="width:13px; height:13px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div>
        </div>
        <span style="font-size:26px; font-weight:600; letter-spacing:-0.02em;">Larder</span>
      </div>
      <div style="font-family:'Newsreader',serif; font-size:40px; line-height:1.05; letter-spacing:-0.02em; font-weight:400; max-width:640px;">The design system for a tool that answers one question — <span style="font-style:italic; color:#1E4A38;">what do I buy this week, and what will it cost?</span></div>
    </div>
    <div style="text-align:right; flex-shrink:0;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Design System</div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C; margin-top:4px;">v1 · for approval</div>
    </div>
  </div>

  <!-- PERSONALITY -->
  <div style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C; padding-top:4px;">Personality</div>
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
      <div style="font-size:15px; color:#57544C; max-width:720px; line-height:1.55;">Confident, precise, quietly premium — financial-operations software a business relies on to stop wasting money. The deliberate risk: <span style="color:#1B1A16; font-weight:600;">headline money is set in a serif</span>, giving every GHS figure the gravity of a printed ledger. Everything around it stays disciplined and calm.</div>
    </div>
  </div>

  <!-- COLOR: BRAND & SURFACE -->
  <section style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">01 — Color</div>
      <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px;">Brand &amp; surface</div>
    </div>
    <div style="display:flex; flex-direction:column; gap:28px;">
      <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">
        <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:96px; border-radius:10px; background:#1E4A38;"></div><div><div style="font-weight:600; font-size:13px;">Evergreen</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#1E4A38 · brand</div></div></div>
        <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:96px; border-radius:10px; background:#2E6B4F;"></div><div><div style="font-weight:600; font-size:13px;">Sage</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#2E6B4F · secondary</div></div></div>
        <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:96px; border-radius:10px; background:#1B1A16;"></div><div><div style="font-weight:600; font-size:13px;">Ink</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#1B1A16 · text</div></div></div>
        <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:96px; border-radius:10px; background:#E9C46A;"></div><div><div style="font-weight:600; font-size:13px;">Brass</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#E9C46A · highlight</div></div></div>
      </div>
      <div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:12px;">Surface layers — data-dense, so we stack four</div>
        <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:14px;">
          <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:72px; border-radius:10px; background:#EDE9E1; border:1px solid #E3DED4;"></div><div><div style="font-weight:600; font-size:13px;">App</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#EDE9E1</div></div></div>
          <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:72px; border-radius:10px; background:#F4F1EA; border:1px solid #E3DED4;"></div><div><div style="font-weight:600; font-size:13px;">Canvas</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#F4F1EA</div></div></div>
          <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:72px; border-radius:10px; background:#FFFFFF; border:1px solid #E3DED4;"></div><div><div style="font-weight:600; font-size:13px;">Card</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#FFFFFF</div></div></div>
          <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:72px; border-radius:10px; background:#F4F1EA; border:1px solid #E3DED4;"></div><div><div style="font-weight:600; font-size:13px;">Sunken</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#EEEAE1</div></div></div>
          <div style="display:flex; flex-direction:column; gap:10px;"><div style="height:72px; border-radius:10px; background:#E3DED4;"></div><div><div style="font-weight:600; font-size:13px;">Border</div><div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C;">#E3DED4</div></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- COLOR: DATA PALETTE -->
  <section style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">01 — Color</div>
      <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px;">The data palette</div>
      <div style="font-size:13px; color:#8B877C; margin-top:8px; line-height:1.5;">The product lives on figures and charts. These four carry meaning everywhere — never decorative.</div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:20px; display:flex; flex-direction:column; gap:16px;">
        <div style="width:36px; height:36px; border-radius:8px; background:#1B1A16;"></div>
        <div><div style="font-weight:700; font-size:15px;">Spend</div><div style="font-size:12px; color:#8B877C; margin-top:2px;">money out — set in ink, authoritative</div></div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C; margin-top:auto;">#1B1A16</div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:20px; display:flex; flex-direction:column; gap:16px;">
        <div style="width:36px; height:36px; border-radius:8px; background:#2E8B5D;"></div>
        <div><div style="font-weight:700; font-size:15px; color:#2E8B5D;">Savings</div><div style="font-size:12px; color:#8B877C; margin-top:2px;">money saved, upward moves</div></div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C; margin-top:auto;">#2E8B5D</div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:20px; display:flex; flex-direction:column; gap:16px;">
        <div style="width:36px; height:36px; border-radius:8px; background:#C0791F;"></div>
        <div><div style="font-weight:700; font-size:15px; color:#C0791F;">Forecast</div><div style="font-size:12px; color:#8B877C; margin-top:2px;">predicted data — the future</div></div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C; margin-top:auto;">#C0791F</div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:20px; display:flex; flex-direction:column; gap:16px;">
        <div style="width:36px; height:36px; border-radius:8px; background:#9A9488;"></div>
        <div><div style="font-weight:700; font-size:15px; color:#57544C;">Actual</div><div style="font-size:12px; color:#8B877C; margin-top:2px;">past / historical — muted</div></div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#8B877C; margin-top:auto;">#9A9488</div>
      </div>
    </div>
  </section>

  <!-- TYPOGRAPHY -->
  <section style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">02 — Type</div>
      <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px;">Numbers are primary</div>
      <div style="font-size:13px; color:#8B877C; margin-top:8px; line-height:1.5;">Three faces, each with one job.</div>
    </div>
    <div style="display:flex; flex-direction:column; gap:20px;">
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:28px 32px; display:flex; justify-content:space-between; align-items:center; gap:24px;">
        <div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Display — Newsreader</div>
          <div style="display:flex; align-items:baseline; gap:8px; margin-top:8px;">
            <span style="font-family:'Hanken Grotesk'; font-size:20px; font-weight:600; color:#8B877C;">GHS</span>
            <span style="font-family:'Newsreader',serif; font-size:68px; font-weight:500; letter-spacing:-0.02em; line-height:1;">572,960</span>
          </div>
        </div>
        <div style="font-size:13px; color:#8B877C; max-width:180px; text-align:right;">Hero money figures. Serif gives them weight.</div>
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Body — Hanken Grotesk</div>
          <div style="font-size:22px; font-weight:600; margin-top:12px; letter-spacing:-0.01em;">This week's plan is ready.</div>
          <div style="font-size:15px; color:#57544C; margin-top:6px; line-height:1.55;">Highly legible UI and prose. 400 for text, 600 for labels, 700 for emphasis.</div>
        </div>
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Tabular — IBM Plex Mono</div>
          <div style="margin-top:14px; display:flex; flex-direction:column; gap:6px; font-family:'IBM Plex Mono',monospace; font-size:15px; font-variant-numeric:tabular-nums;">
            <div style="display:flex; justify-content:space-between;"><span style="color:#57544C;">Chicken Breast</span><span style="font-weight:600;">8,712.00</span></div>
            <div style="display:flex; justify-content:space-between;"><span style="color:#57544C;">Pork Chops</span><span style="font-weight:600;">4,190.50</span></div>
            <div style="display:flex; justify-content:space-between;"><span style="color:#57544C;">Salads</span><span style="font-weight:600;">1,088.00</span></div>
          </div>
          <div style="font-size:12px; color:#8B877C; margin-top:12px;">Figures right-align and scan cleanly in tables.</div>
        </div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px 28px; display:flex; flex-wrap:wrap; gap:28px; align-items:baseline;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; width:100%;">Scale</div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-family:'Newsreader'; font-size:68px; line-height:1;">68</span><span style="font-size:12px; color:#8B877C;">hero</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-family:'Newsreader'; font-size:40px; line-height:1;">40</span><span style="font-size:12px; color:#8B877C;">metric</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-size:28px; font-weight:600;">28</span><span style="font-size:12px; color:#8B877C;">title</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-size:20px; font-weight:600;">20</span><span style="font-size:12px; color:#8B877C;">heading</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-size:15px;">15</span><span style="font-size:12px; color:#8B877C;">body</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-size:13px;">13</span><span style="font-size:12px; color:#8B877C;">small</span></div>
        <div style="display:flex; align-items:baseline; gap:8px;"><span style="font-family:'IBM Plex Mono'; font-size:11px; letter-spacing:0.1em;">11</span><span style="font-size:12px; color:#8B877C;">label caps</span></div>
      </div>
    </div>
  </section>

  <!-- FOUNDATIONS -->
  <section style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">03 — Foundations</div>
      <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px;">Space, radius, line</div>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:16px;">Spacing — 4px base</div>
        <div style="display:flex; align-items:flex-end; gap:8px;">
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:4px; height:4px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">4</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:8px; height:8px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">8</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:16px; height:16px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">16</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:24px; height:24px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">24</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:32px; height:32px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">32</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px;"><div style="width:48px; height:48px; background:#1E4A38;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">48</span></div>
        </div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:16px;">Radius</div>
        <div style="display:flex; gap:14px; align-items:flex-end;">
          <div style="display:flex; flex-direction:column; align-items:center; gap:8px;"><div style="width:48px; height:48px; background:#EEEAE1; border:1px solid #D3CCBE; border-radius:6px;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">6 · control</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:8px;"><div style="width:48px; height:48px; background:#EEEAE1; border:1px solid #D3CCBE; border-radius:10px;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">10 · card</span></div>
          <div style="display:flex; flex-direction:column; align-items:center; gap:8px;"><div style="width:48px; height:48px; background:#EEEAE1; border:1px solid #D3CCBE; border-radius:999px;"></div><span style="font-family:'IBM Plex Mono'; font-size:10px; color:#8B877C;">pill</span></div>
        </div>
      </div>
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:16px;">Line &amp; elevation</div>
        <div style="display:flex; flex-direction:column; gap:14px;">
          <div style="height:1px; background:#E3DED4;"></div>
          <div style="font-size:12px; color:#8B877C;">Hairline divider #E3DED4 — 1px, warm</div>
          <div style="height:44px; background:#FFFFFF; border:1px solid #E3DED4; border-radius:10px; box-shadow:0 1px 2px rgba(27,26,22,0.04), 0 8px 24px rgba(27,26,22,0.05);"></div>
          <div style="font-size:12px; color:#8B877C;">Card rests on one soft, low shadow. Never heavy.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- COMPONENTS -->
  <section style="display:grid; grid-template-columns:200px 1fr; gap:40px;">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">04 — Components</div>
      <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px;">The working parts</div>
    </div>
    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- metric cards — tinted, icon chip + label -->
      <div style="display:grid; grid-template-columns:1.3fr 1.3fr 1fr; gap:16px;">
        <div style="background:#FAF8F3; border-radius:16px; padding:20px 22px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="width:30px; height:30px; border-radius:9px; background:#EFE9DD; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="16" height="16" viewBox="0 0 16 16"><rect x="3" y="2.5" width="10" height="11" rx="1.6" fill="none" stroke="#1E4A38" stroke-width="1.4"></rect><line x1="5.6" y1="6" x2="10.4" y2="6" stroke="#1E4A38" stroke-width="1.4" stroke-linecap="round"></line><line x1="5.6" y1="9" x2="10.4" y2="9" stroke="#1E4A38" stroke-width="1.4" stroke-linecap="round"></line></svg></span>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#7C7869;">This week's buy</span>
          </div>
          <div style="display:flex; align-items:baseline; gap:7px; margin-top:16px;"><span style="font-size:16px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:46px; font-weight:500; letter-spacing:-0.02em; line-height:1;">572,960</span></div>
          <div style="font-size:13px; color:#7C7869; margin-top:10px;">across <strong style="font-weight:700; color:#57544C;">71 items</strong> · 4-week horizon</div>
        </div>
        <div style="background:#E7F1EA; border-radius:16px; padding:20px 22px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="width:30px; height:30px; border-radius:9px; background:#FFFFFF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="16" height="16" viewBox="0 0 16 16"><path d="M8 12.5 V4 M4.6 7.4 L8 4 L11.4 7.4" fill="none" stroke="#2E8B5D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#5E8A72;">Estimated savings</span>
          </div>
          <div style="display:flex; align-items:baseline; gap:7px; margin-top:16px;"><span style="font-size:16px; font-weight:600; color:#2E8B5D;">GHS</span><span style="font-family:'Newsreader',serif; font-size:46px; font-weight:500; letter-spacing:-0.02em; line-height:1; color:#2E8B5D;">62,263</span></div>
          <div style="display:flex; align-items:center; gap:6px; margin-top:10px;"><span style="display:inline-flex; align-items:center; gap:5px; background:#FFFFFF; color:#2E8B5D; font-size:12px; font-weight:600; padding:3px 8px; border-radius:999px;">▲ 10.9%</span><span style="font-size:13px; color:#5E8A72;">vs last year's pattern</span></div>
        </div>
        <div style="background:#F0E3C0; border-radius:16px; padding:20px 22px; display:flex; flex-direction:column; justify-content:center; gap:8px;">
          <div style="display:flex; align-items:center; gap:9px;">
            <span style="width:26px; height:26px; border-radius:8px; background:#FFFFFF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 16 16"><circle cx="8" cy="8" r="5.4" fill="none" stroke="#C0791F" stroke-width="1.4"></circle><circle cx="8" cy="8" r="1.6" fill="#C0791F"></circle></svg></span>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.08em; text-transform:uppercase; color:#9A7433;">Forecast accuracy</span>
          </div>
          <div><div style="font-size:32px; font-weight:600; letter-spacing:-0.01em;">75.5<span style="font-size:18px; color:#9A7433;">%</span></div></div>
        </div>
      </div>
      <div style="font-size:12px; color:#8B877C; margin-top:-6px;">Soft tinted fills, no border. A white (or tinted) icon chip pairs with the mono label on the top row; the serif figure sits below. Each tint carries meaning — neutral for spend, green for savings, brass for forecast.</div>

      <!-- pills + buttons + week selector -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px; display:flex; flex-direction:column; gap:18px;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Status pills</div>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span style="display:inline-flex; align-items:center; gap:7px; background:#E8F1EB; color:#1E4A38; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;"><span style="width:6px; height:6px; border-radius:50%; background:#2E8B5D;"></span>Plan ready</span>
            <span style="display:inline-flex; align-items:center; gap:7px; background:#F4F1EA; color:#57544C; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px; border:1px solid #E3DED4;">75.5% accuracy</span>
            <span style="display:inline-flex; align-items:center; gap:7px; background:#FBF0DE; color:#8A5A12; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;">Forecast</span>
            <span style="display:inline-flex; align-items:center; gap:6px; background:#2E8B5D; color:#fff; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;">▲ up 15%</span>
            <span style="display:inline-flex; align-items:center; gap:6px; background:#F4E5E0; color:#B0472E; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;">▼ down 8%</span>
          </div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-top:6px;">Buttons</div>
          <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <button style="font-family:inherit; background:#1E4A38; color:#fff; border:none; font-size:14px; font-weight:600; padding:11px 20px; border-radius:8px; cursor:pointer;">Review This Week's Buy</button>
            <button style="font-family:inherit; background:#FFFFFF; color:#1B1A16; border:1px solid #D3CCBE; font-size:14px; font-weight:600; padding:11px 20px; border-radius:8px; cursor:pointer;">Export</button>
            <button style="font-family:inherit; background:transparent; color:#1E4A38; border:none; font-size:14px; font-weight:600; padding:11px 8px; border-radius:8px; cursor:pointer;">View forecast →</button>
          </div>
        </div>
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:24px; display:flex; flex-direction:column; gap:18px;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Week selector</div>
          <div style="display:inline-flex; background:#EEEAE1; border-radius:10px; padding:4px; gap:4px; align-self:flex-start;">
            <button style="font-family:inherit; background:#FFFFFF; color:#1B1A16; border:1px solid #E3DED4; font-size:13px; font-weight:600; padding:9px 16px; border-radius:7px; cursor:pointer; box-shadow:0 1px 2px rgba(27,26,22,0.06);">Jan 2</button>
            <button style="font-family:inherit; background:transparent; color:#57544C; border:none; font-size:13px; font-weight:500; padding:9px 16px; border-radius:7px; cursor:pointer;">Jan 9</button>
            <button style="font-family:inherit; background:transparent; color:#57544C; border:none; font-size:13px; font-weight:500; padding:9px 16px; border-radius:7px; cursor:pointer;">Jan 16</button>
            <button style="font-family:inherit; background:transparent; color:#57544C; border:none; font-size:13px; font-weight:500; padding:9px 16px; border-radius:7px; cursor:pointer;">Jan 23</button>
          </div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-top:6px;">Upload dropzone</div>
          <div style="border:1.5px dashed #C9C1B2; border-radius:10px; background:#FBF9F4; padding:22px; text-align:center;">
            <div style="font-weight:600; font-size:14px;">Drop your sales export</div>
            <div style="font-size:12px; color:#8B877C; margin-top:4px;">CSV · we'll match and verify every row</div>
          </div>
        </div>
      </div>

      <!-- data table -->
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; overflow:hidden;">
        <div style="padding:18px 24px; border-bottom:1px solid #E3DED4; display:flex; justify-content:space-between; align-items:center;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Data table row — sorted by spend, change surfaced</div>
          <div style="display:flex; align-items:baseline; gap:6px;"><span style="font-size:12px; color:#8B877C;">Week total</span><span style="font-family:'IBM Plex Mono'; font-size:15px; font-weight:600;">GHS 572,960</span></div>
        </div>
        <div style="display:grid; grid-template-columns:1.6fr 0.9fr 1fr 1fr 0.9fr 0.9fr; padding:12px 24px; background:#FBF9F4; border-bottom:1px solid #E3DED4; font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.08em; text-transform:uppercase; color:#8B877C;">
          <div>Item</div><div style="text-align:right;">Buy units</div><div style="text-align:right;">Total GHS</div><div style="text-align:center;">vs last wk</div><div style="text-align:right;">Forecast</div><div style="text-align:right;">Unit GHS</div>
        </div>
        <div style="display:grid; grid-template-columns:1.6fr 0.9fr 1fr 1fr 0.9fr 0.9fr; padding:16px 24px; align-items:center; border-bottom:1px solid #F0ECE4; font-variant-numeric:tabular-nums;">
          <div style="font-weight:600; font-size:15px;">Chicken Breast</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:17px; font-weight:600;">50</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:15px; font-weight:600;">8,712</div>
          <div style="text-align:center;"><span style="display:inline-flex; align-items:center; gap:5px; background:#E8F1EB; color:#2E8B5D; font-size:12px; font-weight:600; padding:4px 9px; border-radius:999px;">▲ 16%</span></div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">43</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">174.24</div>
        </div>
        <div style="display:grid; grid-template-columns:1.6fr 0.9fr 1fr 1fr 0.9fr 0.9fr; padding:16px 24px; align-items:center; border-bottom:1px solid #F0ECE4; font-variant-numeric:tabular-nums;">
          <div style="font-weight:600; font-size:15px;">Lamb Chops</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:17px; font-weight:600;">28</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:15px; font-weight:600;">6,440</div>
          <div style="text-align:center;"><span style="color:#8B877C; font-size:13px;">—</span></div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">27</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">230.00</div>
        </div>
        <div style="display:grid; grid-template-columns:1.6fr 0.9fr 1fr 1fr 0.9fr 0.9fr; padding:16px 24px; align-items:center; font-variant-numeric:tabular-nums;">
          <div style="font-weight:600; font-size:15px;">Turkey Dishes</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:17px; font-weight:600;">19</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:15px; font-weight:600;">3,021</div>
          <div style="text-align:center;"><span style="display:inline-flex; align-items:center; gap:5px; background:#F4E5E0; color:#B0472E; font-size:12px; font-weight:600; padding:4px 9px; border-radius:999px;">▼ 9%</span></div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">21</div>
          <div style="text-align:right; font-family:'IBM Plex Mono'; font-size:13px; color:#8B877C;">159.00</div>
        </div>
      </div>

      <!-- ============ DATA VISUALIZATION SYSTEM ============ -->
      <div style="display:flex; align-items:baseline; gap:14px; margin-top:8px;">
        <div style="font-family:'Newsreader',serif; font-size:22px;">Data visualization</div>
        <div style="font-size:13px; color:#8B877C;">One chart family — line &amp; bar — defined once, inherited everywhere.</div>
      </div>

      <!-- GOVERNING PRINCIPLE + LEGEND -->
      <div style="background:#1E4A38; border-radius:12px; padding:24px 28px; color:#F4F1EA; display:grid; grid-template-columns:1fr 1px 1.15fr; gap:28px; align-items:center;">
        <div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#E9C46A;">The governing rule</div>
          <div style="font-family:'Newsreader',serif; font-size:23px; margin-top:8px; line-height:1.2;">Certain data never looks like predicted data.</div>
          <div style="font-size:13.5px; color:#B9C7BE; margin-top:8px; line-height:1.55;">History is solid and neutral-dark — it happened. Forecast is dashed brass with a confidence band that widens into the future. Weight, line-style, and markers all carry the distinction, so it survives colour-blindness and greyscale print.</div>
        </div>
        <div style="background:rgba(255,255,255,0.12); width:1px; height:100%;"></div>
        <div style="display:flex; flex-direction:column; gap:14px;">
          <div style="display:flex; align-items:center; gap:12px;">
            <svg width="46" height="14" style="flex-shrink:0;"><line x1="2" y1="7" x2="44" y2="7" stroke="#C7CBBE" stroke-width="2.5"></line><circle cx="23" cy="7" r="3.5" fill="#EDE9E1"></circle></svg>
            <div><span style="font-size:13.5px; font-weight:600;">Actual</span> <span style="font-size:12.5px; color:#B9C7BE;">— solid, neutral. Known &amp; certain.</span></div>
          </div>
          <div style="display:flex; align-items:center; gap:12px;">
            <svg width="46" height="14" style="flex-shrink:0;"><line x1="2" y1="7" x2="44" y2="7" stroke="#E9C46A" stroke-width="2.25" stroke-dasharray="1.5 5"></line><path d="M23,3 L27,7 L23,11 L19,7 Z" fill="#1E4A38" stroke="#E9C46A" stroke-width="1.4"></path></svg>
            <div><span style="font-size:13.5px; font-weight:600; color:#E9C46A;">Forecast</span> <span style="font-size:12.5px; color:#B9C7BE;">— dashed brass, diamond markers. Predicted.</span></div>
          </div>
          <div style="display:flex; align-items:center; gap:12px;">
            <svg width="46" height="14" style="flex-shrink:0;"><path d="M2,10 L44,3 L44,11 L2,12 Z" fill="rgba(233,196,106,0.28)"></path></svg>
            <div><span style="font-size:13.5px; font-weight:600;">Confidence band</span> <span style="font-size:12.5px; color:#B9C7BE;">— widens with uncertainty.</span></div>
          </div>
          <div style="display:flex; align-items:center; gap:12px;">
            <svg width="46" height="14" style="flex-shrink:0;"><line x1="23" y1="1" x2="23" y2="13" stroke="#EDE9E1" stroke-width="1" stroke-dasharray="3 3"></line></svg>
            <div><span style="font-size:13.5px; font-weight:600;">Now divider</span> <span style="font-size:12.5px; color:#B9C7BE;">— seam between history &amp; forecast.</span></div>
          </div>
        </div>
      </div>

      <!-- LINE CHART — TWO STATES -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <!-- rising -->
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:22px 24px; box-shadow:0 1px 2px rgba(27,26,22,0.04); position:relative;">
          <div style="display:flex; justify-content:space-between; align-items:baseline;">
            <div>
              <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Line chart · rising item</div>
              <div style="font-family:'Newsreader',serif; font-size:20px; margin-top:3px;">Chicken Breast — trending up</div>
            </div>
            <span style="display:inline-flex; align-items:center; gap:5px; background:#E8F1EB; color:#2E8B5D; font-size:12px; font-weight:600; padding:4px 9px; border-radius:999px;">▲ 16%</span>
          </div>
          <div style="position:relative; margin-top:14px;">
            <svg viewBox="0 0 640 288" style="width:100%; height:auto; display:block;">
              <line x1="56" y1="83" x2="612" y2="83" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="138" x2="612" y2="138" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="193" x2="612" y2="193" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="248" x2="612" y2="248" stroke="#E3DED4" stroke-width="1"></line>
              <text x="46" y="87" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">75</text>
              <text x="46" y="142" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">50</text>
              <text x="46" y="197" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">25</text>
              <path d="M434,116 L478.5,100.6 L523,85.2 L567.5,69.8 L612,54.4 L612,107.2 L567.5,109.4 L523,111.6 L478.5,113.8 L434,116 Z" fill="rgba(192,121,31,0.13)"></path>
              <polyline points="56,160 90.4,155.6 124.7,157.8 159.1,149 193.5,151.2 227.8,142.4 262.2,144.6 296.5,133.6 330.9,138 365.3,127 399.6,122.6 434,116" fill="none" stroke="#57544C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
              <polyline points="434,116 478.5,107.2 523,98.4 567.5,89.6 612,80.8" fill="none" stroke="#C0791F" stroke-width="2.25" stroke-dasharray="1.5 6" stroke-linecap="round" stroke-linejoin="round"></polyline>
              <line x1="434" y1="22" x2="434" y2="248" stroke="#8B877C" stroke-width="1" stroke-dasharray="4 4"></line>
              <line x1="523" y1="22" x2="523" y2="248" stroke="#1B1A16" stroke-width="1" opacity="0.16"></line>
              <path d="M478.5,103.2 L482.5,107.2 L478.5,111.2 L474.5,107.2 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <path d="M567.5,85.6 L571.5,89.6 L567.5,93.6 L563.5,89.6 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <path d="M612,76.8 L616,80.8 L612,84.8 L608,80.8 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <circle cx="434" cy="116" r="4" fill="#57544C"></circle>
              <circle cx="523" cy="98.4" r="5" fill="#FFFFFF" stroke="#C0791F" stroke-width="2"></circle>
              <rect x="410" y="26" width="48" height="17" rx="4" fill="#57544C"></rect>
              <text x="434" y="38" text-anchor="middle" fill="#F4F1EA" font-family="IBM Plex Mono, monospace" font-size="10" letter-spacing="0.14em">NOW</text>
              <text x="56" y="268" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">Nov</text>
              <text x="245" y="268" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">Dec</text>
              <text x="523" y="268" text-anchor="middle" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">+2wk</text>
              <text x="612" y="268" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">+4wk</text>
            </svg>
            <div style="position:absolute; top:8px; right:8px; background:#FFFFFF; border:1px solid #E3DED4; border-radius:9px; padding:10px 12px; box-shadow:0 6px 18px rgba(27,26,22,0.10); min-width:150px;">
              <div style="font-size:11px; color:#8B877C;">Week of Jan 16</div>
              <div style="display:flex; align-items:center; gap:7px; margin-top:5px;"><span style="width:9px; height:9px; transform:rotate(45deg); border:1.5px solid #C0791F; display:inline-block;"></span><span style="font-size:11px; font-weight:600; color:#C0791F;">Forecast</span></div>
              <div style="font-family:'IBM Plex Mono',monospace; font-size:15px; font-weight:600; margin-top:6px;">68 units</div>
              <div style="font-family:'IBM Plex Mono',monospace; font-size:12px; color:#57544C; margin-top:2px;">≈ GHS 11,846</div>
              <div style="font-size:10.5px; color:#8B877C; margin-top:5px;">range 62–74 units</div>
            </div>
          </div>
        </div>

        <!-- steady -->
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:22px 24px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
          <div style="display:flex; justify-content:space-between; align-items:baseline;">
            <div>
              <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Line chart · steady item</div>
              <div style="font-family:'Newsreader',serif; font-size:20px; margin-top:3px;">Salads — stable demand</div>
            </div>
            <span style="display:inline-flex; align-items:center; gap:5px; background:#F4F1EA; color:#57544C; font-size:12px; font-weight:600; padding:4px 9px; border-radius:999px; border:1px solid #E3DED4;">— steady</span>
          </div>
          <div style="position:relative; margin-top:14px;">
            <svg viewBox="0 0 640 288" style="width:100%; height:auto; display:block;">
              <line x1="56" y1="83" x2="612" y2="83" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="138" x2="612" y2="138" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="193" x2="612" y2="193" stroke="#EFEBE2" stroke-width="1"></line>
              <line x1="56" y1="248" x2="612" y2="248" stroke="#E3DED4" stroke-width="1"></line>
              <text x="46" y="87" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">75</text>
              <text x="46" y="142" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">50</text>
              <text x="46" y="197" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">25</text>
              <path d="M434,138 L478.5,133.6 L523,131.4 L567.5,130.3 L612,128.1 L612,141.3 L567.5,141.3 L523,140.2 L478.5,140.2 L434,138 Z" fill="rgba(192,121,31,0.13)"></path>
              <polyline points="56,138 90.4,140.2 124.7,135.8 159.1,138 193.5,138 227.8,135.8 262.2,140.2 296.5,138 330.9,135.8 365.3,138 399.6,140.2 434,138" fill="none" stroke="#57544C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
              <polyline points="434,138 478.5,136.9 523,135.8 567.5,135.8 612,134.7" fill="none" stroke="#C0791F" stroke-width="2.25" stroke-dasharray="1.5 6" stroke-linecap="round" stroke-linejoin="round"></polyline>
              <line x1="434" y1="22" x2="434" y2="248" stroke="#8B877C" stroke-width="1" stroke-dasharray="4 4"></line>
              <path d="M478.5,132.9 L482.5,136.9 L478.5,140.9 L474.5,136.9 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <path d="M523,131.8 L527,135.8 L523,139.8 L519,135.8 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <path d="M567.5,131.8 L571.5,135.8 L567.5,139.8 L563.5,135.8 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <path d="M612,130.7 L616,134.7 L612,138.7 L608,134.7 Z" fill="#FFFFFF" stroke="#C0791F" stroke-width="1.5"></path>
              <circle cx="434" cy="138" r="4" fill="#57544C"></circle>
              <rect x="410" y="26" width="48" height="17" rx="4" fill="#57544C"></rect>
              <text x="434" y="38" text-anchor="middle" fill="#F4F1EA" font-family="IBM Plex Mono, monospace" font-size="10" letter-spacing="0.14em">NOW</text>
              <text x="56" y="268" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">Nov</text>
              <text x="245" y="268" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">Dec</text>
              <text x="523" y="268" text-anchor="middle" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">+2wk</text>
              <text x="612" y="268" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5">+4wk</text>
            </svg>
            <div style="position:absolute; bottom:70px; left:50%; transform:translateX(-14px); font-size:11px; color:#8B877C; background:#FBF9F4; border:1px solid #E3DED4; border-radius:999px; padding:3px 9px;">tight band = high confidence</div>
          </div>
        </div>
      </div>

      <!-- BAR CHART + ACCESSIBILITY -->
      <div style="display:grid; grid-template-columns:1.5fr 1fr; gap:16px;">
        <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:22px 24px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
          <div style="display:flex; justify-content:space-between; align-items:baseline;">
            <div>
              <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Bar chart · discrete comparison</div>
              <div style="font-family:'Newsreader',serif; font-size:20px; margin-top:3px;">Biggest movers this week</div>
            </div>
            <div style="display:flex; gap:14px;">
              <div style="display:flex; align-items:center; gap:6px;"><span style="width:11px; height:11px; border-radius:3px; background:#2E8B5D;"></span><span style="font-size:12px; color:#57544C;">▲ up</span></div>
              <div style="display:flex; align-items:center; gap:6px;"><span style="width:11px; height:11px; border-radius:3px; background:#B0472E;"></span><span style="font-size:12px; color:#57544C;">▼ down</span></div>
            </div>
          </div>
          <svg viewBox="0 0 640 300" style="width:100%; height:auto; display:block; margin-top:12px;">
            <line x1="56" y1="70" x2="612" y2="70" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="110" x2="612" y2="110" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="190" x2="612" y2="190" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="230" x2="612" y2="230" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="150" x2="612" y2="150" stroke="#D3CCBE" stroke-width="1.25"></line>
            <text x="46" y="74" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">+20</text>
            <text x="46" y="154" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">0</text>
            <text x="46" y="234" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">−20</text>
            <rect x="83.6" y="86" width="56" height="64" rx="5" fill="#2E8B5D"></rect>
            <rect x="194.8" y="106" width="56" height="44" rx="5" fill="#2E8B5D"></rect>
            <rect x="306" y="130" width="56" height="20" rx="5" fill="#2E8B5D"></rect>
            <rect x="417.2" y="150" width="56" height="24" rx="5" fill="#B0472E"></rect>
            <rect x="528.4" y="150" width="56" height="36" rx="5" fill="#B0472E"></rect>
            <text x="111.6" y="78" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲16%</text>
            <text x="222.8" y="98" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲11%</text>
            <text x="334" y="122" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲5%</text>
            <text x="445.2" y="188" text-anchor="middle" fill="#B0472E" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▼6%</text>
            <text x="556.4" y="200" text-anchor="middle" fill="#B0472E" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▼9%</text>
            <text x="111.6" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Chicken</text>
            <text x="222.8" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Burgers</text>
            <text x="334" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Salads</text>
            <text x="445.2" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Spaghetti</text>
            <text x="556.4" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Turkey</text>
          </svg>
          <div style="font-size:12px; color:#8B877C; margin-top:6px;">Same axis, gridline &amp; radius language as the line chart — direction is labelled (▲/▼), never colour alone.</div>
        </div>

        <div style="background:#FBF9F4; border:1px solid #E3DED4; border-radius:12px; padding:22px 24px;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Baked-in accessibility</div>
          <div style="display:flex; flex-direction:column; gap:16px; margin-top:16px;">
            <div style="display:flex; gap:12px;">
              <span style="flex-shrink:0; width:22px; height:22px; border-radius:6px; background:#1E4A38; color:#E9C46A; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">1</span>
              <div><div style="font-size:14px; font-weight:600;">Never colour alone</div><div style="font-size:13px; color:#57544C; line-height:1.5; margin-top:2px;">Solid vs dashed, circle vs diamond, ▲/▼ labels — meaning holds in greyscale.</div></div>
            </div>
            <div style="display:flex; gap:12px;">
              <span style="flex-shrink:0; width:22px; height:22px; border-radius:6px; background:#1E4A38; color:#E9C46A; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">2</span>
              <div><div style="font-size:14px; font-weight:600;">3:1 minimum contrast</div><div style="font-size:13px; color:#57544C; line-height:1.5; margin-top:2px;">Every line, bar and label clears 3:1 on its background. Gridlines stay quiet, but data never does.</div></div>
            </div>
            <div style="display:flex; gap:12px;">
              <span style="flex-shrink:0; width:22px; height:22px; border-radius:6px; background:#1E4A38; color:#E9C46A; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">3</span>
              <div><div style="font-size:14px; font-weight:600;">Tooltip is the source of truth</div><div style="font-size:13px; color:#57544C; line-height:1.5; margin-top:2px;">Exact values are read on hover — never inferred from a colour region.</div></div>
            </div>
          </div>
        </div>
      </div>

      <!-- pricing tier — image-topped, index badge, name straddle -->
      <div style="display:flex; flex-direction:column; gap:16px;">
        <div style="display:flex; justify-content:space-between; align-items:baseline;">
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Pricing tier card — image header, index badge, name straddling the artwork</div>
          <div style="font-size:12px; color:#8B877C;">middle tier anchors · headers accept real artwork</div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:18px; align-items:start;">

          <!-- BASIC -->
          <div style="position:relative; background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:14px 14px 14px; box-shadow:0 1px 2px rgba(27,26,22,0.04), 0 14px 30px rgba(27,26,22,0.05);">
            <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(120px 90px at 24% 30%, #C3DBCB, transparent 70%), radial-gradient(150px 120px at 82% 62%, #D6E2D0, transparent 72%), linear-gradient(135deg,#E9E6DC,#EDEAE0);">
              <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#57544C;">#1</span>
            </div>
            <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Basic</div>
            <div style="padding:44px 12px 4px; text-align:center;">
              <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">250</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
              <div style="font-size:13px; color:#8B877C; margin-top:8px;">The essential purchasing tool</div>
              <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
            </div>
            <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
            <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
              <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> Weekly buy plan</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> Demand forecasts, all items</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> 4-week horizon</div>
            </div>
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#FFFFFF; color:#1E4A38; border:1px solid #1E4A38; font-size:14px; font-weight:600; padding:12px; border-radius:9px; cursor:pointer;">Choose Basic</button>
          </div>

          <!-- PLUS — anchor -->
          <div style="position:relative; background:#FFFFFF; border:1.5px solid #E9C46A; border-radius:16px; padding:14px; box-shadow:0 2px 4px rgba(27,26,22,0.06), 0 22px 44px rgba(30,74,56,0.14);">
            <span style="position:absolute; top:-11px; left:50%; transform:translateX(-50%); background:#1E4A38; color:#E9C46A; font-size:11px; font-weight:700; letter-spacing:0.04em; padding:5px 14px; border-radius:999px; white-space:nowrap; z-index:3;">Most popular</span>
            <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(130px 100px at 20% 34%, #EED9A0, transparent 70%), radial-gradient(150px 120px at 80% 56%, #CFDDD0, transparent 72%), radial-gradient(90px 80px at 62% 82%, #E7C98B, transparent 70%), linear-gradient(135deg,#F1E9D8,#ECE6D8);">
              <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#8A5A12;">#2</span>
            </div>
            <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Plus</div>
            <div style="padding:44px 12px 4px; text-align:center;">
              <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">450</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
              <div style="font-size:13px; color:#8B877C; margin-top:8px;">Be told, don't check</div>
              <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
            </div>
            <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
            <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; color:#8B877C;"><span style="color:#9A9488; font-size:13px;">✓</span> Everything in Basic</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Weekly demand alerts</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Extended forecast horizon</div>
            </div>
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#1E4A38; color:#F4F1EA; border:none; font-size:14px; font-weight:700; padding:13px; border-radius:9px; cursor:pointer;">Choose Plus</button>
          </div>

          <!-- PRO -->
          <div style="position:relative; background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:14px; box-shadow:0 1px 2px rgba(27,26,22,0.04), 0 14px 30px rgba(27,26,22,0.05);">
            <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(130px 100px at 22% 32%, #B4C7BB, transparent 70%), radial-gradient(150px 120px at 80% 62%, #C7D2CA, transparent 72%), radial-gradient(90px 80px at 58% 84%, #A9BFB2, transparent 70%), linear-gradient(135deg,#E4E7E0,#E0E3DC);">
              <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#57544C;">#3</span>
            </div>
            <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Pro</div>
            <div style="padding:44px 12px 4px; text-align:center;">
              <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">900</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
              <div style="font-size:13px; color:#8B877C; margin-top:8px;">For groups &amp; chains</div>
              <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
            </div>
            <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
            <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; color:#8B877C;"><span style="color:#9A9488; font-size:13px;">✓</span> Everything in Plus</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Multi-location &amp; team members</div>
              <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Automated POS ingestion</div>
            </div>
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#FFFFFF; color:#1E4A38; border:1px solid #1E4A38; font-size:14px; font-weight:600; padding:12px; border-radius:9px; cursor:pointer;">Choose Pro</button>
          </div>

        </div>
      </div>

      <!-- app chrome: top bar + icon rail -->
      <div style="display:flex; flex-direction:column; gap:14px;">
        <div style="display:flex; align-items:baseline; gap:12px;">
          <div style="font-family:'Newsreader',serif; font-size:18px;">App chrome</div>
          <span style="font-size:12px; color:#8B877C;">— top menu bar for destinations, an icon-only rail for quick jumps</span>
        </div>

        <!-- top bar spec -->
        <div style="background:#FBFAF6; border:1px solid #E7E1D6; border-radius:12px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:24px;">
          <div style="display:flex; align-items:center; gap:11px;">
            <div style="width:30px; height:30px; border-radius:8px; background:#1E4A38; display:flex; align-items:center; justify-content:center;"><div style="width:12px; height:12px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
            <span style="font-size:17px; font-weight:600; letter-spacing:-0.01em;">Larder</span>
          </div>
          <nav style="display:flex; align-items:center; gap:2px; background:#F1ECE1; padding:5px; border-radius:12px;">
            <span style="padding:8px 16px; border-radius:9px; background:#1E4A38; color:#F6F1E7; font-size:13px; font-weight:600;">Dashboard</span>
            <span style="padding:8px 16px; border-radius:9px; color:#57544C; font-size:13px; font-weight:500;">This Week's Buy</span>
            <span style="padding:8px 16px; border-radius:9px; color:#57544C; font-size:13px; font-weight:500;">Forecasts</span>
            <span style="padding:8px 16px; border-radius:9px; color:#57544C; font-size:13px; font-weight:500;">Upload Data</span>
          </nav>
          <div style="display:flex; align-items:center; gap:9px;">
            <span style="width:34px; height:34px; border-radius:9px; background:#FFFFFF; border:1px solid #E7E1D6; display:flex; align-items:center; justify-content:center; color:#57544C;"><svg width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="9" cy="9" r="6"></circle><line x1="13.5" y1="13.5" x2="17" y2="17"></line></svg></span>
            <div style="display:flex; align-items:center; gap:8px; padding:3px 8px 3px 3px; border-radius:999px; background:#FFFFFF; border:1px solid #E7E1D6;">
              <div style="width:28px; height:28px; border-radius:8px; background:#E9C46A; color:#1E4A38; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px;">AK</div>
              <span style="font-size:12.5px; font-weight:600;">Akwaaba</span>
            </div>
          </div>
        </div>

        <!-- rail + note -->
        <div style="display:flex; gap:16px;">
          <div style="display:flex; flex-direction:column; align-items:center; gap:6px; background:#FFFFFF; border:1px solid #E7E1D6; border-radius:20px; padding:12px 10px; flex-shrink:0;">
            <span style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7;"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="6" height="6" rx="1.5"></rect><rect x="11" y="3" width="6" height="6" rx="1.5"></rect><rect x="3" y="11" width="6" height="6" rx="1.5"></rect><rect x="11" y="11" width="6" height="6" rx="1.5"></rect></svg></span>
            <span style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C;"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><rect x="4" y="3" width="12" height="14" rx="2"></rect><line x1="7" y1="7" x2="13" y2="7"></line><line x1="7" y1="10" x2="13" y2="10"></line><line x1="7" y1="13" x2="11" y2="13"></line></svg></span>
            <span style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C;"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14 L8 9 L11 12 L17 5"></path><path d="M13 5 H17 V9"></path></svg></span>
            <span style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C;"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13 V4"></path><path d="M6.5 7.5 L10 4 L13.5 7.5"></path><path d="M4 13.5 V16 H16 V13.5"></path></svg></span>
            <div style="width:22px; height:1px; background:#EBE5DA; margin:6px 0;"></div>
            <span style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C;"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="4" y1="7" x2="16" y2="7"></line><circle cx="8" cy="7" r="2"></circle><line x1="4" y1="13" x2="16" y2="13"></line><circle cx="13" cy="13" r="2"></circle></svg></span>
          </div>
          <div style="flex:1; background:#FFFFFF; border:1px solid #E3DED4; border-radius:12px; padding:20px 24px; display:flex; flex-direction:column; justify-content:center; gap:8px;">
            <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Navigation</div>
            <div style="font-size:14px; color:#57544C; line-height:1.55; max-width:440px;">A light <strong style="font-weight:700; color:#1B1A16;">top menu bar</strong> carries the brand, the primary destinations as a segmented pill (active in evergreen), and search / notifications / account. A <strong style="font-weight:700; color:#1B1A16;">floating icon rail</strong> on the left mirrors the same destinations as icon-only shortcuts — active tab filled evergreen, utilities settle to the bottom. Both stay pinned while content scrolls.</div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- FOOTER -->
  <div style="border-top:1px solid #D3CCBE; padding-top:24px; display:flex; justify-content:space-between; align-items:center; gap:24px;">
    <div style="font-size:14px; color:#57544C;">Approve this system and I'll design <span style="font-weight:600; color:#1B1A16;">Screen 1 — Dashboard</span> next, one screen at a time.</div>
    <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#8B877C;">Larder · GHS · Ghana</div>
  </div>

</div>
</div>
</x-dc>


</body></html>
</body>
</html>

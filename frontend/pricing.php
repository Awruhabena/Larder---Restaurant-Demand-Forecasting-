<?php
/**
 * pricing.php
 * -----------
 * Static content, no backend connection. Each "Choose X" links to
 * billing.php?plan=X, which starts the checkout/session flow.
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Plans</title>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="larder-tokens.css">
</head>
<body style="font-family:'Hanken Grotesk',sans-serif; color:#1B1A16; margin:0; background:#F4F1EA;">

<header style="position:sticky; top:0; z-index:30; background:#FBFAF6; border-bottom:1px solid #E7E1D6;">
    <div style="max-width:1560px; margin:0 auto; padding:16px 34px; display:flex; align-items:center; justify-content:space-between; gap:28px;">
      <div style="display:flex; align-items:center; gap:11px; flex:1;">
        <div style="width:32px; height:32px; border-radius:9px; background:#1E4A38; display:flex; align-items:center; justify-content:center;"><div style="width:13px; height:13px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
        <span style="font-size:19px; font-weight:600; letter-spacing:-0.01em;">Larder</span>
      </div>
      <nav style="display:flex; align-items:center; gap:2px; background:#F1ECE1; padding:5px; border-radius:12px;">
        <a href="dashboard.php" style="padding:9px 18px; border-radius:9px; color:#57544C; font-size:14px; font-weight:500; text-decoration:none;">Dashboard</a>
        <a href="buy-plan.php" style="padding:9px 18px; border-radius:9px; color:#57544C; font-size:14px; font-weight:500; text-decoration:none;">This Week's Buy</a>
        <a href="forecast-detail.php" style="padding:9px 18px; border-radius:9px; color:#57544C; font-size:14px; font-weight:500; text-decoration:none;">Forecasts</a>
        <a href="upload.php" style="padding:9px 18px; border-radius:9px; color:#57544C; font-size:14px; font-weight:500; text-decoration:none;">Upload Data</a>
      </nav>
      <div style="display:flex; align-items:center; gap:10px; flex:1; justify-content:flex-end;">
        <button style="font-family:inherit; width:38px; height:38px; border-radius:10px; background:#FFFFFF; border:1px solid #E7E1D6; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#57544C;">
          <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="9" cy="9" r="6"></circle><line x1="13.5" y1="13.5" x2="17" y2="17"></line></svg>
        </button>
        <a href="alerts.php" style="width:38px; height:38px; border-radius:10px; background:#FFFFFF; border:1px solid #E7E1D6; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#57544C; position:relative; text-decoration:none;">
          <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8 A4 4 0 0 1 14 8 C14 13 16 14 16 14 H4 C4 14 6 13 6 8 Z"></path><path d="M8.5 16.5 A1.6 1.6 0 0 0 11.5 16.5"></path></svg>
          <span style="position:absolute; top:8px; right:9px; width:6px; height:6px; border-radius:50%; background:#C0791F; border:1.5px solid #FBFAF6;"></span>
        </a>
        <div style="display:flex; align-items:center; gap:9px; padding:4px 8px 4px 4px; border-radius:999px; background:#FFFFFF; border:1px solid #E7E1D6;">
          <div style="width: 30px; height: 30px; border-radius: 40px; background: #E9C46A; color: #1E4A38; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px">AK</div>
          <span style="font-size:13px; font-weight:600; color:#1B1A16;">Akwaaba</span>
          <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="#8B877C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8 L10 12 L14 8"></path></svg>
        </div>
      </div>
    </div>
  </header>

<div style="display:flex; flex:1; min-height:0;">

  <!-- ============ ICON RAIL ============ -->
  <aside style="flex-shrink:0; padding:24px 0 24px 22px;">
    <div style="position:sticky; top:90px; display:flex; flex-direction:column; align-items:center; gap:6px; background:#FFFFFF; border:1px solid #E7E1D6; border-radius:20px; padding:12px 10px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
      <a href="dashboard.php" title="Dashboard" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="6" height="6" rx="1.5"></rect><rect x="11" y="3" width="6" height="6" rx="1.5"></rect><rect x="3" y="11" width="6" height="6" rx="1.5"></rect><rect x="11" y="11" width="6" height="6" rx="1.5"></rect></svg>
      </a>
      <a href="buy-plan.php" title="This Week's Buy" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><rect x="4" y="3" width="12" height="14" rx="2"></rect><line x1="7" y1="7" x2="13" y2="7"></line><line x1="7" y1="10" x2="13" y2="10"></line><line x1="7" y1="13" x2="11" y2="13"></line></svg>
      </a>
      <a href="forecast-detail.php" title="Forecasts" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14 L8 9 L11 12 L17 5"></path><path d="M13 5 H17 V9"></path></svg>
      </a>
      <a href="upload.php" title="Upload Data" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13 V4"></path><path d="M6.5 7.5 L10 4 L13.5 7.5"></path><path d="M4 13.5 V16 H16 V13.5"></path></svg>
      </a>
      <div style="width:22px; height:1px; background:#EBE5DA; margin:6px 0;"></div>
      <a href="pricing.php" title="Pricing" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M4 8 L10 3 L16 8 L10 17 Z"></path></svg>
      </a>
      <a href="billing.php" title="Billing" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><rect x="3" y="5" width="14" height="10" rx="2"></rect><line x1="3" y1="8.5" x2="17" y2="8.5"></line></svg>
      </a>
      <a href="settings.php" title="Settings" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="4" y1="7" x2="16" y2="7"></line><circle cx="8" cy="7" r="2"></circle><line x1="4" y1="13" x2="16" y2="13"></line><circle cx="13" cy="13" r="2"></circle></svg>
      </a>
      <div style="width:22px; height:1px; background:#EBE5DA; margin:6px 0;"></div>
      <a href="#" title="Log out" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#B4AFA3; text-decoration:none;">
        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 14 V16 H4 V4 H12 V6"></path><path d="M8 10 H17 M14 7 L17 10 L14 13"></path></svg>
      </a>
    </div>
  </aside>

  <!-- ============ MAIN ============ -->
  

<main style="flex:1; min-width:0; overflow-x:hidden;"><div style="max-width:1080px; margin:0 auto; padding:44px 44px 64px; box-sizing:border-box;">
<div style="text-align:center;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Plans</div>
        <div style="font-family:'Newsreader',serif; font-size:34px; letter-spacing:-0.01em; margin-top:6px;">Connect your own data when you're ready</div>
        <div style="font-size:14.5px; color:#57544C; margin-top:8px; max-width:520px; margin-left:auto; margin-right:auto; line-height:1.55;">Any size restaurant can pick any tier. Prices in Ghana Cedis, billed monthly.</div>
      </div>

      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-top:36px; align-items:start;">

        <!-- BASIC -->
        <div style="position:relative; background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:14px; box-shadow:0 1px 2px rgba(27,26,22,0.04), 0 14px 30px rgba(27,26,22,0.05);">
          <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(120px 90px at 24% 30%, #C3DBCB, transparent 70%), radial-gradient(150px 120px at 82% 62%, #D6E2D0, transparent 72%), linear-gradient(135deg,#E9E6DC,#EDEAE0);">
            <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#57544C;">#1</span>
          </div>
          <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Basic</div>
          <div style="padding:44px 12px 4px; text-align:center;">
            <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">890</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
            <div style="font-size:13px; color:#8B877C; margin-top:8px;">This week's buy and forecasts, one location</div>
            <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
          </div>
          <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
          <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
            <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> Weekly buy plan</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> Demand forecasts, all items</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px;"><span style="color:#2E6B4F; font-size:13px;">✓</span> 1-week forecast horizon</div>
          </div>
          <a href="billing.php?plan=Basic" style="text-decoration:none;">
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#FFFFFF; color:#1E4A38; border:1px solid #1E4A38; font-size:14px; font-weight:600; padding:12px; border-radius:9px; cursor:pointer;">Choose Basic</button>
          </a>
        </div>

        <!-- PLUS — anchor -->
        <div style="position:relative; background:#FFFFFF; border:1.5px solid #E9C46A; border-radius:16px; padding:14px; box-shadow:0 2px 4px rgba(27,26,22,0.06), 0 22px 44px rgba(30,74,56,0.14);">
          <span style="position:absolute; top:-11px; left:50%; transform:translateX(-50%); background:#1E4A38; color:#E9C46A; font-size:11px; font-weight:700; letter-spacing:0.04em; padding:5px 14px; border-radius:999px; white-space:nowrap; z-index:3;">Most popular</span>
          <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(130px 100px at 20% 34%, #EED9A0, transparent 70%), radial-gradient(150px 120px at 80% 56%, #CFDDD0, transparent 72%), radial-gradient(90px 80px at 62% 82%, #E7C98B, transparent 70%), linear-gradient(135deg,#F1E9D8,#ECE6D8);">
            <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#8A5A12;">#2</span>
          </div>
          <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Plus</div>
          <div style="padding:44px 12px 4px; text-align:center;">
            <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">1,650</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
            <div style="font-size:13px; color:#8B877C; margin-top:8px;">Everything in Basic, plus alerts &amp; longer horizon</div>
            <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
          </div>
          <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
          <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; color:#8B877C;"><span style="color:#9A9488; font-size:13px;">✓</span> Everything in Basic</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Demand-shift alerts</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> 4-week forecast horizon</div>
          </div>
          <a href="billing.php?plan=Plus" style="text-decoration:none;">
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#1E4A38; color:#F4F1EA; border:none; font-size:14px; font-weight:700; padding:13px; border-radius:9px; cursor:pointer;">Choose Plus</button>
          </a>
        </div>

        <!-- PRO -->
        <div style="position:relative; background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:14px; box-shadow:0 1px 2px rgba(27,26,22,0.04), 0 14px 30px rgba(27,26,22,0.05);">
          <div style="height:150px; border-radius:12px; position:relative; overflow:hidden; background:radial-gradient(130px 100px at 22% 32%, #B4C7BB, transparent 70%), radial-gradient(150px 120px at 80% 62%, #C7D2CA, transparent 72%), radial-gradient(90px 80px at 58% 84%, #A9BFB2, transparent 70%), linear-gradient(135deg,#E4E7E0,#E0E3DC);">
            <span style="position:absolute; top:14px; left:18px; font-family:'IBM Plex Mono',monospace; font-size:12px; font-weight:600; letter-spacing:0.1em; color:#57544C;">#3</span>
          </div>
          <div style="position:absolute; top:122px; left:24px; font-family:'Newsreader',serif; font-size:42px; font-weight:500; letter-spacing:-0.02em; color:#1B1A16; z-index:2;">Pro</div>
          <div style="padding:44px 12px 4px; text-align:center;">
            <div style="display:flex; align-items:baseline; justify-content:center; gap:6px;"><span style="font-size:15px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:1;">3,200</span><span style="font-size:14px; color:#8B877C;">/mo</span></div>
            <div style="font-size:13px; color:#8B877C; margin-top:8px;">Everything in Plus, for multi-location teams</div>
            <div style="display:inline-flex; align-items:center; gap:7px; margin-top:16px; background:#F4F1EA; border:1px solid #E3DED4; border-radius:999px; padding:7px 14px; font-size:12px; font-weight:600; color:#57544C;"><span style="width:5px; height:5px; border-radius:50%; background:#2E8B5D;"></span>Paystack · Mobile money</div>
          </div>
          <div style="height:1px; background:#F0ECE4; margin:18px 6px 16px;"></div>
          <div style="display:flex; flex-direction:column; gap:11px; padding:0 8px;">
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; color:#8B877C;"><span style="color:#9A9488; font-size:13px;">✓</span> Everything in Plus</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Multi-location &amp; team members</div>
            <div style="display:flex; align-items:center; gap:11px; font-size:14px; font-weight:600;"><span style="color:#C0791F; font-size:13px;">✦</span> Automated data ingestion</div>
          </div>
          <a href="billing.php?plan=Pro" style="text-decoration:none;">
            <button style="font-family:inherit; width:100%; margin-top:22px; background:#FFFFFF; color:#1E4A38; border:1px solid #1E4A38; font-size:14px; font-weight:600; padding:12px; border-radius:9px; cursor:pointer;">Choose Pro</button>
          </a>
        </div>

      </div>

    
</div></main>
</div>
</body>
</html>

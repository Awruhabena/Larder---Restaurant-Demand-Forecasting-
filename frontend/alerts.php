<?php
/**
 * alerts.php
 * ----------
 * Static content, no backend connection (see API reference "no backend"
 * list). The weekly-return hook. Links route to the real connected screens
 * (buy-plan.php, forecast-detail.php, dashboard.php).
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Alerts</title>
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
        <a href="alerts.php" style="width:38px; height:38px; border-radius:10px; background:#1E4A38; border:1px solid #1E4A38; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#F6F1E7; position:relative; text-decoration:none;">
          <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8 A4 4 0 0 1 14 8 C14 13 16 14 16 14 H4 C4 14 6 13 6 8 Z"></path><path d="M8.5 16.5 A1.6 1.6 0 0 0 11.5 16.5"></path></svg>
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
      <a href="pricing.php" title="Pricing" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
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
  
<main style="flex:1; min-width:0; overflow-x:hidden;"><div style="max-width:760px; margin:0 auto; padding:44px 24px 64px; box-sizing:border-box;">

  <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Notices</div>
  <div style="font-family:'Newsreader',serif; font-size:34px; margin-top:6px;">Alerts</div>

  <!-- newest, actionable -->
  <a href="buy-plan.php" style="text-decoration:none; color:inherit;">
    <div style="background:#1E4A38; color:#F4F1EA; border-radius:14px; padding:22px 26px; margin-top:24px; display:flex; align-items:center; justify-content:space-between; gap:16px;">
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:38px; height:38px; border-radius:10px; background:rgba(233,196,106,0.18); display:flex; align-items:center; justify-content:center;">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#E9C46A" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="12" height="14" rx="2"></rect><path d="M7.5 10 L9.5 12 L13 7.5"></path></svg>
        </div>
        <div>
          <div style="font-size:15px; font-weight:600;">Your Week 3 buy plan is ready</div>
          <div style="font-size:12.5px; color:#B9C7BE; margin-top:2px;">Jan 16 · GHS 572,960 total spend</div>
        </div>
      </div>
      <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="#F4F1EA" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10 H16 M11 5 L16 10 L11 15"></path></svg>
    </div>
  </a>

  <div style="display:flex; flex-direction:column; margin-top:6px;">
    <a href="forecast-detail.php?item=Chicken+Breast" style="text-decoration:none; color:inherit;">
      <div style="display:flex; align-items:center; gap:14px; padding:16px 4px; border-bottom:1px solid #E3DED4;">
        <div style="width:34px; height:34px; border-radius:10px; background:#E8F1EB; display:flex; align-items:center; justify-content:center;">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="#2E8B5D" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14 L8 9 L11 12 L16 6"></path></svg>
        </div>
        <div>
          <div style="font-size:14px;">Chicken Breast demand is climbing — buy up this week</div>
          <div style="font-size:12px; color:#8B877C; margin-top:2px;">2 days ago</div>
        </div>
      </div>
    </a>

    <a href="forecast-detail.php?item=Pork+Chops" style="text-decoration:none; color:inherit;">
      <div style="display:flex; align-items:center; gap:14px; padding:16px 4px; border-bottom:1px solid #E3DED4;">
        <div style="width:34px; height:34px; border-radius:10px; background:#F4E5E0; display:flex; align-items:center; justify-content:center;">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="#B0472E" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6 L8 11 L11 8 L16 14"></path></svg>
        </div>
        <div>
          <div style="font-size:14px;">Pork Chops demand is easing — buy less to avoid waste</div>
          <div style="font-size:12px; color:#8B877C; margin-top:2px;">2 days ago</div>
        </div>
      </div>
    </a>

    <a href="dashboard.php" style="text-decoration:none; color:inherit;">
      <div style="display:flex; align-items:center; gap:14px; padding:16px 4px; border-bottom:1px solid #E3DED4;">
        <div style="width:34px; height:34px; border-radius:10px; background:#F4F1EA; display:flex; align-items:center; justify-content:center;">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="#57544C" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="12" height="14" rx="2"></rect><line x1="7" y1="7" x2="13" y2="7"></line><line x1="7" y1="10" x2="13" y2="10"></line></svg>
        </div>
        <div>
          <div style="font-size:14px;">Week 2 buy plan is ready</div>
          <div style="font-size:12px; color:#8B877C; margin-top:2px;">9 days ago</div>
        </div>
      </div>
    </a>
  </div>

</div></main>
</div>
</body>
</html>

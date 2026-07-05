<?php
/**
 * register.php
 * ------------
 * Static content + a real session start. No account backend (see API
 * reference "no backend" list) — accepts input and starts the journey.
 * Lands on dashboard.php, which (per the journey rule) shows PREVIEW data
 * until $_SESSION['data_ready'] is set later by processing.php.
 */
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['restaurant_name'] = $_POST['restaurant_name'] ?? 'Your Restaurant';
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Create your account</title>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="larder-tokens.css">
</head>
<body style="font-family:'Hanken Grotesk',sans-serif; color:#1B1A16; margin:0;">

<div style="min-height:100vh; display:grid; grid-template-columns:1.05fr 1fr;">

  <!-- LEFT — brand / value -->
  <div style="background:linear-gradient(180deg, rgba(20,50,38,0.80), rgba(15,36,28,0.90)), url('assets/register-hero.png') center/cover no-repeat, #1E4A38; color:#F4F1EA; padding:44px 56px; display:flex; flex-direction:column; justify-content:space-between;">
    <div style="display:flex; align-items:center; gap:12px;">
      <div style="width:34px; height:34px; border-radius:9px; background:#2E6B4F; display:flex; align-items:center; justify-content:center;"><div style="width:14px; height:14px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
      <span style="font-size:22px; font-weight:600;">Larder</span>
    </div>

    <div style="max-width:460px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#E9C46A;">Demand forecasting for restaurants</div>
      <div style="font-family:'Newsreader',serif; font-size:40px; line-height:1.1; margin-top:16px;">Know what to buy this week — and what it will cost.</div>
      <div style="font-size:15px; color:#B9C7BE; margin-top:16px; line-height:1.6;">Larder forecasts demand across every item on your menu, then turns it into one weekly buy plan. Sign up and explore with sample data — connect your own numbers whenever you're ready.</div>

      <div style="display:flex; gap:32px; margin-top:36px;">
        <div>
          <div style="display:flex; align-items:baseline; gap:5px;"><span style="font-size:15px; color:#8FA69A; font-weight:600;">GHS</span><span style="font-family:'Newsreader',serif; font-size:34px; font-weight:500;">62,263</span></div>
          <div style="font-size:12.5px; color:#8FA69A; margin-top:2px;">saved this week, sample kitchen</div>
        </div>
        <div style="width:1px; background:rgba(255,255,255,0.14);"></div>
        <div>
          <div style="font-family:'Newsreader',serif; font-size:34px; font-weight:500;">75.5<span style="font-size:20px; color:#8FA69A;">%</span></div>
          <div style="font-size:12.5px; color:#8FA69A; margin-top:2px;">forecast accuracy</div>
        </div>
      </div>
    </div>

    <div style="font-size:12.5px; color:#8FA69A;">Built for Ghana · prices in GHS · Paystack &amp; mobile money</div>
  </div>

  <!-- RIGHT — form -->
  <div style="background:#F4F1EA; padding:44px 56px; display:flex; flex-direction:column; justify-content:center;">
    <div style="width:100%; max-width:400px; margin:0 auto;">
      <div style="font-family:'Newsreader',serif; font-size:30px;">Create your account</div>
      <div style="font-size:14px; color:#57544C; margin-top:6px;">Free to start. No card required — payment only when you connect your own data.</div>

      <form method="post" style="display:flex; flex-direction:column; gap:16px; margin-top:28px;">
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Restaurant name</label>
          <input type="text" name="restaurant_name" placeholder="Akwaaba Kitchen" style="font-family:inherit; width:100%; background:#FFFFFF; border:1px solid #D3CCBE; border-radius:9px; padding:12px 14px; font-size:14.5px; box-sizing:border-box;">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Work email</label>
          <input type="email" name="email" placeholder="you@restaurant.com" required style="font-family:inherit; width:100%; background:#FFFFFF; border:1px solid #D3CCBE; border-radius:9px; padding:12px 14px; font-size:14.5px; box-sizing:border-box;">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Password</label>
          <input type="password" name="password" placeholder="At least 8 characters" required style="font-family:inherit; width:100%; background:#FFFFFF; border:1px solid #D3CCBE; border-radius:9px; padding:12px 14px; font-size:14.5px; box-sizing:border-box;">
        </div>

        <button type="submit" style="font-family:inherit; width:100%; background:#1E4A38; color:#F6F1E7; border:none; font-size:15px; font-weight:600; padding:14px; border-radius:10px; cursor:pointer; margin-top:6px;">
          Create account &amp; explore →
        </button>
      </form>

      <div style="text-align:center; font-size:13.5px; color:#57544C; margin-top:16px;">Already have an account? <a href="dashboard.php" style="color:#1E4A38; font-weight:600; text-decoration:none;">Sign in</a></div>

      <div style="display:flex; align-items:center; gap:9px; margin-top:20px; background:#FBF0DE; border:1px solid #EAD9B2; border-radius:9px; padding:10px 13px;">
        <div style="font-size:12.5px; color:#8A5A12; line-height:1.45;">You'll land on your dashboard with sample data first — connect your restaurant when you're ready.</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

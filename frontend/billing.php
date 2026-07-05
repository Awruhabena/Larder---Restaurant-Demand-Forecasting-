<?php
/**
 * billing.php
 * -----------
 * Account and payment screen. Before a subscription is active, shows the
 * selected plan and a payment-entry form; submitting sets
 * $_SESSION['subscribed'] and shows a confirmation before continuing to
 * upload.php. Once subscribed, shows the ongoing account view: current
 * plan, payment method on file, and invoice history.
 */
session_start();

// Two steps: enter payment details, then see a confirmation before
// continuing to upload.php.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {
    $_SESSION['subscribed'] = true;
    $_SESSION['plan'] = $_POST['plan'] ?? 'Plus';
    $_SESSION['payment_phone'] = $_POST['momo_number'] ?? '';
    $_SESSION['payment_just_confirmed'] = true;   // show the confirmation screen next load
    header('Location: billing.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue_to_upload'])) {
    unset($_SESSION['payment_just_confirmed']);
    header('Location: upload.php');
    exit;
}

$showConfirmation = !empty($_SESSION['payment_just_confirmed']);
$alreadySubscribed = !empty($_SESSION['subscribed']);
$chosenPlan = $_GET['plan'] ?? ($_SESSION['plan'] ?? 'Plus');
$planPrices = ['Basic' => '890', 'Plus' => '1,650', 'Pro' => '3,200'];
$planBlurbs = [
    'Basic' => 'Basic — this week\'s buy and forecasts.',
    'Plus' => 'Plus — forecasts, alerts, all handled here.',
    'Pro' => 'Pro — multi-location, for growing teams.',
];
$price = $planPrices[$chosenPlan] ?? '1,650';
$blurb = $planBlurbs[$chosenPlan] ?? $planBlurbs['Plus'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Billing</title>
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

  <aside style="flex-shrink:0; padding:24px 0 24px 22px;">
    <div style="position:sticky; top:90px; display:flex; flex-direction:column; align-items:center; gap:6px; background:#FFFFFF; border:1px solid #E7E1D6; border-radius:20px; padding:12px 10px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
      <a href="dashboard.php" title="Dashboard" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="6" height="6" rx="1.5"></rect><rect x="11" y="3" width="6" height="6" rx="1.5"></rect><rect x="3" y="11" width="6" height="6" rx="1.5"></rect><rect x="11" y="11" width="6" height="6" rx="1.5"></rect></svg>
      </a>
      <a href="buy-plan.php" title="This Week's Buy" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#8B877C; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><rect x="4" y="3" width="12" height="14" rx="2"></rect><line x1="7" y1="7" x2="13" y2="7"></line><line x1="7" y1="10" x2="13" y2="10"></line></svg>
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
      <a href="billing.php" title="Billing" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
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

<main style="flex:1; min-width:0; overflow-x:hidden;">
  <div style="max-width:820px; margin:0 auto; padding:44px 44px 64px;">

    <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Account</div>
    <div style="font-family:'Newsreader',serif; font-size:34px; letter-spacing:-0.01em; margin-top:6px;">Billing</div>

    <!-- current plan -->
    <div style="background:#1E4A38; color:#F4F1EA; border-radius:16px; padding:30px 32px; margin-top:26px; display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap; position:relative; overflow:hidden;">
      <svg width="72" height="72" viewBox="0 0 24 24" style="position:absolute; top:-14px; right:150px; opacity:0.5;" fill="#E9C46A"><path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z"></path></svg>
      <div style="position:relative; z-index:1;">
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8FA69A;"><?php echo $alreadySubscribed ? 'Current plan' : 'Selected plan'; ?></div>
        <div style="font-family:'Newsreader',serif; font-size:32px; letter-spacing:-0.01em; line-height:1.15; margin-top:8px;"><?php echo htmlspecialchars($blurb); ?></div>
        <div style="font-size:13.5px; color:#B9C7BE; margin-top:10px;">
          GHS <?php echo $price; ?> / month
          <?php echo $alreadySubscribed ? ' · next payment Jul 9, 2026' : ' · not yet active'; ?>
        </div>
      </div>

      <?php if ($alreadySubscribed): ?>
        <a href="pricing.php" style="text-decoration:none; position:relative; z-index:1;">
          <button style="font-family:inherit; background:#E9C46A; color:#1E4A38; border:none; font-size:15px; font-weight:700; padding:13px 22px; border-radius:10px; cursor:pointer;">Change plan</button>
        </a>
      <?php else: ?>
        <div style="position:relative; z-index:1; font-size:13px; color:#B9C7BE;">Enter payment details below to activate</div>
      <?php endif; ?>
    </div>

    <?php if ($showConfirmation): ?>
    <!-- payment confirmation -->
    <div style="background:#E8F1EB; border:1px solid #B7D6C4; border-radius:14px; padding:28px 32px; margin-top:16px; text-align:center;">
      <div style="width:52px; height:52px; border-radius:50%; background:#2E8B5D; display:flex; align-items:center; justify-content:center; margin:0 auto;">
        <svg width="24" height="24" viewBox="0 0 20 20" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5 L8 14.5 L16 5.5"></path></svg>
      </div>
      <div style="font-family:'Newsreader',serif; font-size:24px; margin-top:16px;">Payment successful</div>
      <div style="font-size:14px; color:#3D6A52; margin-top:6px;">
        GHS <?php echo $price; ?> charged to <?php echo htmlspecialchars($_SESSION['payment_phone'] ?? 'your mobile money number'); ?> · <?php echo htmlspecialchars($chosenPlan); ?> plan is now active.
      </div>
      <form method="post" style="margin-top:20px;">
        <button type="submit" name="continue_to_upload" value="1" style="font-family:inherit; background:#1E4A38; color:#F6F1E7; border:none; font-size:14px; font-weight:600; padding:12px 24px; border-radius:9px; cursor:pointer;">Continue to connect your data →</button>
      </form>
    </div>
    <?php elseif (!$alreadySubscribed): ?>
    <!-- payment entry -->
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:24px 26px; margin-top:16px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:16px;">Payment details</div>
      <form method="post">
        <input type="hidden" name="plan" value="<?php echo htmlspecialchars($chosenPlan); ?>">
        <div style="display:flex; gap:10px; margin-bottom:16px;">
          <label style="flex:1; display:flex; align-items:center; gap:8px; border:1.5px solid #1E4A38; border-radius:10px; padding:12px 14px; cursor:pointer; background:#F4F9F6;">
            <input type="radio" name="method" checked style="accent-color:#1E4A38;"> <span style="font-size:13.5px; font-weight:600;">MTN Mobile Money</span>
          </label>
          <label style="flex:1; display:flex; align-items:center; gap:8px; border:1px solid #D3CCBE; border-radius:10px; padding:12px 14px; cursor:pointer;">
            <input type="radio" name="method" style="accent-color:#1E4A38;"> <span style="font-size:13.5px;">Card (Paystack)</span>
          </label>
        </div>
        <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Mobile money number</label>
        <input type="tel" name="momo_number" required placeholder="024 XXX XXXX" style="font-family:'IBM Plex Mono',monospace; width:100%; background:#F4F1EA; border:1px solid #D3CCBE; border-radius:9px; padding:12px 14px; font-size:14.5px; box-sizing:border-box; margin-bottom:18px;">
        <div style="font-size:12px; color:#8B877C; margin-bottom:18px;">Simulated for this demo — no real charge is made, via Paystack in production.</div>
        <button type="submit" name="submit_payment" value="1" style="font-family:inherit; width:100%; background:#1E4A38; color:#F6F1E7; border:none; font-size:15px; font-weight:600; padding:13px; border-radius:10px; cursor:pointer;">Pay GHS <?php echo $price; ?> &amp; subscribe</button>
      </form>
    </div>
    <?php endif; ?>

    <?php if ($alreadySubscribed && !$showConfirmation): ?>
    <!-- payment method (shown once subscribed) -->
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Payment method</div>
      <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-top:14px; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:42px; height:30px; border-radius:6px; background:#F4F1EA; border:1px solid #E3DED4; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#57544C;">MTN</div>
          <div>
            <div style="font-size:14px; font-weight:600;">MTN Mobile Money</div>
            <div style="font-size:12.5px; color:#8B877C; margin-top:2px;"><?php echo htmlspecialchars($_SESSION['payment_phone'] ?? '•••• •• ••••'); ?> · via Paystack (simulated for this demo)</div>
          </div>
        </div>
        <button style="font-family:inherit; background:#FFFFFF; color:#1B1A16; border:1px solid #D3CCBE; font-size:13px; font-weight:600; padding:9px 16px; border-radius:8px; cursor:pointer;">Update</button>
      </div>
    </div>
    <?php endif; ?>

    <!-- invoice history -->
    <?php if (!$showConfirmation): ?>
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:14px;">Recent invoices</div>
      <?php if ($alreadySubscribed): ?>
      <div style="display:flex; flex-direction:column;">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid #F0ECE4;">
          <span style="font-size:13.5px;">Jun 9, 2026</span>
          <span style="font-family:'IBM Plex Mono',monospace; font-size:13.5px; color:#57544C;">GHS <?php echo $price; ?>.00</span>
          <span style="font-size:12px; color:#2E8B5D; font-weight:600;">Paid</span>
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid #F0ECE4;">
          <span style="font-size:13.5px;">May 9, 2026</span>
          <span style="font-family:'IBM Plex Mono',monospace; font-size:13.5px; color:#57544C;">GHS <?php echo $price; ?>.00</span>
          <span style="font-size:12px; color:#2E8B5D; font-weight:600;">Paid</span>
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0;">
          <span style="font-size:13.5px;">Apr 9, 2026</span>
          <span style="font-family:'IBM Plex Mono',monospace; font-size:13.5px; color:#57544C;">GHS <?php echo $price; ?>.00</span>
          <span style="font-size:12px; color:#2E8B5D; font-weight:600;">Paid</span>
        </div>
      </div>
      <?php else: ?>
        <div style="font-size:13.5px; color:#8B877C;">No invoices yet — your first one appears here after you subscribe.</div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</main>
</div>
</body>
</html>

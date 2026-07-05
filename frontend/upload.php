<?php
/**
 * upload.php
 * ----------
 * One screen, gated by subscription status ($_SESSION['subscribed']).
 * If not subscribed, redirects to the locked view (upload-locked.php).
 *
 * On POST, forwards the file to the /upload endpoint. This triggers two
 * real things on the backend: the Gemini schema mapping (always live),
 * and a genuine recompute job — the uploaded rows are merged into the
 * real order history and the real forecasting engine re-runs across all
 * items (roughly 1-3 minutes). We store the returned job_id in the session; the
 * next screen (processing.php) polls it for real completion, and once
 * done, dashboard.php shows mode=live — the actual recomputed forecast,
 * not the static demo snapshot.
 */
session_start();

if (empty($_SESSION['subscribed'])) {
    header('Location: upload-locked.php');
    exit;
}

$apiBase = 'http://localhost:8000';
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['datafile']) && $_FILES['datafile']['error'] === UPLOAD_ERR_OK) {
    $ch = curl_init("{$apiBase}/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($_FILES['datafile']['tmp_name'], 'text/csv', $_FILES['datafile']['name']),
    ]);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        $error = "Could not reach the Larder API at {$apiBase}. Is `python runner.py` running? ({$curlError})";
    } else {
        $result = json_decode($response, true);
        $_SESSION['just_uploaded'] = true;
        $_SESSION['upload_filename'] = $_FILES['datafile']['name'];
        // Real recompute job kicked off by /upload. Stored so
        // processing.php can poll it for real completion, and so
        // dashboard.php/buy-plan.php/forecast-detail.php can request
        // mode=live once it's done.
        $_SESSION['recompute_job_id'] = $result['job_id'] ?? null;
        $_SESSION['live_job_ready'] = false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Upload your restaurant's data</title>
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
        <a href="upload.php" style="padding:9px 18px; border-radius:9px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; text-decoration:none;">Upload Data</a>
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
      <a href="upload.php" title="Upload Data" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
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
  
<main style="flex:1; min-width:0; overflow-x:hidden;"><div style="max-width:760px; margin:0 auto; padding:40px 24px; box-sizing:border-box;">
  <div style="font-family:'Newsreader',serif; font-size:34px;">Upload your restaurant's data</div>
  <div style="font-size:14.5px; color:#57544C; margin-top:8px; max-width:560px; line-height:1.55;">
    Bring your own sales history and Larder will forecast against it instead of sample data.
    CSV or spreadsheet export from your POS works fine — we'll match the columns for you.
  </div>

  <?php if ($error): ?>
    <div style="margin-top:22px; background:#F4E5E0; color:#B0472E; padding:14px 18px; border-radius:10px; font-size:14px;">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <?php if (!$result): ?>
    <!-- BEFORE: real dropzone / file input -->
    <form method="post" enctype="multipart/form-data">
      <label for="datafile" style="cursor:pointer; margin-top:22px; background:#FFFFFF; border:2px dashed #C9C1B2; border-radius:16px; padding:64px 32px; display:flex; flex-direction:column; align-items:center; gap:14px; text-align:center;">
        <div style="width:56px; height:56px; border-radius:16px; background:#F4F1EA; display:flex; align-items:center; justify-content:center;">
          <svg width="26" height="26" viewBox="0 0 20 20" fill="none" stroke="#57544C" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13 V4"></path><path d="M6.5 7.5 L10 4 L13.5 7.5"></path><path d="M4 13.5 V16 H16 V13.5"></path></svg>
        </div>
        <div style="font-family:'Newsreader',serif; font-size:19px;">Drag your sales export here</div>
        <div style="font-size:13px; color:#8B877C;">or click to browse · .csv, .xlsx up to 20MB</div>
        <input id="datafile" name="datafile" type="file" accept=".csv,.xlsx" style="display:none;" onchange="this.form.submit()">
        <span style="margin-top:6px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; padding:11px 22px; border-radius:9px;">Choose file</span>
      </label>
    </form>
    <div style="display:flex; align-items:center; gap:9px; margin-top:16px; background:#FBF9F4; border:1px solid #E3DED4; border-radius:10px; padding:11px 15px;">
      <div style="font-size:12.5px; color:#57544C; line-height:1.45;">We only need item name, date, and quantity sold — any extra columns are ignored automatically.</div>
    </div>

  <?php else: ?>
    <!-- AFTER: the real integrity report from /upload -->
    <div style="margin-top:22px; background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:26px 30px;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:34px; height:34px; border-radius:9px; background:#E8F1EB; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="#2E8B5D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5 L8 14.5 L16 5.5"></path></svg>
        </div>
        <div>
          <div style="font-family:'Newsreader',serif; font-size:19px;"><?php echo htmlspecialchars($_SESSION['upload_filename']); ?> matched</div>
          <div style="font-size:12.5px; color:#8B877C; margin-top:2px;">
            <?php echo $result['integrity']['rows_kept']; ?> of <?php echo $result['integrity']['original_rows']; ?> rows matched ·
            quantities verified · <?php echo $result['integrity']['rows_dropped_unmatched_item']; ?> skipped
          </div>
          <div style="font-size:11.5px; color:<?php echo $result['gemini_live'] ? '#2E8B5D' : '#8B877C'; ?>; margin-top:4px;">
            <?php echo $result['gemini_live'] ? '● Live Gemini mapping' : '○ Deterministic fallback used (Gemini unavailable — result is still correct)'; ?>
          </div>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-top:22px; padding-top:20px; border-top:1px solid #F0ECE4;">
        <div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:10px;">Your columns</div>
          <div style="font-size:13px; color:#57544C;"><?php echo htmlspecialchars(implode(', ', $result['raw_columns'])); ?></div>
        </div>
        <div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:10px;">Cleaned preview</div>
          <div style="font-size:13px; color:#57544C;">
            <?php foreach (array_slice($result['clean_preview'], 0, 4) as $row): ?>
              <?php echo htmlspecialchars($row['item']); ?> — <?php echo $row['count']; ?><br>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <a href="processing.php" style="display:inline-block; margin-top:24px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; padding:11px 22px; border-radius:9px; text-decoration:none;">Continue →</a>
    </div>
  <?php endif; ?>

</div></main>
</div>
</body>
</html>

<?php
/**
 * buy-plan.php
 * ------------
 * ONE screen, THREE states, same rule as dashboard.php: 'live' once a
 * genuine recompute for this user's own upload has finished (confirmed by
 * processing.php's real polling), 'real' once paid + uploaded, otherwise
 * 'preview' regardless of any ?mode= typed in the URL, except an explicit
 * ?mode=preview override.
 *
 * Requires the Python API running separately: `python runner.py` (port 8000).
 */
session_start();

$journeyHasRealData = !empty($_SESSION['data_ready']);
$liveReady = !empty($_SESSION['live_job_ready']) && !empty($_SESSION['recompute_job_id']);
$jobId = $_SESSION['recompute_job_id'] ?? null;
$requestedMode = $_GET['mode'] ?? null;

if ($requestedMode === 'preview') {
    $mode = 'preview';
} elseif ($liveReady) {
    $mode = 'live';
} elseif ($journeyHasRealData) {
    $mode = 'real';
} else {
    $mode = 'preview';
}

$apiBase = 'http://localhost:8000';
$modeQuery = "mode={$mode}" . ($mode === 'live' ? '&job_id=' . urlencode($jobId) : '');

// Fetch the week list, then every week's rows in one pass (small dataset;
// simplest way to give the JS week-tabs instant switching without reloads).
$weeksJson = @file_get_contents("{$apiBase}/weeks?{$modeQuery}");
if ($weeksJson === false) {
    die('Could not reach the Larder API at ' . $apiBase . '. Is `python runner.py` running?');
}
$weeks = json_decode($weeksJson, true)['weeks'];

$headlineJson = @file_get_contents("{$apiBase}/headline?{$modeQuery}");
$headline = json_decode($headlineJson, true);

$weekData = [];
foreach ($weeks as $wk) {
    $rowsJson = @file_get_contents("{$apiBase}/buy-plan?week=" . urlencode($wk) . "&{$modeQuery}");
    $rows = json_decode($rowsJson, true)['rows'] ?? [];
    // sort by spend, biggest first — matches the original design's ordering
    usort($rows, fn($a, $b) => $b['buy_cost_ghs'] <=> $a['buy_cost_ghs']);
    $weekData[$wk] = $rows;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Larder — This Week's Buy</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="larder-tokens.css">
</head>
<body style="font-family:'Hanken Grotesk',sans-serif; color:#1B1A16; margin:0; background:#F4F1EA;">

<div style="display:flex; flex-direction:column; min-height:100vh;">

  <!-- ============ TOP BAR ============ -->
  <header style="position:sticky; top:0; z-index:30; background:#FBFAF6; border-bottom:1px solid #E7E1D6;">
    <div style="max-width:1560px; margin:0 auto; padding:16px 34px; display:flex; align-items:center; justify-content:space-between; gap:28px;">
      <div style="display:flex; align-items:center; gap:11px; flex:1;">
        <div style="width:32px; height:32px; border-radius:9px; background:#1E4A38; display:flex; align-items:center; justify-content:center;"><div style="width:13px; height:13px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
        <span style="font-size:19px; font-weight:600; letter-spacing:-0.01em;">Larder</span>
      </div>
      <nav style="display:flex; align-items:center; gap:2px; background:#F1ECE1; padding:5px; border-radius:12px;">
        <a href="dashboard.php?mode=<?php echo $mode; ?>" style="padding:9px 18px; border-radius:9px; color:#57544C; font-size:14px; font-weight:500; text-decoration:none;">Dashboard</a>
        <a href="buy-plan.php?mode=<?php echo $mode; ?>" style="padding:9px 18px; border-radius:9px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; text-decoration:none;">This Week's Buy</a>
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
      <a href="buy-plan.php" title="This Week's Buy" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
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
  

<main style="flex:1; min-width:0; overflow-x:hidden;"><div style="max-width:1560px; margin:0 auto; padding:28px 34px; width:100%; box-sizing:border-box; box-sizing:border-box;">

    <!-- HEADER ROW: title + week tabs + filter -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; flex-wrap:wrap; gap:14px;">
      <div>
        <h1 style="font-family:'Newsreader',serif; font-size:28px; font-weight:500; margin:0;">This Week's Buy</h1>
        <div id="weekSubtitle" style="font-size:14px; color:#8B877C; margin-top:4px;"></div>
      </div>
      <div style="display:flex; align-items:center; gap:10px;">
        <div id="weekTabs" style="display:flex; gap:4px; background:#EFEBE2; padding:4px; border-radius:10px;"></div>
        <button id="filterBtn" style="font-family:inherit; font-size:13px; font-weight:600; padding:9px 16px; border-radius:10px; border:1px solid #D3CCBE; background:#FFFFFF; color:#57544C; cursor:pointer;">
          <span id="filterCheck" style="color:#E9C46A;"></span> Only changed
        </button>
      </div>
    </div>
    <div id="filterHint" style="font-size:12.5px; color:#8B877C; margin-bottom:14px;"></div>

    <!-- TABLE -->
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; overflow:hidden;">
      <div style="display:grid; grid-template-columns:2fr 1fr 1.15fr 1fr 0.85fr 0.95fr; padding:13px 24px; background:#FBF9F4; border-bottom:1px solid #E3DED4; font-size:11px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:#8B877C;">
        <div>Item</div><div style="text-align:right;">Buy units</div><div style="text-align:right;">Cost (GHS)</div>
        <div style="text-align:center;">Change</div><div style="text-align:right;">Forecast</div><div style="text-align:right;">Unit cost</div>
      </div>
      <div id="tableBody"></div>
      <div id="emptyState" style="display:none; padding:40px 24px; text-align:center;">
        <div style="font-family:'Newsreader',serif; font-size:20px;">Nothing moved this week.</div>
        <div style="font-size:14px; color:#8B877C; margin-top:6px;">Every item is steady — buy to plan and move on.</div>
      </div>
      <div style="display:grid; grid-template-columns:2fr 1fr 1.15fr 1fr 0.85fr 0.95fr; padding:16px 24px; align-items:center; background:#FBF9F4;">
        <div id="footerLabel" style="font-weight:700; font-size:14px;"></div>
        <div id="footerUnits" style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:16px; font-weight:600;"></div>
        <div id="footerCost" style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:16px; font-weight:700;"></div>
        <div></div><div></div><div></div>
      </div>
    </div>

    <div style="font-size:12.5px; color:#8B877C; margin-top:14px;">
      Click any item to open its forecast detail. Change vs last week uses a ±20% threshold — smaller moves read as steady on purpose.
    </div>
  </div></main>
</div>

<script>
// All weeks' rows, delivered server-side by PHP — no extra API calls needed
// when switching tabs or toggling the filter; both happen instantly client-side.
const WEEK_DATA = <?php echo json_encode($weekData); ?>;
const WEEKS = <?php echo json_encode($weeks); ?>;

let currentWeek = 0;
let onlyChanged = false;

function fmt(n) { return Math.round(n).toLocaleString('en-US'); }

function pillHtml(row) {
  if (row.change === 'up')   return `<span style="display:inline-flex;align-items:center;gap:5px;background:#E8F1EB;color:#2E8B5D;font-size:12px;font-weight:600;padding:4px 9px;border-radius:999px;">▲ ${Math.abs(row.change_pct)}%</span>`;
  if (row.change === 'down') return `<span style="display:inline-flex;align-items:center;gap:5px;background:#F4E5E0;color:#B0472E;font-size:12px;font-weight:600;padding:4px 9px;border-radius:999px;">▼ ${Math.abs(row.change_pct)}%</span>`;
  return `<span style="color:#B4AFA3;font-size:13px;">—</span>`;
}

function rowHtml(row) {
  const dot = row.change === 'up' ? '#2E8B5D' : (row.change === 'down' ? '#B0472E' : '#DAD4C7');
  return `<a href="forecast-detail.php?item=${encodeURIComponent(row.item)}" style="display:grid; grid-template-columns:2fr 1fr 1.15fr 1fr 0.85fr 0.95fr; padding:15px 24px; align-items:center; border-bottom:1px solid #F0ECE4; font-variant-numeric:tabular-nums; text-decoration:none; color:inherit;">
    <div style="display:flex; align-items:center; gap:11px;">
      <span style="width:8px; height:8px; border-radius:2px; background:${dot}; flex-shrink:0;"></span>
      <span style="font-weight:600; font-size:15px;">${row.item}</span>
    </div>
    <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:700; color:#1E4A38;">${row.buy_units}</div>
    <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:15px; font-weight:600;">${fmt(row.buy_cost_ghs)}</div>
    <div style="text-align:center;">${pillHtml(row)}</div>
    <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:13px; color:#8B877C;">${row.forecast_units}</div>
    <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-size:13px; color:#8B877C;">${row.unit_cost.toFixed(2)}</div>
  </a>`;
}

function render() {
  const rows = WEEK_DATA[WEEKS[currentWeek]];
  const changed = rows.filter(r => r.change !== 'steady');
  const shown = onlyChanged ? changed : rows;

  document.getElementById('weekSubtitle').textContent =
    `Week of ${WEEKS[currentWeek]} · ${rows.length} items`;

  // week tabs
  document.getElementById('weekTabs').innerHTML = WEEKS.map((w, i) => `
    <button onclick="setWeek(${i})" style="font-family:inherit; font-size:13px; font-weight:600; padding:8px 14px; border-radius:8px; border:none; cursor:pointer;
      background:${i === currentWeek ? '#1E4A38' : 'transparent'}; color:${i === currentWeek ? '#F6F1E7' : '#57544C'};">${w}</button>
  `).join('');

  // filter button state
  document.getElementById('filterBtn').style.background = onlyChanged ? '#1E4A38' : '#FFFFFF';
  document.getElementById('filterBtn').style.color = onlyChanged ? '#F6F1E7' : '#57544C';
  document.getElementById('filterCheck').textContent = onlyChanged ? '✓' : '';
  document.getElementById('filterHint').textContent = onlyChanged
    ? `Showing ${changed.length} items that moved past ±20%`
    : `${changed.length} of ${rows.length} items moved past ±20% this week`;

  // table body
  document.getElementById('emptyState').style.display = (onlyChanged && shown.length === 0) ? 'block' : 'none';
  document.getElementById('tableBody').innerHTML = shown.map(rowHtml).join('');

  // footer totals (always reflect the FULL week, not just the filtered view)
  const totalUnits = rows.reduce((s, r) => s + r.buy_units, 0);
  const totalCost = rows.reduce((s, r) => s + r.buy_cost_ghs, 0);
  document.getElementById('footerLabel').textContent = `Week total · ${rows.length} items`;
  document.getElementById('footerUnits').textContent = fmt(totalUnits);
  document.getElementById('footerCost').textContent = 'GHS ' + fmt(totalCost);
}

function setWeek(i) { currentWeek = i; render(); }
document.getElementById('filterBtn').onclick = () => { onlyChanged = !onlyChanged; render(); };

render();
</script>

</div>
</body>
</html>

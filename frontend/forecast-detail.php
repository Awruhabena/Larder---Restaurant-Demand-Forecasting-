<?php
/**
 * forecast-detail.php
 * --------------------
 * The forecast chart screen. A solid line shows history, a dashed line
 * shows the forecast, with a "Now" divider between them. No confidence
 * band: the solid/dashed distinction alone communicates known vs
 * predicted, which is sufficient without also modelling uncertainty bounds.
 *
 * /hero currently returns full chart data for one item only (Chicken
 * Breast). This page calls /hero regardless of which item was clicked,
 * and clearly labels the result as the Chicken Breast series if a
 * different item was requested, rather than silently mislabeling the chart.
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
} elseif ($journeyHasRealData || $requestedMode === 'real') {
    $mode = 'real';
} else {
    $mode = 'preview';
}

$requestedItem = $_GET['item'] ?? null;
$apiBase = 'http://localhost:8000';
$modeQuery = "mode={$mode}" . ($mode === 'live' ? '&job_id=' . urlencode($jobId) : '');

$json = @file_get_contents("{$apiBase}/hero?{$modeQuery}");
if ($json === false) {
    die('Could not reach the Larder API at ' . $apiBase . '. Is `python runner.py` running?');
}
$hero = json_decode($json, true);

$isKnownGap = $requestedItem !== null && $requestedItem !== $hero['name'];

$history = array_map(fn($h) => $h['units'], $hero['history']);
$forecast = array_map(fn($f) => $f['units'], $hero['forecast']);
$all = array_merge($history, $forecast);
$max = max($all);
$min = min($all);
$pad = ($max - $min) * 0.25 ?: ($max * 0.2 ?: 5);
$top = $max + $pad;
$bottom = max(0, $min - $pad);

$chartLeft = 64; $chartRight = 868; $chartTop = 60; $chartBottom = 292;
$totalPoints = count($all);
$stepX = ($chartRight - $chartLeft) / ($totalPoints - 1);
$yFor = fn($v) => $chartBottom - (($v - $bottom) / ($top - $bottom)) * ($chartBottom - $chartTop);
$xFor = fn($i) => $chartLeft + $i * $stepX;

$actualPoints = implode(' ', array_map(fn($v, $i) => number_format($xFor($i), 1) . ',' . number_format($yFor($v), 1), $history, array_keys($history)));

$histLen = count($history);
$fcXY = array_merge([[$histLen - 1, $history[$histLen - 1]]],
                     array_map(fn($v, $i) => [$histLen + $i, $v], $forecast, array_keys($forecast)));
$forecastPoints = implode(' ', array_map(fn($p) => number_format($xFor($p[0]), 1) . ',' . number_format($yFor($p[1]), 1), $fcXY));

$nowX = number_format($xFor($histLen - 1), 1);
$nowY = number_format($yFor($history[$histLen - 1]), 1);
$nowLabelX = number_format((float)$nowX - 43, 1);

$actualDots = [];
foreach ($history as $i => $v) {
    if ($i % 4 === 0) $actualDots[] = ['x' => $xFor($i), 'y' => $yFor($v)];
}

// Month tick marks along the x-axis (real week dates from the history array)
$historyDates = array_map(fn($h) => $h['week'], $hero['history']);
$monthTicks = [];
$lastMonth = null;
foreach ($historyDates as $i => $dateStr) {
    $m = date('M', strtotime($dateStr));
    if ($m !== $lastMonth) {
        $monthTicks[] = ['x' => $xFor($i), 'label' => $m];
        $lastMonth = $m;
    }
}

$axisTop = round($top); $axisMid = round(($top + $bottom) / 2); $axisBottom = round($bottom);

// Fetch this item's real buy-plan row (units, cost) for the supporting stats row below the chart.
$weeksJson = @file_get_contents("{$apiBase}/weeks?{$modeQuery}");
$firstWeek = json_decode($weeksJson, true)['weeks'][0] ?? null;
$buyRow = null;
if ($firstWeek) {
    $bpJson = @file_get_contents("{$apiBase}/buy-plan?week=" . urlencode($firstWeek) . "&{$modeQuery}");
    foreach (json_decode($bpJson, true)['rows'] ?? [] as $r) {
        if ($r['item'] === $hero['name']) { $buyRow = $r; break; }
    }
}
$changeLabel = $buyRow['change'] ?? 'steady';
$readout = $changeLabel === 'up'
    ? "Demand is climbing — consider buying a bit more this week."
    : ($changeLabel === 'down'
        ? "Demand is easing off — buy less to avoid waste."
        : "Demand is steady — buy to the usual plan.");
$sourceLabel = $isKnownGap ? 'Example item' : 'Real forecast';
$sourceBg = $isKnownGap ? '#F4E5E0' : '#E8F1EB';
$sourceColor = $isKnownGap ? '#B0472E' : '#2E8B5D';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Larder — Forecast Detail</title>
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
        <a href="forecast-detail.php" style="padding:9px 18px; border-radius:9px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; text-decoration:none;">Forecasts</a>
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
      <a href="forecast-detail.php" title="Forecasts" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
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

  <!-- ============ MAIN ============ --><main style="flex:1; min-width:0; overflow-x:hidden;">
  <div style="max-width:1180px; margin:0 auto; padding:44px 44px 64px;">

    <!-- breadcrumb -->
    <a href="buy-plan.php?mode=<?php echo $mode; ?>" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:#57544C; font-size:13.5px; font-weight:600; margin-bottom:20px;">
      <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4 L6 10 L12 16"></path></svg>
      Back to This Week's Buy
    </a>

    <!-- title -->
    <div style="display:flex; align-items:center; justify-content:space-between; gap:20px; margin-bottom:28px; flex-wrap:wrap;">
      <div>
        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Forecast detail</div>
        <div style="font-family:'Newsreader',serif; font-size:34px; letter-spacing:-0.01em; margin-top:6px;"><?php echo htmlspecialchars($hero['name']); ?></div>
      </div>
    </div>

    <!-- HERO CHART -->
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:16px; padding:28px 32px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px; flex-wrap:wrap;">
        <div>
          <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Weekly demand · history &amp; forecast</div>
            <span style="font-size:11px; font-weight:600; padding:3px 9px; border-radius:999px; background:<?php echo $sourceBg; ?>; color:<?php echo $sourceColor; ?>;"><?php echo $sourceLabel; ?></span>
          </div>
          <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:8px; max-width:520px; line-height:1.3;"><?php echo $readout; ?></div>
        </div>
        <span style="display:inline-flex; align-items:center; gap:6px; background:#F4F1EA; color:#57544C; border:1px solid #E3DED4; font-size:12.5px; font-weight:600; padding:6px 12px; border-radius:999px; flex-shrink:0;">ARIMA<?php echo htmlspecialchars($hero['arima_order']); ?></span>
      </div>

      <div style="position:relative; margin-top:22px;">
        <svg viewBox="0 0 900 340" style="width:100%; height:auto; display:block;">
          <line x1="64" y1="60" x2="868" y2="60" stroke="#EFEBE2" stroke-width="1"></line>
          <line x1="64" y1="118" x2="868" y2="118" stroke="#EFEBE2" stroke-width="1"></line>
          <line x1="64" y1="176" x2="868" y2="176" stroke="#EFEBE2" stroke-width="1"></line>
          <line x1="64" y1="234" x2="868" y2="234" stroke="#EFEBE2" stroke-width="1"></line>
          <line x1="64" y1="292" x2="868" y2="292" stroke="#E3DED4" stroke-width="1"></line>
          <text x="54" y="64" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="12"><?php echo $axisTop; ?></text>
          <text x="54" y="180" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="12"><?php echo $axisMid; ?></text>
          <text x="54" y="296" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="12"><?php echo $axisBottom; ?></text>

          <polyline points="<?php echo $actualPoints; ?>" fill="none" stroke="#57544C" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"></polyline>
          <polyline points="<?php echo $forecastPoints; ?>" fill="none" stroke="#C0791F" stroke-width="2.5" stroke-dasharray="1.5 7" stroke-linecap="round" stroke-linejoin="round"></polyline>

          <?php foreach ($actualDots as $d): ?>
            <circle cx="<?php echo $d['x']; ?>" cy="<?php echo $d['y']; ?>" r="2.75" fill="#EDE9E1" stroke="#57544C" stroke-width="1.4"></circle>
          <?php endforeach; ?>

          <line x1="<?php echo $nowX; ?>" y1="26" x2="<?php echo $nowX; ?>" y2="292" stroke="#8B877C" stroke-width="1" stroke-dasharray="4 4"></line>
          <circle cx="<?php echo $nowX; ?>" cy="<?php echo $nowY; ?>" r="4.5" fill="#57544C"></circle>
          <rect x="<?php echo $nowLabelX; ?>" y="30" width="86" height="20" rx="4" fill="#57544C"></rect>
          <text x="<?php echo $nowX; ?>" y="44" text-anchor="middle" fill="#F4F1EA" font-family="IBM Plex Mono, monospace" font-size="11">NOW · WK <?php echo $histLen; ?></text>

          <?php foreach ($monthTicks as $mt): ?>
          <line x1="<?php echo $mt['x']; ?>" y1="292" x2="<?php echo $mt['x']; ?>" y2="297" stroke="#D3CCBE" stroke-width="1"></line>
          <text x="<?php echo $mt['x']; ?>" y="313" text-anchor="middle" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="10.5"><?php echo $mt['label']; ?></text>
          <?php endforeach; ?>
        </svg>
      </div>

      <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-top:18px; padding-top:18px; border-top:1px solid #F0ECE4; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:22px;">
          <div style="display:flex; align-items:center; gap:8px;"><svg width="30" height="10"><line x1="1" y1="5" x2="29" y2="5" stroke="#57544C" stroke-width="2.5"></line></svg><span style="font-size:12.5px; color:#57544C;">Actual — known, solid line</span></div>
          <div style="display:flex; align-items:center; gap:8px;"><svg width="30" height="10"><line x1="1" y1="5" x2="29" y2="5" stroke="#C0791F" stroke-width="2.25" stroke-dasharray="1.5 5"></line></svg><span style="font-size:12.5px; color:#57544C;">Forecast — predicted, dashed line</span></div>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="width:26px; height:26px; border-radius:8px; background:#FBF9F4; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 16 16"><circle cx="8" cy="8" r="5.4" fill="none" stroke="#C0791F" stroke-width="1.4"></circle><circle cx="8" cy="8" r="1.6" fill="#C0791F"></circle></svg></span>
          <span style="font-size:12.5px; color:#57544C;"><?php echo $hero['accuracy_pct']; ?>% accurate for this item</span>
        </div>
      </div>
    </div>

    <!-- SUPPORTING ROW: real data from the buy plan -->
    <?php if ($buyRow): ?>
    <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">This week's buy</div>
      <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-top:14px;">
        <div>
          <div style="font-size:12px; color:#8B877C;">Buy units</div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:24px; font-weight:700; color:#1E4A38; margin-top:4px;"><?php echo $buyRow['buy_units']; ?></div>
        </div>
        <div>
          <div style="font-size:12px; color:#8B877C;">Total cost</div>
          <div style="display:flex; align-items:baseline; gap:5px; margin-top:4px;"><span style="font-size:12px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:700;"><?php echo number_format($buyRow['buy_cost_ghs']); ?></span></div>
        </div>
        <div>
          <div style="font-size:12px; color:#8B877C;">Forecast units</div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:600; color:#57544C; margin-top:4px;"><?php echo $buyRow['forecast_units']; ?></div>
        </div>
        <div>
          <div style="font-size:12px; color:#8B877C;">Unit cost <span style="font-size:10.5px; color:#B4AFA3;">(at your prices)</span></div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:600; color:#57544C; margin-top:4px;"><?php echo number_format($buyRow['unit_cost'], 2); ?></div>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</main>
</div>
</body>
</html>

<?php
/**
 * processing.php
 * ---------------
 * The transition between upload and the connected dashboard.
 *
 * This used to show a fixed 4.2-second fake progress animation regardless
 * of whether anything was actually computing. It now polls the real
 * recompute job (job_id from upload.php) via /recompute-status and only
 * proceeds once the real engine has actually finished — a full recompute
 * across all items takes roughly 1-3 minutes, so the wait here is real, not staged.
 *
 * If there's no job_id (e.g. the recompute failed to start), this falls
 * back gracefully to the verified pre-computed snapshot (mode=real)
 * rather than blocking the journey — an honest degrade, not a silent one
 * (the dashboard still shows real, verified numbers; it just won't be the
 * caller's own freshly-recomputed data).
 *
 * Once the job is confirmed done, $_SESSION['live_job_ready'] is set,
 * which is what lets dashboard.php / buy-plan.php / forecast-detail.php
 * request mode=live&job_id=... instead of mode=real.
 */
session_start();

if (empty($_SESSION['subscribed']) || empty($_SESSION['just_uploaded'])) {
    header('Location: ' . (empty($_SESSION['subscribed']) ? 'upload-locked.php' : 'upload.php'));
    exit;
}

$_SESSION['data_ready'] = true;
$jobId = $_SESSION['recompute_job_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Setting up your dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body style="margin:0;">

<div style="font-family:'Hanken Grotesk',sans-serif; min-height:100vh; background:#1E4A38; display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative;">

  <div style="position:absolute; top:34px; left:40px; display:flex; align-items:center; gap:11px;">
    <div style="width:32px; height:32px; border-radius:9px; background:#173B2C; display:flex; align-items:center; justify-content:center;"><div style="width:13px; height:13px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
    <span style="font-size:19px; font-weight:600; color:#F6F1E7;">Larder</span>
  </div>

  <div style="display:flex; flex-direction:column; align-items:center; gap:28px;">
    <div style="width:64px; height:64px; border-radius:50%; border:3px solid rgba(246,241,231,0.18); border-top-color:#E9C46A; animation:spin 1.1s linear infinite;"></div>

    <div id="msg" style="font-family:'Newsreader',serif; font-size:22px; color:#F6F1E7; text-align:center; max-width:420px;">Merging your data into your order history…</div>

    <div style="width:280px; height:5px; background:rgba(246,241,231,0.16); border-radius:99px; overflow:hidden;">
      <div id="bar" style="height:100%; background:#E9C46A; border-radius:99px; width:8%; transition:width 0.6s ease;"></div>
    </div>

    <div id="sub" style="font-family:'IBM Plex Mono',monospace; font-size:11.5px; letter-spacing:0.08em; text-transform:uppercase; color:#8FA69A;">Forecasting demand across your menu — real ARIMA fit, roughly 1-3 minutes</div>
  </div>
</div>

<script>
const jobId = <?php echo json_encode($jobId); ?>;
const msgEl = document.getElementById('msg');
const barEl = document.getElementById('bar');
const subEl = document.getElementById('sub');

const messages = [
  'Merging your data into your order history…',
  'Fitting a forecast model per menu item…',
  'Building your buy plan from the recomputed forecast…',
];
let step = 0;
const cycleMsg = setInterval(() => {
  step = (step + 1) % messages.length;
  msgEl.textContent = messages[step];
}, 3500);

function proceed(live) {
  clearInterval(cycleMsg);
  // Tell the server the job's outcome via a tiny same-origin request,
  // then move on to the dashboard.
  fetch('mark-live-ready.php?live=' + (live ? '1' : '0') + '&job_id=' + encodeURIComponent(jobId || ''))
    .finally(() => { window.location.href = 'dashboard.php'; });
}

if (!jobId) {
  // No job to wait for (recompute couldn't be started) — proceed on the
  // verified pre-computed snapshot rather than hang here forever.
  subEl.textContent = 'Using verified snapshot data';
  barEl.style.width = '100%';
  setTimeout(() => proceed(false), 1200);
} else {
  const apiBase = 'http://localhost:8000';
  const poll = () => {
    fetch(`${apiBase}/recompute-status/${jobId}`)
      .then(r => r.json())
      .then(data => {
        // Real elapsed time drives the bar — capped visually near 95%
        // until it's actually done, so the bar never lies about status.
        const pct = Math.min(92, 8 + data.elapsed_seconds * 0.8);
        barEl.style.width = pct + '%';
        subEl.textContent = `Real recompute running — ${Math.round(data.elapsed_seconds)}s elapsed`;

        if (data.status === 'done') {
          barEl.style.width = '100%';
          msgEl.textContent = 'Your forecast is ready.';
          subEl.textContent = 'Recompute complete';
          setTimeout(() => proceed(true), 700);
        } else if (data.status === 'error' || data.status === 'not_found') {
          msgEl.textContent = 'Recompute hit an issue — showing verified snapshot data.';
          subEl.textContent = data.error || 'Falling back to mode=real';
          setTimeout(() => proceed(false), 1800);
        } else {
          setTimeout(poll, 3000);
        }
      })
      .catch(() => {
        // API unreachable mid-poll — degrade honestly rather than hang.
        msgEl.textContent = 'Lost contact with the forecasting API — showing verified snapshot data.';
        setTimeout(() => proceed(false), 1800);
      });
  };
  poll();
}
</script>

</body>
</html>

<?php
/**
 * mark-live-ready.php
 * --------------------
 * Called by processing.php once it has confirmed (via /recompute-status)
 * that the real recompute job actually finished. Sets the session flag
 * that lets dashboard.php / buy-plan.php / forecast-detail.php request
 * mode=live&job_id=... instead of mode=real.
 *
 * This is deliberately a separate, tiny endpoint rather than folding the
 * flag-setting into processing.php's initial page load, because the real
 * completion is only known client-side, after polling — the server can't
 * know it up front.
 */
session_start();

$live = isset($_GET['live']) && $_GET['live'] === '1';
$jobId = $_GET['job_id'] ?? null;

if ($live && !empty($_SESSION['recompute_job_id']) && $_SESSION['recompute_job_id'] === $jobId) {
    $_SESSION['live_job_ready'] = true;
} else {
    $_SESSION['live_job_ready'] = false;
}

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'live_job_ready' => $_SESSION['live_job_ready']]);

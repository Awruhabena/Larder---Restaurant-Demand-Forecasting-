<?php
/**
 * reset-journey.php
 * ------------------
 * TESTING/QA UTILITY — not part of the designed app, not linked from any
 * real screen. Calls session_unset() + session_destroy(), which clears
 * every session variable — subscribed, just_uploaded, data_ready, and the
 * live-recompute pair (recompute_job_id, live_job_ready) — so you can
 * re-test the full journey, including a fresh real recompute, from
 * scratch without clearing browser cookies manually.
 *
 * Visit this URL directly whenever you want to restart the journey:
 *   http://localhost/larder/reset-journey.php
 */
session_start();
session_unset();
session_destroy();
echo "Session reset. <a href='register.php'>Start the journey again →</a>";

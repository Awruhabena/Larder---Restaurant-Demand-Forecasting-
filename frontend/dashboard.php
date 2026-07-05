<?php
/**
 * dashboard.php
 * -------------
 * One screen, three data states, chosen by the user's actual progress
 * rather than a URL parameter alone.
 *   preview -> generic sample data, shown until the journey earns 'real'
 *   real    -> the verified pre-computed snapshot, earned by paying + uploading
 *   live    -> the genuine recompute result for THIS user's uploaded data,
 *              earned once processing.php has confirmed (via polling
 *              /recompute-status) that the real engine actually finished
 *              — set via $_SESSION['live_job_ready'] + recompute_job_id.
 * An explicit ?mode=preview always forces preview.
 */
session_start();

$journeyHasRealData = !empty($_SESSION['data_ready']);
$liveReady = !empty($_SESSION['live_job_ready']) && !empty($_SESSION['recompute_job_id']);
$jobId = $_SESSION['recompute_job_id'] ?? null;
$requestedMode = $_GET['mode'] ?? null;

if ($requestedMode === 'preview') {
    $mode = 'preview';                       // explicit override, always honoured
} elseif ($liveReady) {
    $mode = 'live';                          // genuine recompute finished for this user
} elseif ($journeyHasRealData) {
    $mode = 'real';                          // earned by paying + uploading
} else {
    $mode = 'preview';                       // default until the journey earns 'real'
}

$apiBase = 'http://localhost:8000'; // Python API - run via `python runner.py`
$modeQuery = "mode={$mode}" . ($mode === 'live' ? '&job_id=' . urlencode($jobId) : '');
$json = @file_get_contents("{$apiBase}/headline?{$modeQuery}");

if ($json === false) {
    die('Could not reach the Larder API at ' . $apiBase . '. Is `python runner.py` running?');
}

$headline = json_decode($json, true);
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Larder — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;600&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="larder-tokens.css">
</head>
<body>
<x-dc>
<helmet>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<style>/* cyrillic-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("016f7a84-6012-4d24-9cd6-7d327ceda6ca") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* vietnamese */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("1a371835-8947-44f4-91d6-73c45d160ba4") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("67ac90ce-ef9b-42ef-a8d0-f7cd15342ee7") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("266434a6-1412-4377-ae32-84fb4dc5c4d7") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("016f7a84-6012-4d24-9cd6-7d327ceda6ca") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* vietnamese */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("1a371835-8947-44f4-91d6-73c45d160ba4") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("67ac90ce-ef9b-42ef-a8d0-f7cd15342ee7") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("266434a6-1412-4377-ae32-84fb4dc5c4d7") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("016f7a84-6012-4d24-9cd6-7d327ceda6ca") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* vietnamese */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("1a371835-8947-44f4-91d6-73c45d160ba4") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("67ac90ce-ef9b-42ef-a8d0-f7cd15342ee7") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("266434a6-1412-4377-ae32-84fb4dc5c4d7") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url("016f7a84-6012-4d24-9cd6-7d327ceda6ca") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* vietnamese */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url("1a371835-8947-44f4-91d6-73c45d160ba4") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url("67ac90ce-ef9b-42ef-a8d0-f7cd15342ee7") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Hanken Grotesk';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url("266434a6-1412-4377-ae32-84fb4dc5c4d7") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("393c42c0-67d4-43c0-84d9-5695188f6cea") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("43c3808c-1723-4c18-a211-232de1cb8961") format('woff2');
  unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* vietnamese */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("196a7922-53b8-4f53-9399-de023586ed1b") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("d1337b41-aee3-40bf-8f48-9a377d6750f9") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("a47e7c2a-d593-4f3b-8b62-3e8a04dc098e") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("dceb9345-debe-4ac3-8a74-5492774958be") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("71310d1e-8ce1-4a54-8655-bd3d73abd19f") format('woff2');
  unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* vietnamese */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("1adc8724-6f4e-4cf7-b099-ca1125522365") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("a10dc85e-024b-4eab-9626-eb1813ec6719") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("211f4842-9663-4ac8-b983-fa2276306ce0") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("1e036cb6-2e0e-4564-a258-be8ec6bf17cf") format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C8A, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("9d8aa4c0-2e17-49cb-8cdc-c6514b5206c9") format('woff2');
  unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* vietnamese */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("62cd743a-5f02-4752-8f2d-b4375de6f970") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("5bffb75b-39e4-4e65-915a-a1a3ff0b083c") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'IBM Plex Mono';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("117b7bc2-134a-42a0-8ef8-220f132f49d7") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* vietnamese */
@font-face {
  font-family: 'Newsreader';
  font-style: italic;
  font-weight: 400;
  font-display: swap;
  src: url("f71d2c28-246b-4b39-88aa-3d0b4e16f0a9") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Newsreader';
  font-style: italic;
  font-weight: 400;
  font-display: swap;
  src: url("a6435185-e3ba-47fa-8f58-9552a873b3b8") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Newsreader';
  font-style: italic;
  font-weight: 400;
  font-display: swap;
  src: url("526bb260-38b0-410a-b5d2-9b8fcb97e3e2") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* vietnamese */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("ce191e95-dfce-4acf-8f99-9fbfbee15d25") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("e1ee587c-1394-4b22-8470-22ad4341028b") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url("8b150351-3954-49b1-a5fd-18c93bc27265") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* vietnamese */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("ce191e95-dfce-4acf-8f99-9fbfbee15d25") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("e1ee587c-1394-4b22-8470-22ad4341028b") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 500;
  font-display: swap;
  src: url("8b150351-3954-49b1-a5fd-18c93bc27265") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* vietnamese */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("ce191e95-dfce-4acf-8f99-9fbfbee15d25") format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("e1ee587c-1394-4b22-8470-22ad4341028b") format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Newsreader';
  font-style: normal;
  font-weight: 600;
  font-display: swap;
  src: url("8b150351-3954-49b1-a5fd-18c93bc27265") format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
</style>
<style>
  body { margin:0; background:#EDE9E1; -webkit-font-smoothing:antialiased; text-rendering:optimizeLegibility; }
  *{ box-sizing:border-box; }
  ::-webkit-scrollbar{ width:10px; height:10px; }
  ::-webkit-scrollbar-thumb{ background:#D3CCBE; border-radius:6px; border:2px solid #EDE9E1; }
</style>
</helmet>

<div style="font-family:'Hanken Grotesk',sans-serif; color:#1B1A16; display:flex; flex-direction:column; min-height:100vh; background:#F4F1EA;">

  <!-- ============ TOP BAR ============ -->
  <header style="position:sticky; top:0; z-index:30; background:#FBFAF6; border-bottom:1px solid #E7E1D6;">
    <div style="max-width:1560px; margin:0 auto; padding:16px 34px; display:flex; align-items:center; justify-content:space-between; gap:28px;">
      <div style="display:flex; align-items:center; gap:11px; flex:1;">
        <div style="width:32px; height:32px; border-radius:9px; background:#1E4A38; display:flex; align-items:center; justify-content:center;"><div style="width:13px; height:13px; border:2.5px solid #E9C46A; border-radius:50%; border-top-color:transparent; transform:rotate(-45deg);"></div></div>
        <span style="font-size:19px; font-weight:600; letter-spacing:-0.01em;">Larder</span>
      </div>
      <nav style="display:flex; align-items:center; gap:2px; background:#F1ECE1; padding:5px; border-radius:12px;">
        <a href="dashboard.php" style="padding:9px 18px; border-radius:9px; background:#1E4A38; color:#F6F1E7; font-size:14px; font-weight:600; text-decoration:none;">Dashboard</a>
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
      <a href="dashboard.php" title="Dashboard" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
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
  <main style="flex:1; min-width:0; overflow-x:hidden;">
    <div style="max-width: 1320px; margin: 0 auto; padding: 44px 44px 56px; position: relative">

      <!-- sample-data notice -->
      <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; background:#FBF0DE; border:1px solid #EAD9B2; border-radius:10px; padding:10px 16px; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#8A5A12;">
          <?php if ($mode === 'preview'): ?>
            You're viewing <strong style="font-weight:700;">demo data</strong> — these are sample numbers so you can see how Larder works.
          <?php else: ?>
            You're viewing <strong style="font-weight:700;">your data</strong> — this is the real forecast from your uploaded sales history.
          <?php endif; ?>
        </div>
        <a href="<?php echo $mode === 'preview' ? 'pricing.php' : '#'; ?>" style="font-size:13px; font-weight:600; color:#1E4A38; text-decoration:none; white-space:nowrap;"><?php echo $mode === 'preview' ? 'Connect your restaurant →' : ''; ?></a>
      </div>

      <!-- topbar -->
      <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
          <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Monday · 2 January 2026 · Week 1 of 4</div>
          <div style="font-family:'Newsreader',serif; font-size:32px; letter-spacing:-0.01em; margin-top:6px;">Good morning, Kwame.</div>
        </div>
        <span style="display:inline-flex; align-items:center; gap:7px; background:#FFFFFF; border:1px solid #E3DED4; color:#57544C; font-size:12.5px; font-weight:600; padding:8px 13px; border-radius:999px;"><?php echo $headline['accuracy_pct']; ?>% forecast accuracy</span>
      </div>

      <!-- HERO METRICS -->
      <div style="display:grid; grid-template-columns:1.35fr 1.35fr 1fr; gap:16px;">
        <!-- buy plan cost -->
        <div style="background:#FAF8F3; border-radius:16px; padding:18px 20px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="width:30px; height:30px; border-radius:9px; background:#EFE9DD; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="16" height="16" viewBox="0 0 16 16"><rect x="3" y="2.5" width="10" height="11" rx="1.6" fill="none" stroke="#1E4A38" stroke-width="1.4"></rect><line x1="5.6" y1="6" x2="10.4" y2="6" stroke="#1E4A38" stroke-width="1.4" stroke-linecap="round"></line><line x1="5.6" y1="9" x2="10.4" y2="9" stroke="#1E4A38" stroke-width="1.4" stroke-linecap="round"></line></svg></span>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#7C7869;">THIS WEEK'S BUY PLAN FORCAST</span>
          </div>
          <div style="display:flex; align-items:baseline; gap:8px; margin-top:16px;"><span style="font-size:18px; font-weight:600; color:#8B877C;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:0.95;"><?php echo number_format($headline['plan_total_ghs']); ?></span><span style="font-size:13px; color:#B4AFA3; font-weight:500;">· at your prices</span></div>
          <div style="margin-top:11px; font-size:13px; color:#7C7869;">across <strong style="font-weight:700; color:#57544C;"><?php echo $headline['items_count']; ?> items</strong> · 4-week horizon</div>
        </div>
        <!-- savings -->
        <div style="background:#E7F1EA; border-radius:16px; padding:18px 20px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="width:30px; height:30px; border-radius:9px; background:#FFFFFF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="16" height="16" viewBox="0 0 16 16"><path d="M8 12.5 V4 M4.6 7.4 L8 4 L11.4 7.4" fill="none" stroke="#2E8B5D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#5E8A72;">Estimated savings</span>
          </div>
          <div style="display:flex; align-items:baseline; gap:8px; margin-top:16px;"><span style="font-size:18px; font-weight:600; color:#2E8B5D;">GHS</span><span style="font-family:'Newsreader',serif; font-size:44px; font-weight:500; letter-spacing:-0.02em; line-height:0.95; color:#2E8B5D;"><?php echo number_format($headline['savings_ghs']); ?></span><span style="font-size:13px; color:#8FA69A; font-weight:500;">estimated · at your prices</span></div>
          <div style="display:flex; align-items:center; gap:8px; margin-top:11px;"><span style="display:inline-flex; align-items:center; gap:5px; background:#FFFFFF; color:#2E8B5D; font-size:12px; font-weight:600; padding:3px 8px; border-radius:999px;">▲ 10.9%</span><span style="font-size:13px; color:#5E8A72;">vs last year's pattern</span></div>
        </div>
        <!-- supporting stats -->
        <div style="display:flex; flex-direction:column; gap:16px;">
          <div style="flex:1; background:#F0E3C0; border-radius:16px; padding:14px 18px; display:flex; flex-direction:column; justify-content:center;">
            <div style="display:flex; align-items:center; gap:9px;">
              <span style="width:26px; height:26px; border-radius:8px; background:#FFFFFF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 16 16"><circle cx="8" cy="8" r="5.4" fill="none" stroke="#C0791F" stroke-width="1.4"></circle><circle cx="8" cy="8" r="1.6" fill="#C0791F"></circle></svg></span>
              <span style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.08em; text-transform:uppercase; color:#9A7433;">Forecast accuracy</span>
            </div>
            <div style="font-size:24px; font-weight:600; letter-spacing:-0.01em; margin-top:8px;"><?php echo $headline['accuracy_pct']; ?><span style="font-size:15px; color:#9A7433;">%</span></div>
          </div>
          <div style="flex:1; background:#EAEEE9; border-radius:16px; padding:14px 18px; display:flex; flex-direction:column; justify-content:center;">
            <div style="display:flex; align-items:center; gap:9px;">
              <span style="width:26px; height:26px; border-radius:8px; background:#FFFFFF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 16 16"><rect x="2.5" y="2.5" width="4.5" height="4.5" rx="1" fill="#57544C"></rect><rect x="9" y="2.5" width="4.5" height="4.5" rx="1" fill="#57544C"></rect><rect x="2.5" y="9" width="4.5" height="4.5" rx="1" fill="#57544C"></rect><rect x="9" y="9" width="4.5" height="4.5" rx="1" fill="#57544C"></rect></svg></span>
              <span style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; letter-spacing:0.08em; text-transform:uppercase; color:#7C7869;">Items tracked</span>
            </div>
            <div style="font-size:24px; font-weight:600; letter-spacing:-0.01em; margin-top:8px;">71</div>
          </div>
        </div>
      </div>
      <div style="font-size:12px; color:#B4AFA3; margin-top:10px;">Quantities are forecast; costs use the prices you set.</div>

      <!-- PRIMARY CTA -->
      <a href="buy-plan.php" style="text-decoration:none; color:inherit; display:block; margin-top:16px;">
        <div style="background:#1E4A38; border-radius:16px; padding:30px 32px; display:flex; align-items:center; justify-content:space-between; gap:24px; box-shadow:0 18px 40px rgba(30,74,56,0.18); position:relative; overflow:hidden;">
          <svg width="72" height="72" viewBox="0 0 24 24" style="position:absolute; top:-14px; right:110px; opacity:0.5;" fill="#E9C46A"><path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z"></path></svg>
          <div style="position:relative; z-index:1;">
            <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8FA69A;">Weekly buy plan</div>
            <div style="font-family:'Newsreader',serif; font-size:32px; color:#F4F1EA; letter-spacing:-0.01em; line-height:1.15; margin-top:8px; max-width:460px;">From forecast to purchase order — handle it all here.</div>
            <div style="font-size:13.5px; color:#B9C7BE; margin-top:10px;"><?php echo $headline['items_count']; ?> items forecast · <strong style="color:#F4F1EA; font-weight:600;">GHS <?php echo number_format($headline['plan_total_ghs']); ?></strong> to spend for the week of Jan 2 · <span style="color:#8FA69A;">at your prices</span>.</div>
          </div>
          <div style="display:flex; align-items:center; gap:10px; background:#E9C46A; color:#1B1A16; font-size:15px; font-weight:700; padding:13px 22px; border-radius:10px; flex-shrink:0; position:relative; z-index:1;">Review This Week's Buy <span style="font-size:18px;">→</span></div>
        </div>
      </a>

      <!-- BIGGEST MOVERS -->
      <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:24px 28px; margin-top:16px; box-shadow:0 1px 2px rgba(27,26,22,0.04);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
          <div>
            <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">This week</div>
            <div style="font-family:'Newsreader',serif; font-size:22px; margin-top:4px;">Biggest movers</div>
            <div style="font-size:13.5px; color:#8B877C; margin-top:3px;">Where demand shifted most — everything else is steady.</div>
          </div>
          <div style="display:flex; gap:14px; padding-top:6px;">
            <div style="display:flex; align-items:center; gap:6px;"><span style="width:11px; height:11px; border-radius:3px; background:#2E8B5D;"></span><span style="font-size:12px; color:#57544C;">▲ up</span></div>
            <div style="display:flex; align-items:center; gap:6px;"><span style="width:11px; height:11px; border-radius:3px; background:#B0472E;"></span><span style="font-size:12px; color:#57544C;">▼ down</span></div>
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1.5fr 1fr; gap:28px; align-items:center; margin-top:10px;">
          <svg viewBox="0 0 640 300" style="width:100%; height:auto; display:block;">
            <line x1="56" y1="70" x2="612" y2="70" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="110" x2="612" y2="110" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="190" x2="612" y2="190" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="230" x2="612" y2="230" stroke="#EFEBE2" stroke-width="1"></line>
            <line x1="56" y1="150" x2="612" y2="150" stroke="#D3CCBE" stroke-width="1.25"></line>
            <text x="46" y="74" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">+20</text>
            <text x="46" y="154" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">0</text>
            <text x="46" y="234" text-anchor="end" fill="#B4AFA3" font-family="IBM Plex Mono, monospace" font-size="11">−20</text>
            <rect x="83.6" y="86" width="56" height="64" rx="5" fill="#2E8B5D"></rect>
            <rect x="194.8" y="106" width="56" height="44" rx="5" fill="#2E8B5D"></rect>
            <rect x="306" y="130" width="56" height="20" rx="5" fill="#2E8B5D"></rect>
            <rect x="417.2" y="150" width="56" height="24" rx="5" fill="#B0472E"></rect>
            <rect x="528.4" y="150" width="56" height="36" rx="5" fill="#B0472E"></rect>
            <text x="111.6" y="78" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲16%</text>
            <text x="222.8" y="98" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲11%</text>
            <text x="334" y="122" text-anchor="middle" fill="#2E8B5D" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▲5%</text>
            <text x="445.2" y="188" text-anchor="middle" fill="#B0472E" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▼6%</text>
            <text x="556.4" y="200" text-anchor="middle" fill="#B0472E" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="600">▼9%</text>
            <text x="111.6" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Chicken</text>
            <text x="222.8" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Burgers</text>
            <text x="334" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Salads</text>
            <text x="445.2" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Spaghetti</text>
            <text x="556.4" y="256" text-anchor="middle" fill="#57544C" font-family="Hanken Grotesk, sans-serif" font-size="12.5" font-weight="600">Turkey</text>
          </svg>

          <div style="display:flex; flex-direction:column; gap:12px;">
            <div style="display:flex; align-items:flex-start; gap:11px; padding:12px 14px; background:#FBF9F4; border:1px solid #EFEBE2; border-radius:10px;">
              <span style="color:#2E8B5D; font-size:15px; font-weight:700; margin-top:1px;">▲</span>
              <div><div style="font-size:14px; font-weight:600;">Chicken Breast climbing</div><div style="font-size:13px; color:#57544C; margin-top:2px;">Up 16% — the week's biggest buy increase.</div></div>
            </div>
            <div style="display:flex; align-items:flex-start; gap:11px; padding:12px 14px; background:#FBF9F4; border:1px solid #EFEBE2; border-radius:10px;">
              <span style="color:#B0472E; font-size:15px; font-weight:700; margin-top:1px;">▼</span>
              <div><div style="font-size:14px; font-weight:600;">Turkey Dishes easing</div><div style="font-size:13px; color:#57544C; margin-top:2px;">Down 9% — buy less, avoid waste.</div></div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:10px;">
              <span style="color:#8B877C; font-size:15px;">—</span>
              <div style="font-size:13px; color:#8B877C;"><strong style="color:#57544C; font-weight:700;">66 of 71 items</strong> are steady this week.</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
  </div>
</div>
</x-dc>


</body></html>
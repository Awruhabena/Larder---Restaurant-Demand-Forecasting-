<?php
/**
 * settings.php
 * ------------
 * The price-entry area — the piece that makes the Buy Plan / Dashboard
 * cedis figures honest ("at your prices"). No live API connection for this
 * screen (see API reference "no backend" list) — prices are held in the
 * PHP session for now. The backend's build_plan_from_orders() already
 * accepts a `prices` override dict; wiring THIS form's saved prices into
 * that call is the natural next step once persistence is added.
 */
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prices'])) {
    $_SESSION['user_prices'] = $_POST['prices']; // [item_name => price], saved for this session
}
$savedPrices = $_SESSION['user_prices'] ?? [
    'Chicken Breast' => '174.24',
    'Salads' => '68.00',
    'Pork Chops' => '168.00',
    'Burgers' => '42.50',
];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Larder — Settings</title>
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
      <a href="settings.php" title="Settings" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#1E4A38; color:#F6F1E7; text-decoration:none;">
        <svg width="19" height="19" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="4" y1="7" x2="16" y2="7"></line><circle cx="8" cy="7" r="2"></circle><line x1="4" y1="13" x2="16" y2="13"></line><circle cx="13" cy="13" r="2"></circle></svg>
      </a>
      <div style="width:22px; height:1px; background:#EBE5DA; margin:6px 0;"></div>
      <a href="#" title="Log out" style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#B4AFA3; text-decoration:none;">
        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 14 V16 H4 V4 H12 V6"></path><path d="M8 10 H17 M14 7 L17 10 L14 13"></path></svg>
      </a>
    </div>
  </aside>

  <!-- ============ MAIN ============ -->
  
<main style="flex:1; min-width:0; overflow-x:hidden;"><div style="max-width:900px; margin:0 auto; padding:36px 24px; box-sizing:border-box;">
  <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#8B877C;">Account</div>
  <h1 style="font-family:'Newsreader',serif; font-size:34px; font-weight:500; letter-spacing:-0.01em; margin:6px 0 0;">Settings</h1>

  <!-- RESTAURANT PROFILE -->
  <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:26px;">
    <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:16px;">Restaurant profile</div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
      <div>
        <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Restaurant name</label>
        <input type="text" value="<?php echo htmlspecialchars($_SESSION['restaurant_name'] ?? 'Akwaaba Kitchen'); ?>" style="font-family:inherit; width:100%; background:#F4F1EA; border:1px solid #D3CCBE; border-radius:9px; padding:11px 13px; font-size:14px; box-sizing:border-box;">
      </div>
      <div>
        <label style="display:block; font-size:12.5px; font-weight:600; color:#57544C; margin-bottom:7px;">Location</label>
        <input type="text" value="Osu, Accra" style="font-family:inherit; width:100%; background:#F4F1EA; border:1px solid #D3CCBE; border-radius:9px; padding:11px 13px; font-size:14px; box-sizing:border-box;">
      </div>
    </div>
  </div>

  <!-- UNIT PRICES (the working price-entry form) -->
  <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Unit prices</div>
      <div style="font-size:12px; color:#8B877C;">Used for every GHS figure across Larder</div>
    </div>
    <div style="font-size:13px; color:#8B877C; margin-top:10px; margin-bottom:4px;">
      Larder predicts <strong>quantity</strong> — how much you'll sell. It never predicts price. Enter your own current prices so buy-plan costs are accurate to your restaurant.
    </div>

    <form method="post" id="priceForm">
      <div style="display:grid; grid-template-columns:1.4fr 1fr auto; gap:14px; padding:10px 0 8px;">
        <span style="font-size:11px; font-weight:600; color:#8B877C; text-transform:uppercase; letter-spacing:0.06em;">Item name</span>
        <span style="font-size:11px; font-weight:600; color:#8B877C; text-transform:uppercase; letter-spacing:0.06em;">Unit price</span>
        <span></span>
      </div>
      <div id="priceRows"></div>

      <button type="button" id="addRowBtn" style="font-family:inherit; margin-top:14px; background:#FFFFFF; color:#1E4A38; border:1px dashed #9AB6A6; font-size:13px; font-weight:600; padding:10px 16px; border-radius:9px; cursor:pointer; display:flex; align-items:center; gap:8px;">
        <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="10" y1="4" x2="10" y2="16"></line><line x1="4" y1="10" x2="16" y2="10"></line></svg>
        Add item
      </button>

      <div style="display:flex; align-items:center; gap:9px; margin-top:16px; background:#FBF9F4; border:1px solid #E3DED4; border-radius:9px; padding:10px 13px;">
        <svg width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="#57544C" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="7.5"></circle><path d="M10 9 V14"></path><circle cx="10" cy="6.4" r="0.4" fill="#57544C"></circle></svg>
        <div style="font-size:12.5px; color:#57544C; line-height:1.45;">These prices feed every GHS figure across Larder — add every item you buy so your Buy Plan totals match what you actually pay.</div>
      </div>

      <div style="margin-top:18px;">
        <button type="submit" style="font-family:inherit; background:#1E4A38; color:#F6F1E7; border:none; font-size:14px; font-weight:600; padding:12px 24px; border-radius:9px; cursor:pointer;">Save prices</button>
      </div>
    </form>
  </div>

  <!-- TEAM MEMBERS -->
  <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
      <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C;">Team members</div>
      <button style="font-family:inherit; background:#FFFFFF; color:#1E4A38; border:1px solid #D3CCBE; font-size:12.5px; font-weight:600; padding:8px 14px; border-radius:8px; cursor:pointer;">+ Invite</button>
    </div>
    <div style="display:flex; flex-direction:column; margin-top:14px;">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 0; border-bottom:1px solid #F0ECE4;">
        <div style="display:flex; align-items:center; gap:10px;"><div style="width:28px; height:28px; border-radius:40px; background:#E9C46A; color:#1E4A38; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px;">AK</div><span style="font-size:13.5px;">Akwaaba Owusu · you</span></div>
        <span style="font-size:12px; color:#57544C;">Owner</span>
      </div>
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 0;">
        <div style="display:flex; align-items:center; gap:10px;"><div style="width:28px; height:28px; border-radius:8px; background:#E3DED4; color:#57544C; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px;">MK</div><span style="font-size:13.5px;">Maame Konadu</span></div>
        <span style="font-size:12px; color:#57544C;">Manager</span>
      </div>
    </div>
  </div>

  <!-- NOTIFICATION PREFERENCES -->
  <div style="background:#FFFFFF; border:1px solid #E3DED4; border-radius:14px; padding:22px 26px; margin-top:16px;">
    <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#8B877C; margin-bottom:14px;">Notification preferences</div>
    <div style="display:flex; flex-direction:column; gap:14px;">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
        <span style="font-size:13.5px;">Weekly buy plan ready</span>
        <div style="width:38px; height:22px; border-radius:999px; background:#1E4A38; position:relative;"><div style="width:16px; height:16px; border-radius:50%; background:#FFFFFF; position:absolute; top:3px; right:3px;"></div></div>
      </div>
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
        <span style="font-size:13.5px;">Demand-shift alerts</span>
        <div style="width:38px; height:22px; border-radius:999px; background:#1E4A38; position:relative;"><div style="width:16px; height:16px; border-radius:50%; background:#FFFFFF; position:absolute; top:3px; right:3px;"></div></div>
      </div>
      <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
        <span style="font-size:13.5px;">Product updates</span>
        <div style="width:38px; height:22px; border-radius:999px; background:#E3DED4; position:relative;"><div style="width:16px; height:16px; border-radius:50%; background:#FFFFFF; position:absolute; top:3px; left:3px;"></div></div>
      </div>
    </div>
  </div>

</div></main>

<script>
let items = <?php echo json_encode(array_values(array_map(fn($n, $p) => ['name' => $n, 'price' => $p], array_keys($savedPrices), $savedPrices))); ?>;
let nextId = items.length;

function render() {
  const container = document.getElementById('priceRows');
  container.innerHTML = items.map((it, i) => `
    <div style="display:grid; grid-template-columns:1.4fr 1fr auto; gap:14px; align-items:center; padding:8px 0; border-bottom:1px solid #F0ECE4;">
      <input type="text" name="prices[${i}][name]" placeholder="e.g. Chicken Breast" value="${it.name}"
             style="font-family:inherit; width:100%; background:#F4F1EA; border:1px solid #D3CCBE; border-radius:7px; padding:9px 11px; font-size:13.5px; box-sizing:border-box;">
      <span style="display:flex; align-items:center; gap:6px;">
        <span style="font-size:12px; color:#8B877C;">GHS</span>
        <input type="text" name="prices[${i}][price]" placeholder="0.00" value="${it.price}"
               style="font-family:'IBM Plex Mono',monospace; width:100%; background:#F4F1EA; border:1px solid #D3CCBE; border-radius:7px; padding:9px 11px; font-size:13px; box-sizing:border-box;">
      </span>
      <button type="button" onclick="removeRow(${i})" title="Remove item"
              style="font-family:inherit; width:30px; height:30px; border-radius:7px; background:#FFFFFF; border:1px solid #E3DED4; color:#B0472E; cursor:pointer; display:flex; align-items:center; justify-content:center;">
        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><line x1="5" y1="5" x2="15" y2="15"></line><line x1="15" y1="5" x2="5" y2="15"></line></svg>
      </button>
    </div>
  `).join('');
}
function removeRow(i) { items.splice(i, 1); render(); }
document.getElementById('addRowBtn').onclick = () => { items.push({name: '', price: ''}); render(); };
render();
</script>

</div>
</body>
</html>

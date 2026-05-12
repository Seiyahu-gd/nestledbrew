<?php session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart — NestledBrew</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  <link rel="stylesheet" href="cart.css">
</head>
<body>

<header id="mainHeader">
  <div class="nav-topstrip">
    <div class="nav-topstrip-contact">
      <span>📍 LiveLoveLieSt., Cebu City</span>
      <span>🕐 Mon–Fri: 7AM – 8PM &nbsp;·&nbsp; Sat–Sun: 8AM – 9PM</span>
      <span>📞 +63 917 123 4567</span>
    </div>
    <div class="nav-topstrip-actions">
      <a href="mailto:nestledbrew@gmail.com">nestledbrew@gmail.com</a>
      <span style="opacity:0.3">|</span>
      <button onclick="toggleMode()" id="modeBtn">☀ Light Mode</button>
    </div>
  </div>
  <div class="nav-main">
    <a href="homepage.php" class="brand-link">
      <div class="logo-mark">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="1.5"/>
          <text x="50%" y="56%" dominant-baseline="middle" text-anchor="middle" font-family="Fraunces, serif" font-size="16" font-style="italic" fill="currentColor">N</text>
        </svg>
      </div>
      <span class="brand-text">NestledBrew</span>
    </a>
    <nav class="nav-links">
      <a href="homepage.php" class="nav-link">Home</a>
      <a href="menu.php" class="nav-link active">Menu</a>
      <a href="about.php" class="nav-link">About Us</a>
      <a href="rewards.php" class="nav-link">Rewards</a>
    </nav>
    <div class="nav-actions">
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-profile-group">
          <a href="rewards.php" class="nav-profile-link">
            <div class="profile-circle" style="<?php echo !empty($_SESSION['user_picture']) ? 'padding:0;overflow:hidden;' : ''; ?>">
              <?php if (!empty($_SESSION['user_picture'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['user_picture']); ?>"
                    alt="Profile"
                    style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
              <?php else: ?>
                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
              <?php endif; ?>
            </div>
            <span class="user-name-text"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          </a>
          <a href="logout.php" class="logout-btn">Logout</a>
        </div>
      <?php else: ?>
        <a href="login.php" class="nav-link">Sign In</a>
        <a href="login.php#signup" class="btn btn-primary">Join Now</a>
      <?php endif; ?>
    </div>
    <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<main>

<?php if(!isset($_SESSION['user_id'])): ?>
  <!-- Not logged in -->
  <div style="text-align:center;padding:160px 40px 80px;display:flex;flex-direction:column;align-items:center;gap:16px;">
    <div style="font-size:3rem;">☕</div>
    <h2 style="font-family:var(--font-display);font-size:1.8rem;font-style:italic;color:var(--text);">Sign in to view your cart</h2>
    <p style="color:var(--text-muted);font-size:0.95rem;margin-bottom:8px;">Your cart is saved to your account so it's always with you.</p>
    <div style="display:flex;gap:12px;">
      <a href="login.php" class="btn btn-primary btn-lg">Sign In</a>
      <a href="login.php#signup" class="btn btn-lg">Create Account</a>
    </div>
  </div>

<?php else:
  $uid = $_SESSION['user_id'];
  $ps  = $conn->prepare("SELECT user_points, tier FROM users WHERE id = ?");
  $ps->bind_param("i", $uid);
  $ps->execute();
  $pu         = $ps->get_result()->fetch_assoc();
  $user_points = $pu['user_points'] ?? 0;
  $user_tier   = $pu['tier'] ?? 'Bean Starter';
?>

  <!-- Page Hero -->
  <div class="page-hero">
    <span class="page-hero-eyebrow">Your Order</span>
    <h1 class="page-hero-title">Your <em>Cart</em></h1>
    <p class="page-hero-sub">Saved to your account — shop from any device and pick up right where you left off.</p>
  </div>

  <div class="cart-container">

    <!-- Points Banner -->
    <div class="points-banner-bar reveal">
      <div class="points-banner-bar-left">
        <span style="font-size:1.3rem;color:var(--accent);">✦</span>
        <div>
          <div style="font-size:0.88rem;font-weight:600;color:var(--text);">Your BrewPoints</div>
          <div style="font-size:0.78rem;color:var(--text-muted);margin-top:2px;">
            <?php echo htmlspecialchars($user_tier); ?> · Redeem points for a discount at checkout
          </div>
        </div>
      </div>
      <div style="font-family:var(--font-display);font-size:1.3rem;font-weight:600;font-style:italic;color:var(--accent);">
        <?php echo number_format($user_points); ?> pts
      </div>
    </div>

    <!-- Cart Grid -->
    <div class="cart-main">

      <!-- Left: Items -->
      <div class="cart-items-section">
        <h2>Order Items</h2>
        <div id="cartItems">
          <div class="cart-loading-state">
            <div class="cart-spinner"></div>
            <span>Loading your cart...</span>
          </div>
        </div>
      </div>

      <!-- Right: Summary (populated by cart.js) -->
      <div class="cart-summary" id="cartSummary">
        <div style="color:var(--text-muted);font-size:0.9rem;text-align:center;padding:20px 0;">
          Loading summary...
        </div>
      </div>

    </div>
  </div>

<?php endif; ?>
</main>

<div class="toast" id="globalToast"></div>

<?php include 'footer.php'; ?>

<script src="homepage.js"></script>
<script src="cart.js"></script>

<style>
.points-banner-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 22px;
  background: var(--card-bg);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 28px;
  backdrop-filter: blur(8px);
}
.points-banner-bar-left {
  display: flex;
  align-items: center;
  gap: 14px;
}
.cart-loading-state {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 60px;
  color: var(--text-muted);
  font-size: 0.9rem;
}
.cart-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid var(--border);
  border-top-color: var(--accent);
  border-radius: 50%;
  animation: cartSpin 0.8s linear infinite;
  display: inline-block;
  flex-shrink: 0;
}
@keyframes cartSpin { to { transform: rotate(360deg); } }
</style>
</body>
</html>
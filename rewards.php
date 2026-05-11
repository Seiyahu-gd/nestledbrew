<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rewards — NestledBrew</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  <link rel="stylesheet" href="rewards.css">
</head>
<body>

  <div class="page-loader" id="pageLoader">
    <div class="loader-inner"><div class="loader-logo">N</div></div>
  </div>

<header id="mainHeader">
  <div class="nav-topstrip">
    <div class="nav-topstrip-contact">
      <span>📍 LiveLoveLieSt., Cebu City</span>
      <span>🕐 Mon–Fri: 7AM – 8PM &nbsp;·&nbsp; Sat–Sun: 8AM – 9PM</span>
      <span>📞 +63 917 123 4567</span>
    </div>
    <div class="nav-topstrip-actions">
      <a href="mailto:hello@nestledbrew.com">nestledbrew@gmail.com</a>
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
      <a href="menu.php" class="nav-link">Menu</a>
      <a href="about.php" class="nav-link">About Us</a>
      <a href="rewards.php" class="nav-link active">Rewards</a>
    </nav>

    <div class="nav-actions">
      <a href="cart.php" class="cart-nav-link">
    🛒 <span class="cart-nav-label">Cart</span>
      </a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-profile-group">
          <a href="profile.php" class="nav-profile-link">
            <div class="profile-circle">
              <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
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

  <main class="dashboard-container">
    <div class="main-content">
      <div class="points-card animate-fadeUp">
        <p style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem;">Your BrewPoints</p>
        <div class="points-display">
        <div class="points-number rewards-card-pts-label">
        <?php echo isset($_SESSION['user_points']) ? $_SESSION['user_points'] : '0'; ?>
        </div>
        <p>Bean Starter Tier</p>
        </div>
      </div>

      <div class="action-grid">
        <div class="card-panel reveal">
          <h3>Scan Your Cup</h3>
          <p class="text-muted" style="font-size: 0.9rem;">Enter the 6-digit code found under your cup sleeve.</p>
          <div class="qr-input-group">
            <input type="text" placeholder="XX-XX-XX" style="flex: 1; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg);">
            <button class="btn btn-primary">Apply</button>
          </div>
        </div>

        <div class="card-panel reveal">
          <h3>Tip of the Day!</h3>
          <div style="display: grid; gap: 10px; margin-top: 16px;">
          <p class="text-muted" style="font-size: 0.9rem; color: var(--accent);">There is no expiration date for points!</p>
          </div>
        </div>
      </div>

      <div class="card-panel reveal" style="margin-top: 32px;">
        <h3 style="margin-bottom: 20px;">Redeem Rewards</h3>
        <div class="reward-item">
          <div class="reward-icon">☕</div>
          <div style="flex: 1;">
            <p><strong>Free Espresso Shot</strong></p>
            <p class="text-muted" style="font-size: 0.8rem;">200 Points</p>
          </div>
          <button class="btn ">Redeem</button>
        </div>
        <div class="reward-item">
          <div class="reward-icon">🥐</div>
          <div style="flex: 1;">
            <p><strong>Any Pastry</strong></p>
            <p class="text-muted" style="font-size: 0.8rem;">400 Points</p>
          </div>
          <button class="btn ">Redeem</button>
        </div>
         <div class="reward-item">
          <div class="reward-icon"></div>
          <div style="flex: 1;">
            <p><strong>Brew Member</strong></p>
            <p class="text-muted" style="font-size: 0.8rem;">500 Points</p>
          </div>
          <button class="btn ">Redeem</button>
        </div>
         <div class="reward-item">
          <div class="reward-icon">🔥</div>
          <div style="flex: 1;">
            <p><strong>Gold Reserve</strong></p>
            <p class="text-muted" style="font-size: 0.8rem;">2,000 Points</p>
          </div>
          <button class="btn ">Redeem</button>
        </div>
      </div>
    </div>

    <aside class="sidebar">
      <div class="card-panel reveal">
        <h4 style="margin-bottom: 16px;">Available Promos</h4>
        <div style="background: var(--surface-2); padding: 16px; border-radius: 12px; margin-bottom: 12px;">
          <p style="color: var(--accent); font-weight: bold;">Double Point Mondays!</p>
          <p style="font-size: 0.8rem;">Earn 2x points on all seasonal lattes every Monday.</p>
        </div>
      </div>
    </aside>
  </main>

  <footer>
    <div class="footer-inner">
      <div class="footer-brand-col">
        <div class="footer-brand-name">NestledBrew</div>
        <p class="footer-brand-desc">
          A sanctuary for coffee lovers and book enthusiasts in the heart of Cebu City. 
          Sourcing ethically, brewing passionately since 2019.
        </p>
        <div class="footer-socials">
          <a href="#" class="social-link" aria-label="Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="#" class="social-link" aria-label="Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Navigate</div>
        <ul class="footer-links">
          <li><a href="homepage.php">Home</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="rewards.php">Rewards</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Visit Us</div>
        <ul class="footer-links">
          <li><a href="#">123 Brew Street</a></li>
          <li><a href="#">Cebu City, PH</a></li>
          <li><a href="#">Mon–Fri: 7AM–8PM</a></li>
          <li><a href="#">Sat–Sun: 8AM–9PM</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Connect</div>
        <ul class="footer-links">
          <li><a href="#">Instagram</a></li>
          <li><a href="#">Facebook</a></li>
          <li><a href="mailto:hello@nestledbrew.com">hello@nestledbrew.com</a></li>
          <li><a href="#">+63 917 123 4567</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 NestledBrew. All Rights Reserved.</span>
      <span>Made with ☕ in Cebu City</span>
    </div>
  </footer>



  <script src="homepage.js"></script>
</body>
</html>
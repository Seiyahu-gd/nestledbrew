<?php session_start(); ?>
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
      <a href="homepage.php" class="nav-link active">Home</a>
      <a href="menu.php" class="nav-link">Menu</a>
      <a href="about.php" class="nav-link">About Us</a>
      <a href="rewards.php" class="nav-link">Rewards</a>
    </nav>

    <div class="nav-actions">
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-profile-group">
          <a href="rewards.php" class="nav-profile-link">
            <div class="profile-circle">
              <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
            </div>
            <span class="user-name-text"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          </a>
          <a href="logout.php" class="logout-btn">Logout</a>
        </div>
      <?php else: ?>
        <a href="login.html" class="nav-link">Sign In</a>
        <a href="login.html#signup" class="btn btn-primary">Join Now</a>
      <?php endif; ?>
    </div>

    <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

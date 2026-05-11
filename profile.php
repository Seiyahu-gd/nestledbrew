<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NestledBrew — My Profile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  <style>
    /* ===== PROFILE PAGE STYLES ===== */

    /* Page header */
    .profile-page-hero {
      padding: 140px 60px 60px;
      background: var(--hero-bg);
      position: relative;
      overflow: hidden;
    }
    .profile-page-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 50% 60% at 10% 50%, rgba(139,107,79,0.08) 0%, transparent 70%),
        radial-gradient(ellipse 40% 50% at 90% 30%, rgba(58,35,24,0.5) 0%, transparent 60%);
      pointer-events: none;
    }
    .profile-page-hero::after {
      content: '';
      position: absolute;
      inset: 0;
      opacity: 0.035;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
      background-size: 200px;
      pointer-events: none;
    }

    .profile-hero-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: flex-end;
      gap: 36px;
      position: relative;
      z-index: 1;
    }

    /* Avatar */
    .profile-avatar-wrap {
      position: relative;
      flex-shrink: 0;
    }
    .profile-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--surface-2), var(--accent));
      border: 3px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-display);
      font-size: 3rem;
      font-style: italic;
      font-weight: 600;
      color: #1A0E0A;
      box-shadow: 0 12px 40px var(--shadow);
      animation: animFadeUp 0.7s 0.2s both;
    }
    .profile-avatar-badge {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--surface);
      border: 2px solid var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      cursor: pointer;
      transition: all var(--transition);
    }
    .profile-avatar-badge:hover {
      background: var(--accent);
      transform: scale(1.1);
    }

    /* Hero meta */
    .profile-hero-meta {
      flex: 1;
      padding-bottom: 4px;
    }
    .profile-hero-eyebrow {
      font-family: var(--font-body);
      font-size: 0.76rem;
      font-weight: 500;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 8px;
      display: block;
      animation: animFadeUp 0.7s 0.3s both;
    }
    .profile-hero-name {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3vw, 2.8rem);
      font-weight: 600;
      color: var(--text);
      line-height: 1.1;
      margin-bottom: 8px;
      animation: animFadeUp 0.7s 0.4s both;
    }
    .profile-hero-name em {
      font-style: italic;
      color: var(--accent);
      font-weight: 400;
    }
    .profile-hero-bio {
      font-family: var(--font-body);
      font-size: 0.95rem;
      font-weight: 300;
      color: var(--text-muted);
      line-height: 1.65;
      max-width: 460px;
      animation: animFadeUp 0.7s 0.5s both;
    }
    .profile-hero-joined {
      font-family: var(--font-body);
      font-size: 0.78rem;
      font-weight: 400;
      letter-spacing: 0.08em;
      color: var(--text-dim);
      margin-top: 10px;
      animation: animFadeUp 0.7s 0.55s both;
    }

    /* Tier badge in hero */
    .profile-tier-badge {
      flex-shrink: 0;
      padding: 16px 24px;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      text-align: center;
      backdrop-filter: blur(8px);
      animation: animFadeUp 0.7s 0.6s both;
    }
    .profile-tier-icon { font-size: 1.8rem; margin-bottom: 6px; }
    .profile-tier-label {
      font-family: var(--font-body);
      font-size: 0.7rem;
      font-weight: 500;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--text-dim);
      margin-bottom: 2px;
    }
    .profile-tier-name {
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 600;
      font-style: italic;
      color: var(--accent);
    }

    /* ===== MAIN PROFILE BODY ===== */
    .profile-body {
      background: var(--bg-secondary);
      min-height: 60vh;
      padding: 60px 0 100px;
    }
    .profile-body-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 60px;
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 40px;
      align-items: start;
    }

    /* ===== PANEL CARDS ===== */
    .profile-panel {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      backdrop-filter: blur(8px);
    }
    .panel-header {
      padding: 22px 28px 18px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .panel-title {
      font-family: var(--font-display);
      font-size: 1.05rem;
      font-weight: 600;
      font-style: italic;
      color: var(--text);
    }
    .panel-eyebrow {
      font-family: var(--font-body);
      font-size: 0.68rem;
      font-weight: 500;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 2px;
    }
    .panel-body { padding: 28px; }

    /* ===== EDIT FORM ===== */
    .form-group {
      margin-bottom: 24px;
    }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
      display: block;
      font-family: var(--font-body);
      font-size: 0.75rem;
      font-weight: 500;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 8px;
    }
    .form-input,
    .form-textarea {
      width: 100%;
      padding: 12px 16px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-family: var(--font-body);
      font-size: 0.95rem;
      font-weight: 400;
      color: var(--text);
      transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
      outline: none;
      resize: none;
    }
    .form-input::placeholder,
    .form-textarea::placeholder {
      color: var(--text-dim);
    }
    .form-input:focus,
    .form-textarea:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px var(--accent-glow);
      background: var(--surface-2);
    }
    .form-textarea { height: 110px; line-height: 1.65; }
    .form-hint {
      display: block;
      font-family: var(--font-body);
      font-size: 0.76rem;
      font-weight: 300;
      color: var(--text-dim);
      margin-top: 6px;
    }
    .char-count {
      float: right;
      font-size: 0.74rem;
      color: var(--text-dim);
      transition: color var(--transition);
    }
    .char-count.warn { color: #C98A4E; }
    .char-count.over { color: #D9534F; }

    .form-divider {
      height: 1px;
      background: var(--border);
      margin: 28px 0;
    }

    .form-actions {
      display: flex;
      gap: 12px;
      align-items: center;
      padding-top: 4px;
    }
    .save-status {
      font-family: var(--font-body);
      font-size: 0.82rem;
      font-weight: 400;
      color: var(--accent);
      opacity: 0;
      transition: opacity 0.4s ease;
    }
    .save-status.show { opacity: 1; }

    /* Password field row */
    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    /* ===== RIGHT SIDEBAR ===== */
    .sidebar-stack {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    /* Rewards card */
    .rewards-card-profile {
      width: 100%;
      background: linear-gradient(135deg, var(--surface-2) 0%, var(--surface) 60%, #5A3020 100%);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 30px 28px 26px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 24px 64px var(--shadow-lg);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .rewards-card-profile:hover {
      transform: translateY(-4px) rotate(0.5deg);
      box-shadow: 0 36px 80px var(--shadow-lg);
    }
    .rewards-card-shimmer {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.05) 50%, transparent 60%);
      pointer-events: none;
    }
    .rc-brand {
      font-family: var(--font-display);
      font-size: 0.95rem;
      font-weight: 600;
      font-style: italic;
      color: var(--accent-light);
      margin-bottom: 28px;
      opacity: 0.85;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .rc-chip {
      font-family: var(--font-body);
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      background: var(--accent-glow);
      border: 1px solid rgba(139,107,79,0.3);
      color: var(--accent-light);
      border-radius: 100px;
      padding: 3px 9px;
    }
    .rc-points {
      font-family: var(--font-display);
      font-size: 3.4rem;
      font-weight: 600;
      color: var(--text);
      line-height: 1;
      margin-bottom: 4px;
    }
    .rc-points-label {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 400;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 22px;
    }
    .rc-progress-wrap {
      margin-bottom: 8px;
    }
    .rc-progress-labels {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }
    .rc-progress-tier {
      font-family: var(--font-body);
      font-size: 0.74rem;
      font-weight: 500;
      color: var(--text-muted);
    }
    .rc-progress-next {
      font-family: var(--font-body);
      font-size: 0.7rem;
      color: var(--text-dim);
    }
    .rc-progress-bar {
      height: 4px;
      background: var(--border);
      border-radius: 2px;
      overflow: hidden;
      margin-bottom: 6px;
    }
    .rc-progress-fill {
      height: 100%;
      border-radius: 2px;
      background: linear-gradient(to right, var(--accent), var(--accent-light));
      transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .rc-progress-hint {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 300;
      color: var(--text-dim);
    }
    .rc-name {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 500;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--text-dim);
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid rgba(220,203,178,0.1);
    }

    /* Points history mini list */
    .points-history {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .points-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
      transition: background var(--transition);
    }
    .points-row:last-child { border-bottom: none; }
    .points-row-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .points-icon {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: var(--surface);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      flex-shrink: 0;
    }
    .points-label {
      font-family: var(--font-body);
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text);
    }
    .points-date {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 300;
      color: var(--text-dim);
      margin-top: 1px;
    }
    .points-val {
      font-family: var(--font-display);
      font-size: 0.95rem;
      font-weight: 600;
      font-style: italic;
    }
    .points-val.earn { color: var(--accent); }
    .points-val.redeem { color: var(--text-dim); }

    /* Tier benefits */
    .benefits-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .benefit-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-family: var(--font-body);
      font-size: 0.85rem;
      font-weight: 400;
      color: var(--text-muted);
      transition: border-color var(--transition), transform var(--transition);
    }
    .benefit-item:hover {
      border-color: var(--accent);
      transform: translateX(3px);
    }
    .benefit-check {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: var(--accent-glow);
      border: 1px solid var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.65rem;
      color: var(--accent);
      flex-shrink: 0;
    }

    /* ===== DANGER ZONE ===== */
    .danger-zone {
      border-color: rgba(217, 83, 79, 0.2);
    }
    .danger-zone .panel-header {
      border-color: rgba(217, 83, 79, 0.15);
    }
    .danger-zone .panel-eyebrow {
      color: #D9534F;
    }
    .btn-danger {
      background: transparent;
      border-color: rgba(217, 83, 79, 0.4);
      color: rgba(217, 83, 79, 0.7);
      font-size: 0.8rem;
      padding: 8px 16px;
    }
    .btn-danger:hover {
      background: rgba(217, 83, 79, 0.08);
      border-color: #D9534F;
      color: #D9534F;
      transform: none;
      box-shadow: none;
    }

    /* ===== AVATAR INITIAL ===== */
    .big-initial {
      display: inline-block;
      font-family: var(--font-display);
      font-style: italic;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
      .profile-body-inner { padding: 0 40px; }
      .profile-page-hero { padding: 140px 40px 60px; }
    }
    @media (max-width: 860px) {
      .profile-page-hero { padding: 110px 24px 48px; }
      .profile-hero-inner { flex-direction: column; align-items: flex-start; gap: 20px; }
      .profile-tier-badge { align-self: flex-start; }
      .profile-body-inner {
        grid-template-columns: 1fr;
        padding: 0 24px;
        gap: 28px;
      }
      .field-row { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
      .profile-avatar { width: 82px; height: 82px; font-size: 2.2rem; }
      .profile-hero-name { font-size: 1.8rem; }
    }
  </style>
</head>

<body>

  <!-- Page Loader -->
  <div class="page-loader" id="pageLoader">
    <div class="loader-inner">
      <div class="loader-logo">N</div>
      <div class="loader-bar"><div class="loader-fill"></div></div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast" id="globalToast"></div>

  <!-- ===== HEADER / NAVBAR ===== -->
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
        <a href="rewards.php" class="nav-link">Rewards</a>
      </nav>

      <div class="nav-actions">
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

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="homepage.php" class="mobile-link">Home</a>
    <a href="menu.php" class="mobile-link">Menu</a>
    <a href="about.php" class="mobile-link">About Us</a>
    <a href="rewards.php" class="mobile-link">Rewards</a>
    <div class="mobile-actions">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="btn">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn">Sign In</a>
        <a href="login.php#signup" class="btn btn-primary">Join Now</a>
      <?php endif; ?>
    </div>
  </div>


  <!-- ===== PROFILE HERO ===== -->
  <section class="profile-page-hero">
    <div class="profile-hero-inner">

      <!-- Avatar -->
      <div class="profile-avatar-wrap">
        <div class="profile-avatar" id="heroAvatar">
          <span class="big-initial" id="heroInitial">
            <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'G'; ?>
          </span>
        </div>
        <div class="profile-avatar-badge" title="Change avatar color" onclick="cycleAvatarColor()">✏️</div>
      </div>

      <!-- Meta -->
      <div class="profile-hero-meta">
        <span class="profile-hero-eyebrow">My Profile</span>
        <h1 class="profile-hero-name" id="heroName">
          <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>
          <em id="heroNameAccent"></em>
        </h1>
        <p class="profile-hero-bio" id="heroBio">
          <?php echo isset($_SESSION['user_bio']) ? htmlspecialchars($_SESSION['user_bio']) : 'Coffee lover & NestledBrew regular. Nothing beats a good book and a warm cortado.'; ?>
        </p>
        <p class="profile-hero-joined">
          ☕ Member since <?php echo isset($_SESSION['user_since']) ? htmlspecialchars($_SESSION['user_since']) : '2024'; ?>
          &nbsp;·&nbsp;
          <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'member@nestledbrew.com'; ?>
        </p>
      </div>

      <!-- Tier badge -->
      <div class="profile-tier-badge">
        <div class="profile-tier-icon" id="heroTierIcon">✨</div>
        <div class="profile-tier-label">Current Tier</div>
        <div class="profile-tier-name" id="heroTierName">Brew Member</div>
      </div>

    </div>
  </section>


  <!-- ===== PROFILE BODY ===== -->
  <section class="profile-body">
    <div class="profile-body-inner">

      <!-- LEFT: Edit Forms -->
      <div class="left-col" style="display:flex;flex-direction:column;gap:28px;">

        <!-- Edit Profile Panel -->
        <div class="profile-panel reveal">
          <div class="panel-header">
            <div>
              <div class="panel-eyebrow">Account</div>
              <div class="panel-title">Edit Profile</div>
            </div>
          </div>
          <div class="panel-body">

            <!-- Display Name -->
            <div class="form-group">
              <label class="form-label" for="inputName">Display Name</label>
              <input
                class="form-input"
                type="text"
                id="inputName"
                placeholder="Your display name"
                maxlength="40"
                value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                oninput="syncHeroName(this.value); updateCharCount('inputName','nameCount',40)"
              >
              <span class="form-hint">
                <span class="char-count" id="nameCount">0 / 40</span>
                This is how your name appears across NestledBrew.
              </span>
            </div>

            <!-- Bio -->
            <div class="form-group">
              <label class="form-label" for="inputBio">Bio</label>
              <textarea
                class="form-textarea"
                id="inputBio"
                placeholder="Tell us a little about yourself…"
                maxlength="160"
                oninput="syncHeroBio(this.value); updateCharCount('inputBio','bioCount',160)"
              ><?php echo isset($_SESSION['user_bio']) ? htmlspecialchars($_SESSION['user_bio']) : 'Coffee lover & NestledBrew regular. Nothing beats a good book and a warm cortado.'; ?></textarea>
              <span class="form-hint">
                <span class="char-count" id="bioCount">0 / 160</span>
                A short intro shown on your profile.
              </span>
            </div>

            <div class="form-actions">
              <button class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
              <button class="btn" onclick="resetProfile()">Discard</button>
              <span class="save-status" id="saveStatus">✓ Changes saved</span>
            </div>
          </div>
        </div>

        <!-- Change Password Panel -->
        <div class="profile-panel reveal reveal-delay-1">
          <div class="panel-header">
            <div>
              <div class="panel-eyebrow">Security</div>
              <div class="panel-title">Change Password</div>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group">
              <label class="form-label" for="inputCurrentPw">Current Password</label>
              <input class="form-input" type="password" id="inputCurrentPw" placeholder="Enter current password">
            </div>
            <div class="field-row">
              <div class="form-group">
                <label class="form-label" for="inputNewPw">New Password</label>
                <input class="form-input" type="password" id="inputNewPw" placeholder="New password" oninput="checkPwStrength(this.value)">
                <span class="form-hint" id="pwStrengthHint"></span>
              </div>
              <div class="form-group">
                <label class="form-label" for="inputConfirmPw">Confirm Password</label>
                <input class="form-input" type="password" id="inputConfirmPw" placeholder="Repeat new password">
              </div>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary" onclick="changePassword()">Update Password</button>
              <span class="save-status" id="pwStatus"></span>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="profile-panel danger-zone reveal reveal-delay-2">
          <div class="panel-header">
            <div>
              <div class="panel-eyebrow">Danger Zone</div>
              <div class="panel-title">Account Actions</div>
            </div>
          </div>
          <div class="panel-body">
            <p style="font-family:var(--font-body);font-size:0.88rem;font-weight:300;color:var(--text-muted);line-height:1.65;margin-bottom:20px;">
              Permanently deleting your account will remove all your data, BrewPoints, and rewards history. This cannot be undone.
            </p>
            <button class="btn btn-danger" onclick="confirmDelete()">Delete My Account</button>
          </div>
        </div>

      </div><!-- /left-col -->

      <!-- RIGHT: Sidebar -->
      <div class="sidebar-stack">

        <!-- Rewards Card -->
        <div class="rewards-card-profile reveal">
          <div class="rewards-card-shimmer"></div>
          <div class="rc-brand">
            NestledBrew
            <span class="rc-chip" id="sidebarTierChip">Brew Member</span>
          </div>
          <div class="rc-points" id="sidebarPoints">
            <?php echo isset($_SESSION['user_points']) ? number_format($_SESSION['user_points']) : '0'; ?>
          </div>
          <div class="rc-points-label">BrewPoints</div>

          <div class="rc-progress-wrap">
            <div class="rc-progress-labels">
              <span class="rc-progress-tier" id="progressTier">Brew Member</span>
              <span class="rc-progress-next" id="progressNextLabel">1,650 pts to Gold Reserve</span>
            </div>
            <div class="rc-progress-bar">
              <div class="rc-progress-fill" id="progressFill" style="width: 0%"></div>
            </div>
            <div class="rc-progress-hint" id="progressHint">500 – 2,000 pts</div>
          </div>

          <div class="rc-name" id="sidebarName">
            <?php echo isset($_SESSION['user_name']) ? strtoupper(htmlspecialchars($_SESSION['user_name'])) : 'GUEST'; ?>
          </div>
        </div>

        <!-- Points History -->
        <div class="profile-panel reveal reveal-delay-1">
          <div class="panel-header">
            <div>
              <div class="panel-eyebrow">Activity</div>
              <div class="panel-title">Points History</div>
            </div>
            <a href="rewards.php" class="btn" style="padding:7px 14px;font-size:0.72rem;">View All</a>
          </div>
          <div class="panel-body" style="padding-top:8px;padding-bottom:8px;">
            <div class="points-history">
              <div class="points-row">
                <div class="points-row-left">
                  <div class="points-icon">☕</div>
                  <div>
                    <div class="points-label">Caramel Macchiato</div>
                    <div class="points-date">Today, 2:14 PM</div>
                  </div>
                </div>
                <div class="points-val earn">+15 pts</div>
              </div>
              <div class="points-row">
                <div class="points-row-left">
                  <div class="points-icon">🍮</div>
                  <div>
                    <div class="points-label">Brown Sugar Cold Brew</div>
                    <div class="points-date">Yesterday, 10:45 AM</div>
                  </div>
                </div>
                <div class="points-val earn">+18 pts</div>
              </div>
              <div class="points-row">
                <div class="points-row-left">
                  <div class="points-icon">🎁</div>
                  <div>
                    <div class="points-label">Free Drink Redeemed</div>
                    <div class="points-date">May 8, 3:00 PM</div>
                  </div>
                </div>
                <div class="points-val redeem">−150 pts</div>
              </div>
              <div class="points-row">
                <div class="points-row-left">
                  <div class="points-icon">✨</div>
                  <div>
                    <div class="points-label">Ube Honey Latte</div>
                    <div class="points-date">May 7, 9:12 AM</div>
                  </div>
                </div>
                <div class="points-val earn">+17 pts</div>
              </div>
              <div class="points-row">
                <div class="points-row-left">
                  <div class="points-icon">🎂</div>
                  <div>
                    <div class="points-label">Birthday Bonus</div>
                    <div class="points-date">May 1</div>
                  </div>
                </div>
                <div class="points-val earn">+100 pts</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Current Tier Benefits -->
        <div class="profile-panel reveal reveal-delay-2">
          <div class="panel-header">
            <div>
              <div class="panel-eyebrow">Your Perks</div>
              <div class="panel-title">Tier Benefits</div>
            </div>
          </div>
          <div class="panel-body">
            <div class="benefits-list">
              <div class="benefit-item">
                <div class="benefit-check">✓</div>
                10% off every order
              </div>
              <div class="benefit-item">
                <div class="benefit-check">✓</div>
                Monthly free drink
              </div>
              <div class="benefit-item">
                <div class="benefit-check">✓</div>
                Free birthday drink
              </div>
              <div class="benefit-item">
                <div class="benefit-check">✓</div>
                Priority queuing
              </div>
            </div>
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--border);">
              <p style="font-family:var(--font-body);font-size:0.8rem;font-weight:300;color:var(--text-dim);line-height:1.6;">
                Reach <strong style="color:var(--accent);font-weight:600;">Gold Reserve</strong> at 2,000 pts to unlock 20% off, early menu access, and free delivery.
              </p>
            </div>
          </div>
        </div>

      </div><!-- /sidebar-stack -->
    </div><!-- /profile-body-inner -->
  </section>


  <!-- ===== FOOTER ===== -->
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
  <script>
    /* ===== PROFILE PAGE SCRIPT ===== */

    // Avatar color palette (cycles on ✏️ click)
    const avatarGradients = [
      'linear-gradient(135deg, var(--surface-2), var(--accent))',
      'linear-gradient(135deg, #2E4A3E, #7A8B6F)',
      'linear-gradient(135deg, #3A2B4A, #8B6FAF)',
      'linear-gradient(135deg, #4A3020, #C98A4E)',
      'linear-gradient(135deg, #1E3040, #4A90D9)',
    ];
    let avatarColorIdx = 0;
    function cycleAvatarColor() {
      avatarColorIdx = (avatarColorIdx + 1) % avatarGradients.length;
      document.getElementById('heroAvatar').style.background = avatarGradients[avatarColorIdx];
    }

    // Tier logic
    function getTier(pts) {
      if (pts >= 2000) return { name: 'Gold Reserve',  icon: '🔥', min: 2000, max: 5000 };
      if (pts >= 500)  return { name: 'Brew Member',   icon: '✨', min: 500,  max: 2000 };
      return               { name: 'Bean Starter',  icon: '🙌', min: 0,    max: 500  };
    }

    function initPointsUI() {
      const pts = parseInt('<?php echo isset($_SESSION["user_points"]) ? (int)$_SESSION["user_points"] : 350; ?>') || 350;
      const tier = getTier(pts);
      const nextTierPts = tier.max;
      const pct = Math.min(((pts - tier.min) / (tier.max - tier.min)) * 100, 100);
      const remaining = Math.max(nextTierPts - pts, 0);

      document.getElementById('heroTierIcon').textContent    = tier.icon;
      document.getElementById('heroTierName').textContent    = tier.name;
      document.getElementById('sidebarTierChip').textContent = tier.name;
      document.getElementById('progressTier').textContent    = tier.name;
      document.getElementById('progressHint').textContent    = `${tier.min.toLocaleString()} – ${tier.max.toLocaleString()} pts`;

      if (tier.name === 'Gold Reserve') {
        document.getElementById('progressNextLabel').textContent = '🏆 Maximum tier reached!';
      } else {
        const nextTier = getTier(tier.max);
        document.getElementById('progressNextLabel').textContent =
          `${remaining.toLocaleString()} pts to ${nextTier.name}`;
      }

      // Animate progress bar after small delay
      setTimeout(() => {
        document.getElementById('progressFill').style.width = pct + '%';
      }, 400);
    }

    // Live hero sync
    function syncHeroName(val) {
      const el = document.getElementById('heroName');
      el.childNodes[0].textContent = val || 'Your Name';
    }
    function syncHeroBio(val) {
      document.getElementById('heroBio').textContent = val || '';
    }

    // Character counter
    function updateCharCount(inputId, countId, max) {
      const val = document.getElementById(inputId).value.length;
      const el  = document.getElementById(countId);
      el.textContent = `${val} / ${max}`;
      el.className = 'char-count' + (val > max * 0.9 ? ' warn' : '') + (val >= max ? ' over' : '');
    }

    // Save profile (POST to update_profile.php)
    async function saveProfile() {
      const name = document.getElementById('inputName').value.trim();
      const bio  = document.getElementById('inputBio').value.trim();

      if (!name) {
        showToast('Display name cannot be empty.');
        return;
      }

      const status = document.getElementById('saveStatus');
      status.classList.remove('show');

      try {
        const fd = new FormData();
        fd.append('name', name);
        fd.append('bio', bio);

        const res = await fetch('update_profile.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result && result.success) {
          status.classList.add('show');
          setTimeout(() => status.classList.remove('show'), 3000);

          showToast('✓ Profile updated successfully!');

          // Sync sidebar name
          document.getElementById('sidebarName').textContent = name.toUpperCase();
          document.getElementById('heroInitial').textContent = name.charAt(0).toUpperCase();
        } else {
          showToast(result?.message || 'Could not save changes.');
        }
      } catch (e) {
        console.error(e);
        showToast('Connection error while saving profile.');
      }
    }

    function resetProfile() {
      document.getElementById('inputName').value = '<?php echo isset($_SESSION["user_name"]) ? addslashes(htmlspecialchars($_SESSION["user_name"])) : ""; ?>';
      document.getElementById('inputBio').value  = '<?php echo isset($_SESSION["user_bio"])  ? addslashes(htmlspecialchars($_SESSION["user_bio"]))  : "Coffee lover & NestledBrew regular. Nothing beats a good book and a warm cortado."; ?>';
      updateCharCount('inputName', 'nameCount', 40);
      updateCharCount('inputBio',  'bioCount',  160);
      showToast('Changes discarded.');

      // Reset hero mirror
      syncHeroName(document.getElementById('inputName').value);
      syncHeroBio(document.getElementById('inputBio').value);
    }


    // Password strength hint
    function checkPwStrength(val) {
      const hint = document.getElementById('pwStrengthHint');
      if (!val) { hint.textContent = ''; return; }
      if (val.length < 6)  { hint.textContent = '⚠ Too short'; hint.style.color = '#D9534F'; return; }
      if (val.length < 10) { hint.textContent = '○ Fair';     hint.style.color = '#C98A4E'; return; }
      hint.textContent = '● Strong'; hint.style.color = 'var(--accent-2)';
    }

    async function changePassword() {
      const cur  = document.getElementById('inputCurrentPw').value;
      const nw   = document.getElementById('inputNewPw').value;
      const conf = document.getElementById('inputConfirmPw').value;
      const st   = document.getElementById('pwStatus');

      if (!cur || !nw || !conf) { showToast('Please fill all password fields.'); return; }
      if (nw !== conf)           { showToast('New passwords do not match.');      return; }
      if (nw.length < 6)         { showToast('Password must be at least 6 characters.'); return; }

      st.textContent = 'Updating…';
      st.classList.remove('show');

      try {
        const fd = new FormData();
        fd.append('current_password', cur);
        fd.append('new_password', nw);
        fd.append('confirm_password', conf);

        const res = await fetch('change_password.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result && result.success) {
          st.textContent = '✓ Password updated';
          st.classList.add('show');
          setTimeout(() => st.classList.remove('show'), 3000);
          showToast('✓ Password changed successfully!');

          document.getElementById('inputCurrentPw').value = '';
          document.getElementById('inputNewPw').value     = '';
          document.getElementById('inputConfirmPw').value = '';
        } else {
          showToast(result?.message || 'Could not update password.');
          st.textContent = '';
        }
      } catch (e) {
        console.error(e);
        showToast('Connection error while updating password.');
      }
    }

    async function confirmDelete() {
      if (!confirm('Are you sure you want to permanently delete your account? This cannot be undone.')) return;

      showToast('Account deletion requested…');

      try {
        const res = await fetch('delete_account.php', { method: 'POST' });
        const result = await res.json();

        if (result && result.success) {
          showToast('Account deleted. Redirecting…');
          setTimeout(() => { window.location.href = 'homepage.php'; }, 1500);
        } else {
          showToast(result?.message || 'Could not delete account.');
        }
      } catch (e) {
        console.error(e);
        showToast('Connection error while deleting account.');
      }
    }


    // Init on load
    document.addEventListener('DOMContentLoaded', () => {
      initPointsUI();
      updateCharCount('inputName', 'nameCount', 40);
      updateCharCount('inputBio',  'bioCount',  160);
    });

    // Override toggleMode to keep map compatibility
    function toggleMode() {
      const isLight = document.body.classList.contains('light-mode');
      applyMode(!isLight);
    }
  </script>
</body>
</html>
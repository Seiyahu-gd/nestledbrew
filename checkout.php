<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NestledBrew — Checkout</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  <style>
    /* ===== CHECKOUT PAGE STYLES ===== */

    .checkout-hero {
      padding: 130px 60px 48px;
      background: var(--hero-bg);
      position: relative;
      overflow: hidden;
    }
    .checkout-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 50% 60% at 10% 50%, rgba(139,107,79,0.07) 0%, transparent 70%),
        radial-gradient(ellipse 40% 50% at 90% 30%, rgba(58,35,24,0.45) 0%, transparent 60%);
      pointer-events: none;
    }
    .checkout-hero-inner {
      max-width: 1100px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }
    .checkout-eyebrow {
      font-family: var(--font-body);
      font-size: 0.74rem;
      font-weight: 500;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: animFadeUp 0.6s 0.1s both;
    }
    .checkout-eyebrow::before {
      content: '';
      display: inline-block;
      width: 28px;
      height: 1px;
      background: var(--accent);
      opacity: 0.6;
    }
    .checkout-hero h1 {
      font-family: var(--font-display);
      font-size: clamp(2rem, 3.5vw, 3rem);
      font-weight: 600;
      color: var(--text);
      line-height: 1.1;
      animation: animFadeUp 0.6s 0.2s both;
    }
    .checkout-hero h1 em {
      font-style: italic;
      color: var(--accent);
      font-weight: 400;
    }

    /* Steps indicator */
    .checkout-steps {
      display: flex;
      align-items: center;
      gap: 0;
      margin-top: 28px;
      animation: animFadeUp 0.6s 0.3s both;
    }
    .step-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .step-dot {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 1.5px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 500;
      color: var(--text-dim);
      background: var(--surface);
      transition: all 0.3s ease;
      flex-shrink: 0;
    }
    .step-dot.active {
      border-color: var(--accent);
      background: var(--accent);
      color: #1A0E0A;
      font-weight: 600;
    }
    .step-dot.done {
      border-color: var(--accent);
      background: var(--accent-glow);
      color: var(--accent);
    }
    .step-label {
      font-family: var(--font-body);
      font-size: 0.76rem;
      font-weight: 400;
      color: var(--text-dim);
      white-space: nowrap;
    }
    .step-label.active { color: var(--text); font-weight: 500; }
    .step-line {
      flex: 1;
      height: 1px;
      background: var(--border);
      margin: 0 12px;
      min-width: 32px;
    }

    /* ===== MAIN LAYOUT ===== */
    .checkout-body {
      background: var(--bg-secondary);
      padding: 48px 0 100px;
      min-height: 60vh;
    }
    .checkout-body-inner {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 60px;
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 36px;
      align-items: start;
    }

    /* ===== PANEL ===== */
    .co-panel {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      backdrop-filter: blur(8px);
      margin-bottom: 24px;
    }
    .co-panel:last-child { margin-bottom: 0; }
    .co-panel-header {
      padding: 20px 26px 16px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .co-panel-num {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: var(--accent-glow);
      border: 1px solid var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--accent);
      flex-shrink: 0;
      margin-right: 10px;
    }
    .co-panel-title {
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 600;
      font-style: italic;
      color: var(--text);
      display: flex;
      align-items: center;
    }
    .co-panel-eyebrow {
      font-family: var(--font-body);
      font-size: 0.66rem;
      font-weight: 500;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 2px;
    }
    .co-panel-body { padding: 24px 26px; }

    /* ===== FORM ELEMENTS ===== */
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 18px;
    }
    .form-row.single { grid-template-columns: 1fr; }
    .form-row.thirds { grid-template-columns: 1fr 1fr 1fr; }
    .form-group { display: flex; flex-direction: column; }
    .form-label {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 500;
      letter-spacing: 0.11em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 7px;
    }
    .form-label .req { color: var(--accent); margin-left: 2px; }
    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 11px 14px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-family: var(--font-body);
      font-size: 0.92rem;
      font-weight: 400;
      color: var(--text);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      box-sizing: border-box;
    }
    .form-input::placeholder, .form-textarea::placeholder { color: var(--text-dim); }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px var(--accent-glow);
      background: var(--surface-2);
    }
    .form-select { cursor: pointer; appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238B6B4F' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 36px;
    }
    .form-textarea { height: 90px; resize: none; line-height: 1.6; }

    /* ===== ORDER TYPE SELECTOR ===== */
    .order-type-group {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 0;
    }
    .order-type-option { display: none; }
    .order-type-label {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      padding: 16px 12px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius);
      cursor: pointer;
      background: var(--surface);
      transition: all 0.2s ease;
      text-align: center;
    }
    .order-type-label:hover {
      border-color: var(--accent);
      background: var(--accent-glow);
    }
    .order-type-option:checked + .order-type-label {
      border-color: var(--accent);
      background: var(--accent-glow);
      box-shadow: 0 0 0 3px var(--accent-glow);
    }
    .ot-icon { font-size: 1.5rem; }
    .ot-name {
      font-family: var(--font-body);
      font-size: 0.8rem;
      font-weight: 500;
      color: var(--text);
    }
    .ot-desc {
      font-family: var(--font-body);
      font-size: 0.7rem;
      font-weight: 300;
      color: var(--text-dim);
      line-height: 1.4;
    }

    /* Delivery fields (shown only when delivery is selected) */
    #deliveryFields { display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border); }
    #deliveryFields.visible { display: block; }

    /* ===== PAYMENT METHOD ===== */
    .payment-options {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    .payment-option { display: none; }
    .payment-label {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius);
      cursor: pointer;
      background: var(--surface);
      transition: all 0.2s ease;
    }
    .payment-label:hover {
      border-color: var(--accent);
      background: var(--accent-glow);
    }
    .payment-option:checked + .payment-label {
      border-color: var(--accent);
      background: var(--accent-glow);
      box-shadow: 0 0 0 3px var(--accent-glow);
    }
    .payment-icon { font-size: 1.3rem; flex-shrink: 0; }
    .payment-name {
      font-family: var(--font-body);
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text);
    }
    .payment-sub {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 300;
      color: var(--text-dim);
    }

    /* E-wallet / card fields */
    #ewalletFields, #cardFields { display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border); }
    #ewalletFields.visible, #cardFields.visible { display: block; }

    /* Card number formatting */
    .card-number-wrap { position: relative; }
    .card-number-wrap .card-brand {
      position: absolute;
      right: 14px; top: 50%;
      transform: translateY(-50%);
      font-size: 1.1rem;
      pointer-events: none;
    }

    /* ===== RIGHT SIDEBAR ===== */
    .order-summary-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      position: sticky;
      top: 100px;
    }
    .osc-header {
      padding: 20px 24px 16px;
      border-bottom: 1px solid var(--border);
    }
    .osc-title {
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 600;
      font-style: italic;
      color: var(--text);
    }
    .osc-body { padding: 20px 24px; }

    /* Items list */
    .osc-items { margin-bottom: 20px; }
    .osc-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }
    .osc-item:last-child { border-bottom: none; }
    .osc-item-img {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      object-fit: cover;
      background: var(--surface);
      flex-shrink: 0;
    }
    .osc-item-info { flex: 1; min-width: 0; }
    .osc-item-name {
      font-family: var(--font-body);
      font-size: 0.84rem;
      font-weight: 500;
      color: var(--text);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .osc-item-qty {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 300;
      color: var(--text-dim);
    }
    .osc-item-price {
      font-family: var(--font-display);
      font-size: 0.9rem;
      font-weight: 600;
      font-style: italic;
      color: var(--accent);
      flex-shrink: 0;
    }

    /* Loading skeleton for items */
    .osc-skeleton {
      height: 42px;
      background: var(--surface);
      border-radius: 8px;
      margin-bottom: 10px;
      animation: skeletonPulse 1.4s ease-in-out infinite;
    }
    @keyframes skeletonPulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.4; }
    }

    /* Summary rows */
    .summary-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 0;
      font-family: var(--font-body);
      font-size: 0.85rem;
      color: var(--text-muted);
    }
    .summary-line.total {
      font-family: var(--font-display);
      font-size: 1.1rem;
      font-weight: 600;
      font-style: italic;
      color: var(--text);
      padding-top: 12px;
      margin-top: 6px;
      border-top: 1px solid var(--border);
    }
    .summary-line.discount { color: var(--accent); }
    .summary-divider { height: 1px; background: var(--border); margin: 8px 0; }

    /* Points badge on sidebar */
    .points-applied-badge {
      display: none;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      background: var(--accent-glow);
      border: 1px solid rgba(139,107,79,0.25);
      border-radius: var(--radius);
      margin-bottom: 14px;
    }
    .points-applied-badge.visible { display: flex; }
    .pab-icon { font-size: 1rem; }
    .pab-text {
      font-family: var(--font-body);
      font-size: 0.78rem;
      font-weight: 400;
      color: var(--text-muted);
      line-height: 1.4;
    }
    .pab-text strong { color: var(--accent); font-weight: 600; }

    /* Place order button */
    .place-order-btn {
      width: 100%;
      margin-top: 20px;
      padding: 14px;
      font-size: 0.95rem;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }
    .place-order-btn .btn-spinner {
      display: none;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(26,14,10,0.3);
      border-top-color: #1A0E0A;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      margin-right: 8px;
    }
    .place-order-btn.loading .btn-spinner { display: inline-block; }
    .place-order-btn.loading .btn-text { opacity: 0.7; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Secure badge */
    .secure-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-top: 12px;
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 300;
      color: var(--text-dim);
    }

    /* ===== SUCCESS OVERLAY ===== */
    .order-success-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(10,6,4,0.85);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(6px);
      animation: fadeIn 0.4s ease;
    }
    .order-success-overlay.show { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .success-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 48px 40px;
      text-align: center;
      max-width: 440px;
      width: 90%;
      animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .success-icon { font-size: 3rem; margin-bottom: 20px; }
    .success-title {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 600;
      font-style: italic;
      color: var(--text);
      margin-bottom: 10px;
    }
    .success-title em { color: var(--accent); font-weight: 400; }
    .success-desc {
      font-family: var(--font-body);
      font-size: 0.9rem;
      font-weight: 300;
      color: var(--text-muted);
      line-height: 1.65;
      margin-bottom: 28px;
    }
    .success-order-num {
      font-family: var(--font-display);
      font-size: 1rem;
      font-style: italic;
      color: var(--accent);
      margin-bottom: 28px;
      padding: 12px 20px;
      background: var(--accent-glow);
      border: 1px solid rgba(139,107,79,0.2);
      border-radius: var(--radius);
      display: inline-block;
    }
    .success-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
      .checkout-body-inner { padding: 0 40px; }
      .checkout-hero { padding: 130px 40px 48px; }
    }
    @media (max-width: 860px) {
      .checkout-hero { padding: 110px 24px 40px; }
      .checkout-body-inner {
        grid-template-columns: 1fr;
        padding: 0 24px;
        gap: 24px;
      }
      .order-summary-card { position: static; }
      .checkout-steps { flex-wrap: wrap; gap: 8px; }
      .step-line { display: none; }
    }
    @media (max-width: 560px) {
      .order-type-group { grid-template-columns: 1fr; }
      .payment-options { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .form-row.thirds { grid-template-columns: 1fr; }
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

  <!-- ===== ORDER SUCCESS OVERLAY ===== -->
  <div class="order-success-overlay" id="successOverlay">
    <div class="success-card">
      <div class="success-icon">☕</div>
      <h2 class="success-title">Order <em>Placed!</em></h2>
      <p class="success-desc">
        Your order has been received and is being prepared with care.
        We'll have it ready for you shortly.
      </p>
      <div class="success-order-num" id="successOrderNum"># NB-000000</div>
      <div class="success-actions">
        <a href="homepage.php" class="btn btn-primary">Back to Home</a>
        <a href="menu.php" class="btn">Order Again</a>
      </div>
    </div>
  </div>

  <!-- ===== HEADER ===== -->
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
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </div>

  <!-- ===== HERO ===== -->
  <section class="checkout-hero">
    <div class="checkout-hero-inner">
      <div class="checkout-eyebrow">Checkout</div>
      <h1>Almost <em>there.</em></h1>

      <!-- Step indicator -->
      <div class="checkout-steps">
        <div class="step-item">
          <div class="step-dot done">✓</div>
          <span class="step-label">Cart</span>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
          <div class="step-dot active">2</div>
          <span class="step-label active">Checkout</span>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
          <div class="step-dot">3</div>
          <span class="step-label">Confirmation</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CHECKOUT BODY ===== -->
  <section class="checkout-body">
    <div class="checkout-body-inner">

      <!-- LEFT: Forms -->
      <div class="left-col">

        <!-- 1. Order Type -->
        <div class="co-panel reveal">
          <div class="co-panel-header">
            <div class="co-panel-title">
              <div class="co-panel-num">1</div>
              <div>
                <div class="co-panel-eyebrow">How</div>
                Order Type
              </div>
            </div>
          </div>
          <div class="co-panel-body">
            <div class="order-type-group">

              <div>
                <input type="radio" class="order-type-option" name="orderType" id="typeTakeout" value="takeout" checked>
                <label class="order-type-label" for="typeTakeout">
                  <span class="ot-icon">🥡</span>
                  <span class="ot-name">Takeout</span>
                  <span class="ot-desc">Pick up at the counter</span>
                </label>
              </div>

              <div>
                <input type="radio" class="order-type-option" name="orderType" id="typeDinein" value="dinein">
                <label class="order-type-label" for="typeDinein">
                  <span class="ot-icon">☕</span>
                  <span class="ot-name">Dine-in</span>
                  <span class="ot-desc">Enjoy at our café</span>
                </label>
              </div>

              <div>
                <input type="radio" class="order-type-option" name="orderType" id="typeDelivery" value="delivery">
                <label class="order-type-label" for="typeDelivery">
                  <span class="ot-icon">🛵</span>
                  <span class="ot-name">Delivery</span>
                  <span class="ot-desc">Delivered to your door</span>
                </label>
              </div>

            </div>

            <!-- Dine-in: table number -->
            <div id="dineinFields" style="display:none;margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="tableNumber">Table Number</label>
                  <input class="form-input" type="text" id="tableNumber" placeholder="e.g. Table 4">
                </div>
              </div>
            </div>

            <!-- Delivery address fields -->
            <div id="deliveryFields">
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="deliveryAddress">Delivery Address <span class="req">*</span></label>
                  <input class="form-input" type="text" id="deliveryAddress" placeholder="House/Unit No., Street">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="deliveryBarangay">Barangay <span class="req">*</span></label>
                  <input class="form-input" type="text" id="deliveryBarangay" placeholder="Barangay">
                </div>
                <div class="form-group">
                  <label class="form-label" for="deliveryCity">City <span class="req">*</span></label>
                  <input class="form-input" type="text" id="deliveryCity" placeholder="City" value="Cebu City">
                </div>
              </div>
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="deliveryNotes">Delivery Instructions</label>
                  <textarea class="form-textarea" id="deliveryNotes" placeholder="Gate code, landmark, preferred drop-off spot…"></textarea>
                </div>
              </div>
              <p style="font-family:var(--font-body);font-size:0.78rem;font-weight:300;color:var(--text-dim);margin-top:4px;">
                🛵 Estimated delivery: 30–45 mins &nbsp;·&nbsp; Delivery fee: ₱50
              </p>
            </div>

          </div>
        </div>

        <!-- 2. Contact Details -->
        <div class="co-panel reveal reveal-delay-1">
          <div class="co-panel-header">
            <div class="co-panel-title">
              <div class="co-panel-num">2</div>
              <div>
                <div class="co-panel-eyebrow">Who</div>
                Contact Details
              </div>
            </div>
          </div>
          <div class="co-panel-body">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="firstName">First Name <span class="req">*</span></label>
                <input class="form-input" type="text" id="firstName"
                  placeholder="First name"
                  value="<?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? '')[0]); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="lastName">Last Name</label>
                <input class="form-input" type="text" id="lastName"
                  placeholder="Last name"
                  value="<?php
                    $parts = explode(' ', $_SESSION['user_name'] ?? '');
                    echo htmlspecialchars(count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '');
                  ?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="contactEmail">Email <span class="req">*</span></label>
                <input class="form-input" type="email" id="contactEmail"
                  placeholder="your@email.com"
                  value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="contactPhone">Phone <span class="req">*</span></label>
                <input class="form-input" type="tel" id="contactPhone" placeholder="+63 9XX XXX XXXX">
              </div>
            </div>
            <div class="form-row single">
              <div class="form-group">
                <label class="form-label" for="orderNote">Order Notes</label>
                <textarea class="form-textarea" id="orderNote" placeholder="Any special requests, allergies, or preferences…"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Payment -->
        <div class="co-panel reveal reveal-delay-2">
          <div class="co-panel-header">
            <div class="co-panel-title">
              <div class="co-panel-num">3</div>
              <div>
                <div class="co-panel-eyebrow">How to Pay</div>
                Payment Method
              </div>
            </div>
          </div>
          <div class="co-panel-body">
            <div class="payment-options">

              <div>
                <input type="radio" class="payment-option" name="paymentMethod" id="payCash" value="cash" checked>
                <label class="payment-label" for="payCash">
                  <span class="payment-icon">💵</span>
                  <div>
                    <div class="payment-name">Cash</div>
                    <div class="payment-sub">Pay on pickup</div>
                  </div>
                </label>
              </div>

              <div>
                <input type="radio" class="payment-option" name="paymentMethod" id="payGcash" value="gcash">
                <label class="payment-label" for="payGcash">
                  <span class="payment-icon">📱</span>
                  <div>
                    <div class="payment-name">GCash</div>
                    <div class="payment-sub">Send to our number</div>
                  </div>
                </label>
              </div>

              <div>
                <input type="radio" class="payment-option" name="paymentMethod" id="payMaya" value="maya">
                <label class="payment-label" for="payMaya">
                  <span class="payment-icon">💚</span>
                  <div>
                    <div class="payment-name">PayMaya</div>
                    <div class="payment-sub">Send to our number</div>
                  </div>
                </label>
              </div>

              <div>
                <input type="radio" class="payment-option" name="paymentMethod" id="payCard" value="card">
                <label class="payment-label" for="payCard">
                  <span class="payment-icon">💳</span>
                  <div>
                    <div class="payment-name">Card</div>
                    <div class="payment-sub">Visa / Mastercard</div>
                  </div>
                </label>
              </div>

            </div>

            <!-- E-wallet instructions -->
            <div id="ewalletFields">
              <p style="font-family:var(--font-body);font-size:0.84rem;font-weight:300;color:var(--text-muted);line-height:1.65;margin-bottom:14px;">
                Send your payment to <strong style="color:var(--accent);font-weight:600;">+63 917 123 4567</strong> and upload a screenshot of your receipt below.
              </p>
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="receiptRef">Reference Number <span class="req">*</span></label>
                  <input class="form-input" type="text" id="receiptRef" placeholder="e.g. 9834521067">
                </div>
              </div>
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label">Receipt Screenshot</label>
                  <input class="form-input" type="file" id="receiptFile" accept="image/*" style="padding:8px 14px;">
                </div>
              </div>
            </div>

            <!-- Card fields -->
            <div id="cardFields">
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="cardName">Cardholder Name <span class="req">*</span></label>
                  <input class="form-input" type="text" id="cardName" placeholder="As it appears on card">
                </div>
              </div>
              <div class="form-row single">
                <div class="form-group">
                  <label class="form-label" for="cardNumber">Card Number <span class="req">*</span></label>
                  <div class="card-number-wrap">
                    <input class="form-input" type="text" id="cardNumber"
                      placeholder="0000 0000 0000 0000"
                      maxlength="19"
                      oninput="formatCardNumber(this)"
                      style="padding-right:48px;">
                    <span class="card-brand" id="cardBrand">💳</span>
                  </div>
                </div>
              </div>
              <div class="form-row thirds">
                <div class="form-group">
                  <label class="form-label" for="cardExpiry">Expiry <span class="req">*</span></label>
                  <input class="form-input" type="text" id="cardExpiry" placeholder="MM / YY" maxlength="7" oninput="formatExpiry(this)">
                </div>
                <div class="form-group">
                  <label class="form-label" for="cardCvv">CVV <span class="req">*</span></label>
                  <input class="form-input" type="password" id="cardCvv" placeholder="•••" maxlength="4">
                </div>
                <div class="form-group" style="justify-content:flex-end;padding-bottom:2px;">
                  <span style="font-family:var(--font-body);font-size:0.72rem;font-weight:300;color:var(--text-dim);line-height:1.5;">
                    3–4 digits on the back of your card
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div><!-- /left-col -->

      <!-- RIGHT: Order Summary -->
      <div class="sidebar-col">
        <div class="order-summary-card reveal">
          <div class="osc-header">
            <div class="osc-title">Your Order</div>
          </div>
          <div class="osc-body">

            <!-- BrewPoints applied badge -->
            <div class="points-applied-badge" id="pointsBadge">
              <span class="pab-icon">✦</span>
              <div class="pab-text">
                <strong id="pointsBadgeText">350 pts applied</strong> — saving you ₱35.00
              </div>
            </div>

            <!-- Items list (populated by JS) -->
            <div class="osc-items" id="oscItems">
              <div class="osc-skeleton"></div>
              <div class="osc-skeleton" style="opacity:0.6;height:36px;"></div>
              <div class="osc-skeleton" style="opacity:0.3;height:28px;"></div>
            </div>

            <!-- Totals -->
            <div id="oscTotals">
              <div class="summary-line">
                <span>Subtotal</span>
                <span id="oscSubtotal">—</span>
              </div>
              <div class="summary-line">
                <span>Tax (10%)</span>
                <span id="oscTax">—</span>
              </div>
              <div class="summary-line" id="oscDeliveryRow" style="display:none;">
                <span>Delivery fee</span>
                <span>₱50.00</span>
              </div>
              <div class="summary-line discount" id="oscDiscountRow" style="display:none;">
                <span>BrewPoints discount</span>
                <span id="oscDiscount">—</span>
              </div>
              <div class="summary-line total">
                <span>Total</span>
                <span id="oscTotal">—</span>
              </div>
            </div>

            <button class="btn btn-primary place-order-btn" onclick="placeOrder()">
              <span class="btn-spinner" id="orderSpinner"></span>
              <span class="btn-text">Place Order</span>
            </button>

            <div class="secure-badge">
              🔒 Secure checkout &nbsp;·&nbsp; NestledBrew
            </div>

          </div>
        </div>
      </div>

    </div><!-- /checkout-body-inner -->
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
    /* ===== CHECKOUT PAGE SCRIPT ===== */

    // Pull discount/points passed from cart.js via sessionStorage
    const _checkoutPoints   = parseInt(sessionStorage.getItem('checkout_points')   || '0');
    const _checkoutDiscount = parseFloat(sessionStorage.getItem('checkout_discount') || '0');

    let _cartData = null; // will hold full cart response

    /* ===== LOAD CART DATA INTO SUMMARY ===== */
    async function loadCheckoutSummary() {
      try {
        const fd = new FormData();
        fd.append('action', 'get');
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status !== 'success' || !result.items || result.items.length === 0) {
          // Nothing in cart — redirect back
          showToast('Your cart is empty. Redirecting…');
          setTimeout(() => { window.location.href = 'cart.php'; }, 1500);
          return;
        }

        _cartData = result;
        renderSummary(result);
      } catch (e) {
        console.error(e);
        showToast('Could not load cart. Please try again.');
      }
    }

    function renderSummary(data) {
      // Items
      const oscItems = document.getElementById('oscItems');
      if (!oscItems) return;

      let html = '';
      data.items.forEach(item => {
        html += `
          <div class="osc-item">
            <img class="osc-item-img"
              src="${item.image_path || 'assets/placeholder.png'}"
              alt="${escHtml(item.name)}"
              onerror="this.style.display='none'">
            <div class="osc-item-info">
              <div class="osc-item-name">${escHtml(item.name)}</div>
              <div class="osc-item-qty">× ${item.quantity}</div>
            </div>
            <div class="osc-item-price">₱${parseFloat(item.subtotal).toFixed(2)}</div>
          </div>`;
      });
      oscItems.innerHTML = html;

      // Totals
      const subtotal = parseFloat(data.subtotal);
      const tax      = parseFloat(data.tax);
      const baseTotal = parseFloat(data.total);
      const isDelivery = document.querySelector('input[name="orderType"]:checked')?.value === 'delivery';
      const deliveryFee = isDelivery ? 50 : 0;
      const finalTotal = Math.max(baseTotal + deliveryFee - _checkoutDiscount, 0);

      document.getElementById('oscSubtotal').textContent = `₱${subtotal.toFixed(2)}`;
      document.getElementById('oscTax').textContent      = `₱${tax.toFixed(2)}`;
      document.getElementById('oscTotal').textContent    = `₱${finalTotal.toFixed(2)}`;

      // Points badge
      if (_checkoutPoints > 0 && _checkoutDiscount > 0) {
        const badge = document.getElementById('pointsBadge');
        document.getElementById('pointsBadgeText').textContent =
          `${_checkoutPoints.toLocaleString()} pts applied — saving you ₱${_checkoutDiscount.toFixed(2)}`;
        badge.classList.add('visible');

        document.getElementById('oscDiscountRow').style.display  = 'flex';
        document.getElementById('oscDiscount').textContent       = `−₱${_checkoutDiscount.toFixed(2)}`;
      }

      updateDeliveryRow();
    }

    function updateDeliveryRow() {
      const isDelivery = document.querySelector('input[name="orderType"]:checked')?.value === 'delivery';
      const row = document.getElementById('oscDeliveryRow');
      if (row) row.style.display = isDelivery ? 'flex' : 'none';

      // Recalculate total with/without delivery fee
      if (_cartData) {
        const baseTotal   = parseFloat(_cartData.total);
        const deliveryFee = isDelivery ? 50 : 0;
        const finalTotal  = Math.max(baseTotal + deliveryFee - _checkoutDiscount, 0);
        const totalEl = document.getElementById('oscTotal');
        if (totalEl) totalEl.textContent = `₱${finalTotal.toFixed(2)}`;
      }
    }

    /* ===== ORDER TYPE TOGGLING ===== */
    document.querySelectorAll('input[name="orderType"]').forEach(radio => {
      radio.addEventListener('change', () => {
        const val = radio.value;
        const deliveryFields = document.getElementById('deliveryFields');
        const dineinFields   = document.getElementById('dineinFields');

        deliveryFields.classList.toggle('visible', val === 'delivery');
        dineinFields.style.display = val === 'dinein' ? 'block' : 'none';

        updateDeliveryRow();
      });
    });

    /* ===== PAYMENT METHOD TOGGLING ===== */
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
      radio.addEventListener('change', () => {
        const val        = radio.value;
        const ewallet    = document.getElementById('ewalletFields');
        const cardFields = document.getElementById('cardFields');

        ewallet.classList.toggle('visible',    val === 'gcash' || val === 'maya');
        cardFields.classList.toggle('visible', val === 'card');
      });
    });

    /* ===== CARD NUMBER FORMATTING ===== */
    function formatCardNumber(input) {
      let val = input.value.replace(/\D/g, '').substring(0, 16);
      input.value = val.match(/.{1,4}/g)?.join(' ') || val;

      // Simple brand detection
      const brand = document.getElementById('cardBrand');
      if (!brand) return;
      if (/^4/.test(val))      brand.textContent = '💙'; // Visa-ish
      else if (/^5/.test(val)) brand.textContent = '🟠'; // MC-ish
      else                     brand.textContent = '💳';
    }

    function formatExpiry(input) {
      let val = input.value.replace(/\D/g, '').substring(0, 4);
      if (val.length >= 3) val = val.substring(0,2) + ' / ' + val.substring(2);
      input.value = val;
    }

    /* ===== VALIDATION ===== */
    function validateForm() {
      const orderType   = document.querySelector('input[name="orderType"]:checked')?.value;
      const payment     = document.querySelector('input[name="paymentMethod"]:checked')?.value;
      const firstName   = document.getElementById('firstName').value.trim();
      const email       = document.getElementById('contactEmail').value.trim();
      const phone       = document.getElementById('contactPhone').value.trim();

      if (!firstName)  { showToast('Please enter your first name.');       return false; }
      if (!email)      { showToast('Please enter your email address.');    return false; }
      if (!phone)      { showToast('Please enter your phone number.');     return false; }

      if (orderType === 'delivery') {
        const addr = document.getElementById('deliveryAddress').value.trim();
        const bgy  = document.getElementById('deliveryBarangay').value.trim();
        if (!addr) { showToast('Please enter your delivery address.'); return false; }
        if (!bgy)  { showToast('Please enter your barangay.');         return false; }
      }

      if (payment === 'gcash' || payment === 'maya') {
        const ref = document.getElementById('receiptRef').value.trim();
        if (!ref) { showToast('Please enter your e-wallet reference number.'); return false; }
      }

      if (payment === 'card') {
        const cn  = document.getElementById('cardNumber').value.replace(/\s/g,'');
        const exp = document.getElementById('cardExpiry').value.trim();
        const cvv = document.getElementById('cardCvv').value.trim();
        const nm  = document.getElementById('cardName').value.trim();
        if (!nm)        { showToast('Please enter the cardholder name.');    return false; }
        if (cn.length < 13) { showToast('Please enter a valid card number.'); return false; }
        if (!exp)       { showToast('Please enter the card expiry date.');   return false; }
        if (!cvv)       { showToast('Please enter the CVV.');                return false; }
      }

      return true;
    }

    /* ===== PLACE ORDER ===== */
    async function placeOrder() {
      if (!validateForm()) return;

      const btn = document.querySelector('.place-order-btn');
      btn.classList.add('loading');
      btn.disabled = true;

      try {
        const orderType = document.querySelector('input[name="orderType"]:checked')?.value;
        const payment   = document.querySelector('input[name="paymentMethod"]:checked')?.value;

        const fd = new FormData();
        fd.append('action',             'place_order');
        fd.append('order_type',         orderType);
        fd.append('payment_method',     payment);
        fd.append('first_name',         document.getElementById('firstName').value.trim());
        fd.append('last_name',          document.getElementById('lastName').value.trim());
        fd.append('email',              document.getElementById('contactEmail').value.trim());
        fd.append('phone',              document.getElementById('contactPhone').value.trim());
        fd.append('order_note',         document.getElementById('orderNote').value.trim());
        fd.append('points_used',        _checkoutPoints);
        fd.append('discount_amount',    _checkoutDiscount.toFixed(2));

        if (orderType === 'delivery') {
          fd.append('delivery_address',  document.getElementById('deliveryAddress').value.trim());
          fd.append('delivery_barangay', document.getElementById('deliveryBarangay').value.trim());
          fd.append('delivery_city',     document.getElementById('deliveryCity').value.trim());
          fd.append('delivery_notes',    document.getElementById('deliveryNotes').value.trim());
        }

        if (orderType === 'dinein') {
          fd.append('table_number', document.getElementById('tableNumber').value.trim());
        }

        if (payment === 'gcash' || payment === 'maya') {
          fd.append('receipt_ref', document.getElementById('receiptRef').value.trim());
          const file = document.getElementById('receiptFile').files[0];
          if (file) fd.append('receipt_file', file);
        }

        if (payment === 'card') {
          // NOTE: In production, never send raw card data to your own server.
          // Use a payment gateway (e.g. PayMongo) to tokenise the card client-side.
          fd.append('card_name',   document.getElementById('cardName').value.trim());
          fd.append('card_last4',  document.getElementById('cardNumber').value.replace(/\s/g,'').slice(-4));
        }

        const res    = await fetch('place-order.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result && result.success) {
          // Clear sessionStorage discount
          sessionStorage.removeItem('checkout_points');
          sessionStorage.removeItem('checkout_discount');

          // Show success overlay
          const orderNum = result.order_number || ('NB-' + String(Date.now()).slice(-6));
          document.getElementById('successOrderNum').textContent = '# ' + orderNum;
          document.getElementById('successOverlay').classList.add('show');

          // Update step indicator
          document.querySelectorAll('.step-dot')[1].className = 'step-dot done';
          document.querySelectorAll('.step-dot')[1].textContent = '✓';
          document.querySelectorAll('.step-dot')[2].className = 'step-dot active';
          document.querySelectorAll('.step-dot')[2].textContent = '3';
          document.querySelectorAll('.step-label')[2].className = 'step-label active';
        } else {
          showToast('✗ ' + (result?.message || 'Could not place order. Please try again.'));
          btn.classList.remove('loading');
          btn.disabled = false;
        }
      } catch (e) {
        console.error(e);
        showToast('Connection error. Please check your internet and try again.');
        btn.classList.remove('loading');
        btn.disabled = false;
      }
    }

    /* ===== HELPER ===== */
    function escHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    /* ===== INIT ===== */
    document.addEventListener('DOMContentLoaded', loadCheckoutSummary);
  </script>
</body>
</html>

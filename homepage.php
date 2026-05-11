<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NestledBrew — Your Favourite Coffee Destination</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
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
      <a href="homepage.php" class="nav-link active">Home</a>
      <a href="menu.php" class="nav-link">Menu</a>
      <a href="about.php" class="nav-link">About Us</a>
      <a href="rewards.php" class="nav-link">Rewards</a>
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


  <!-- ===== HERO SECTION ===== -->
  <section id="hero">
    <div class="hero-bg-texture"></div>
    <div class="hero-grain"></div>
    <div class="hero-inner">

      <!-- LEFT: Logo / Visual -->
      <div class="hero-visual animate-fadeLeft">
        <div class="hero-logo-container">
          <div class="hero-logo-ring">
            <div class="hero-logo-mark">
              <svg viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="80" cy="80" r="74" stroke="var(--accent)" stroke-width="1" stroke-dasharray="4 6"/>
                <circle cx="80" cy="80" r="60" stroke="var(--accent)" stroke-width="1.5" opacity="0.6"/>
                <circle cx="80" cy="80" r="46" fill="var(--surface)" stroke="var(--accent)" stroke-width="1"/>
                <text x="50%" y="52%" dominant-baseline="middle" text-anchor="middle" font-family="Fraunces, serif" font-size="44" font-style="italic" fill="var(--accent)">N</text>
              </svg>
            </div>
            <div class="hero-logo-orbit">
              <span class="orbit-dot"></span>
            </div>
          </div>
          <div class="hero-badge animate-pop">
            <div class="hero-badge-icon">⭐</div>
            <div class="hero-badge-text">
              <div class="hero-badge-title">4.9 / 5.0</div>
              <div class="hero-badge-sub">2,400+ reviews</div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Content -->
      <div class="hero-content">
        <p class="hero-eyebrow animate-fadeUp" style="--d:0.2s">Est. 2019 · Cebu City</p>
        <h1 class="hero-title animate-fadeUp" style="--d:0.4s">
          NestledBrew<br>
          <em>Where Every Sip<br>Tells a Story</em>
        </h1>
        <p class="hero-sub animate-fadeUp" style="--d:0.6s">
          A sanctuary for coffee lovers and book enthusiasts in the heart of Cebu City. We source ethically, roast in-house, and brew with soul — so every cup is an experience worth returning for.
        </p>
        <div class="hero-ctas animate-fadeUp" style="--d:0.8s">
          <a href="menu.php" class="btn btn-primary btn-lg">Explore Menu</a>
          <a href="about.php" class="btn btn-lg">Our Story</a>
        </div>
        <div class="hero-stats animate-fadeUp" style="--d:1.0s">
          <div class="stat-item">
            <div class="stat-number">5+</div>
            <div class="stat-label">Years Brewing</div>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <div class="stat-number">50k+</div>
            <div class="stat-label">Happy Guests</div>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <div class="stat-number">100%</div>
            <div class="stat-label">Organic Beans</div>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-scroll-cue">
      <span>Scroll to explore</span>
      <div class="scroll-arrow"></div>
    </div>
  </section>


  <!-- ===== INTERACTIVE MAP SECTION ===== -->
  <section id="location">
    <div class="section-header">
      <p class="section-eyebrow reveal">Find Us</p>
      <h2 class="section-title reveal reveal-delay-1">Visit Our Café</h2>
      <p class="section-body reveal reveal-delay-2">
        Located near the University of San Carlos — your perfect study break destination. Drop by for a cup, stay for the ambiance.
      </p>
    </div>
    <div class="map-wrapper reveal reveal-delay-2">
      <div id="map3d" class="map-container"></div>
      <div class="map-info-card">
        <div class="map-info-icon">☕</div>
        <div class="map-info-details">
          <div class="map-info-name">NestledBrew Café</div>
          <div class="map-info-addr">Near University of San Carlos<br>Cebu City, Philippines</div>
          <div class="map-info-hours">Open today until 8:00 PM</div>
        </div>
        <a href="https://maps.google.com/?q=University+of+San+Carlos+Cebu+City" target="_blank" class="btn btn-primary map-directions-btn">Get Directions</a>
      </div>
    </div>
  </section>


  <!-- ===== MENU PREVIEW SECTION ===== -->
  <section id="menu-preview">
    <div class="section-header">
      <p class="section-eyebrow reveal">Discover Your Perfect Brew</p>
      <h2 class="section-title reveal reveal-delay-1">Our Signature Drinks</h2>
      <p class="section-body reveal reveal-delay-2">
        From classic espresso to innovative specialty drinks — each beverage crafted with precision and care.
      </p>
    </div>

    <div class="menu-tabs reveal">
      <button class="tab-btn active" data-tab="espresso" onclick="switchMenuTab('espresso', this)">Espresso</button>
      <button class="tab-btn" data-tab="specialty" onclick="switchMenuTab('specialty', this)">Specialty</button>
      <button class="tab-btn" data-tab="food" onclick="switchMenuTab('food', this)">Food</button>
    </div>

    <div class="menu-grid" id="menuGrid"></div>

    <div class="menu-cta-row reveal">
      <a href="menu.php" class="btn btn-primary btn-lg">View Full Menu</a>
    </div>
  </section>


  <!-- ===== ABOUT PREVIEW SECTION ===== -->
  <section id="about-preview">

      <div class="about-content">
        <p class="section-eyebrow reveal">Our Story</p>
        <h2 class="section-title reveal reveal-delay-1">
          Crafted with Passion,<br><em>Served with Love</em>
        </h2>
        <p class="section-body reveal reveal-delay-2">
          Since 2019, NestledBrew has been a sanctuary for coffee lovers seeking exceptional 
          quality and genuine hospitality. We source our beans from sustainable farms across 14 countries, 
          roasting them in-house to bring out their unique character.
        </p>
        <p class="section-body reveal reveal-delay-3">
          Our skilled baristas transform premium beans into artful beverages that awaken the 
          senses — paired perfectly with a good book and even better company.
        </p>

        <div class="reveal reveal-delay-4" style="margin-top:36px">
        </div>
      </div>
    </div>
  </section>

  
  <section id="rewards-preview">
    <div class="rewards-inner">
      <div class="rewards-content">
        <p class="section-eyebrow reveal">NestledBrew Rewards</p>
        <h2 class="section-title reveal reveal-delay-1">
          Every Cup<br><em>Earns More</em>
        </h2>
        <p class="section-body reveal reveal-delay-2">
          Join NestledBrew Rewards and earn points with every visit. Unlock exclusive perks, free drinks, and member-only discounts as you climb through our tiers.
        </p>
        <div class="rewards-tiers reveal reveal-delay-3">
          <div class="tier-item">
            <div class="tier-icon">🙌</div>
            <div class="tier-info">
              <div class="tier-name">Bean Starter</div>
              <div class="tier-desc">Free birthday drink · Priority queuing</div>
            </div>
            <div class="tier-pts">0 pts</div>
          </div>
          <div class="tier-item">
            <div class="tier-icon">✨</div>
            <div class="tier-info">
              <div class="tier-name">Brew Member</div>
              <div class="tier-desc">10% off every order · Monthly free drink</div>
            </div>
            <div class="tier-pts">500 pts</div>
          </div>
          <div class="tier-item">
            <div class="tier-icon">🔥</div>
            <div class="tier-info">
              <div class="tier-name">Gold Reserve</div>
              <div class="tier-desc">20% off · Early menu access · Free delivery</div>
            </div>
            <div class="tier-pts">2,000 pts</div>
          </div>
        </div>
        <div style="margin-top:32px" class="reveal reveal-delay-4">
          <a href="login.php#signup" class="btn btn-primary btn-lg">Join Rewards — It's Free</a>
          <a href="rewards.php" class="btn btn-lg" style="margin-left:12px">Learn More</a>
        </div>
      </div>

      <div class="rewards-visual reveal reveal-delay-2">
        <div class="rewards-card">
          <div class="rewards-card-shimmer"></div>
          <div class="rewards-card-brand">NestledBrew</div>
          <div class="points-number rewards-card-pts-label"> <?php echo isset($_SESSION['user_points']) ? $_SESSION['user_points'] : '0'; ?> </div>
          <div class="rewards-card-pts-label">BrewPoints</div>
          <span class="user-name-text rewards-card-name "><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?></span>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== TESTIMONIALS ===== -->
  <section id="testimonials">
    <div class="section-header">
      <p class="section-eyebrow reveal">What Guests Say</p>
      <h2 class="section-title reveal reveal-delay-1">Loved by Coffee Lovers</h2>
    </div>
    <div class="testimonial-track-wrapper reveal reveal-delay-2">
      <div class="testimonial-track" id="testimonialTrack">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">"The best café in Cebu, hands down. The oat milk latte is absolutely divine, and the atmosphere makes you want to stay all day with a good book."</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar">A</div>
            <div>
              <div class="testimonial-name">Andrea M.</div>
              <div class="testimonial-role">USC Student</div>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">"I bring all my clients here for meetings. The cold brew is unmatched, and the staff genuinely care about every single cup they make."</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar">R</div>
            <div>
              <div class="testimonial-name">Rafael T.</div>
              <div class="testimonial-role">Local Business Owner</div>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">"NestledBrew feels like a warm hug. The seasonal specials are always something new and exciting. My loyalty card is basically full every month!"</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar">S</div>
            <div>
              <div class="testimonial-name">Sofia L.</div>
              <div class="testimonial-role">Freelance Writer</div>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">"The pour-over selection here rivals anything I'
            ve had in Manila. The baristas really know their craft — they'll tell you everything
             about the beans."</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar">K</div>
            <div>
              <div class="testimonial-name">Karl V.</div>
              <div class="testimonial-role">Coffee Enthusiast</div>
            </div>
          </div>
        </div>
      </div>
      <div class="testimonial-controls">
        <button class="testimonial-btn" onclick="scrollTestimonials(-1)">←</button>
        <button class="testimonial-btn" onclick="scrollTestimonials(1)">→</button>
      </div>
    </div>
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


  <!-- Leaflet.js for 3D-style Map -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
  <script src="homepage.js"></script>
</body>
</html>
  <?php session_start(); 
  include 'db.php';
  
  if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $_SESSION['user_picture'] = $row['profile_picture'] ?? null;
}

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — NestledBrew</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="about.css">
  </head>
  <body>

    <div class="page-loader" id="pageLoader">
      <div class="loader-inner">
        <div class="loader-logo">N</div>
        <div class="loader-bar"><div class="loader-fill"></div></div>
      </div>
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
        <a href="about.php" class="nav-link active">About Us</a>
        <a href="rewards.php" class="nav-link">Rewards</a>
      </nav>

      <div class="nav-actions">
      <a href="cart.php" class="cart-nav-link">
      🛒 <span class="cart-nav-label">Cart</span>
      </a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <div class="user-profile-group">
            <a href="profile.php" class="nav-profile-link">
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

    <section id="hero" style="min-height: 60vh;">
      <div class="hero-bg-texture"></div>
      <div class="hero-inner" style="justify-content: center; text-align: center;">
        <div class="hero-content">
          <h1 class="hero-title animate-fadeUp" style="--d:0.2s">Meet the <em>Heart</em><br>Behind the Brew</h1>
          <p class="hero-sub animate-fadeUp" style="--d:0.4s; margin: 0 auto 36px;">
            NestledBrew began with a simple dream: to create a sanctuary where community and caffeine meet. Get to know the team dedicated to crafting your perfect cup.
          </p>
        </div>
      </div>
    </section>

  <section id="team">
      <div class="section-header">
        <p class="section-eyebrow reveal">Cozy Corners. Warm Cups. Good Company</p>
        <h2 class="section-title reveal reveal-delay-1">Our Story</h2>
        <p class="section-body reveal reveal-delay-2">
        At Nestled Brew, we believe that the best moments 
        are found in the quiet spaces between the noise. 
        Born from a love for nature and a craving for 
        genuine connection, our shop was designed to be more than 
        just a stop on your commute, it's a place to root yourself, 
        even if just for a morning
        </p>
      </div>


    <section id="team_people">
      <div class="section-header">
        <p class="section-eyebrow reveal">The People</p>
        <h2 class="section-title reveal reveal-delay-1">Our Founding Team</h2>
      </div>

      <div class="team-grid">
        <div class="team-card reveal">
          <div class="team-img-wrapper">
          <img src="picutres/jhonne.jpg" alt="Jhonne Reinz Jarito" class="team-photo"> </div>
          <div class="team-name">Jhonne Reinz Jarito</div>
          <div class="team-role">Barista</div>
          <p class="team-bio">A dedicated latte artist who spent three months perfecting the "Cebuano Sun" design.</p>
        </div>

        <div class="team-card reveal reveal-delay-1">
          <div class="team-img-wrapper">
          <img src="picutres/lyan.jpeg" alt="L" class="team-photo"> </div>
          <div class="team-name">Lyan Cryster Cuesta</div>
          <div class="team-role">Barista</div>
          <p class="team-bio">A certified bean enthusiast who keeps a personal journal tracking the flavor profiles of every coffee origin he has ever tasted.</p>
        </div>

        <div class="team-card reveal reveal-delay-2">
          <div class="team-img-wrapper">
          <img src="picutres/micah.webp" alt="Micah Jolie Ocanada" class="team-photo"> </div>
          <div class="team-name">Micah Jolie Ocanada</div>
          <div class="team-role">Lead Barista</div>
          <p class="team-bio">Known as the team's "Scent Specialist," she can identify a coffee roast's origin blindfolded just by the aroma of the grind.</p>
        </div>

        <div class="team-card reveal">
          <div class="team-img-wrapper">
          <img src="picutres/leigh.webp" alt="Liegh Avery Lamayo" class="team-photo"> </div>
          <div class="team-name">Liegh Avery Lamayo</div>
          <div class="team-role">Barista</div>
          <p class="team-bio">A champion of sustainability who pioneered the shop's zero-waste initiative for recycled coffee grounds.</p>
        </div>

        <div class="team-card reveal reveal-delay-1"> 
          <div class="team-img-wrapper">
          <img src="picutres/joseph.jpg" alt="Joseph Martin Maquinad" class="team-photo"> </div>
          <div class="team-name">Joseph Martin Maquinad</div>
          <div class="team-role">Barista</div>
          <p class="team-bio">An expert in brewing chemistry who maintains that the secret to the perfect cup is the precise mineral balance of the local spring water.</p>
        </div>
      </div>
    </section>
        </section>

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
            <li><a href="#">LiveLoveLieSt.</a></li>
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
            <li><a href="mailto:hello@nestledbrew.com">nestledbrew@gmail.com</a></li>
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
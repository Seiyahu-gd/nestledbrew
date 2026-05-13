<?php session_start();
include 'db.php';
 
$categories_query = "SELECT DISTINCT category FROM menu_items ORDER BY category ASC";
$categories_result = $conn->query($categories_query);

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
    <title>Menu — NestledBrew</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="menu.css">
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
      <a href="menu.php" class="nav-link active">Menu</a>
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

    <main class="menu-page">
        <div class="menu-container">
            <?php while($cat = $categories_result->fetch_assoc()): 
                $current_cat = $cat['category']; ?>
                
                <section class="menu-section reveal">
                    <h2 class="category-title"><?php echo $current_cat; ?></h2>
                    <div class="menu-grid">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE category = ?");
                        $stmt->bind_param("s", $current_cat);
                        $stmt->execute();
                        $items_result = $stmt->get_result();
                        while($item = $items_result->fetch_assoc()): ?>
                            
                            <div class="menu-card">
                                <div class="card-image">
                                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                                <div class="card-content">
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p class="price">₱<?php echo number_format($item['price'], 2); ?></p>
                                    <div class="quantity-control" id="qty-<?php echo $item['id']; ?>">
                                      <button class="add-btn" onclick="showQtyControl(<?php echo $item['id']; ?>)">+ Add</button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php endwhile; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="homepage.js"></script>
    <script src="menu.js"></script>
</body>
</html>
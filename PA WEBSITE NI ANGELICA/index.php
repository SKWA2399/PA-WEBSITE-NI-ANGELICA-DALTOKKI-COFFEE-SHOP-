<?php 
require_once 'includes/config.php'; 

// Redirect logged-in users to dashboard
if (is_logged_in()) {
    redirect('pages/dashboard.php');
}

// Get cart count for non-logged-in users
$cart_count = get_cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Dal Tokki Cafe - Cozy cafe in Rodriguez, Calabarzon. Enjoy signature drinks, pastries, and a smart loyalty experience." />
  <title>Dal Tokki Cafe</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/root.css">
  
</head>
<body>
  <!-- ================= HEADER ================= -->
  <header>
    <div class="logo">
      <img src="assets/images/icon.jpg" alt="Dal Tokki Cafe Logo">
      <h1>Dal Tokki Cafe</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="navbar">
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#menu">Menu</a>
      <a href="#contact">Contact</a>
    </nav>

    <!-- Cart Button -->
    <a href="auth/login.php?from=cart" class="cart-btn" id="cart-button">
      <i class="fas fa-shopping-cart"></i> Cart
      <?php if ($cart_count > 0): ?>
        <span class="cart-count"><?php echo $cart_count; ?></span>
      <?php endif; ?>
    </a>

    <!-- Mobile Menu Icon -->
    <div id="menu-icon">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>

  <!-- ================= HERO SECTION ================= -->
  <section class="hero" id="home">
    <div class="hero-container">
      <div class="hero-text">
        <h2>Brewing Happiness, One Cup at a Time <i class="fas fa-coffee"></i></h2>
        <p>Located in Rodriguez, Calabarzon - Your cozy neighborhood cafe for great coffee and community.</p>
      </div>
      
      <div class="hero-image">
        <img src="assets/images/background.jpeg" alt="Dal Tokki Cafe">
      </div>
    </div>
  </section>

<!-- ================= ABOUT DAL TOKKI CAFE ================= -->
<section class="about" id="about">
  <div class="about-container">
    <!-- About Text -->
    <div class="about-text">
      <h2>About Dal Tokki Cafe</h2>
      <p>
        Chrysxava's <strong>Dal Tokki Café</strong> is a cozy, community-centered cafe
        in the heart of <strong>Rodriguez, Calabarzon</strong>. We serve carefully brewed coffee,
        delicious pastries, and signature drinks that bring people together.
      </p>
      <a href="#menu" class="about-btn">Discover Our Menu</a>
    </div>
    
    <!-- About Image -->
    <div class="about-img">
      <img src="assets/images/about-cafe.jpg" alt="Dal Tokki Cafe Interior">
    </div>
  </div>
</section>

  <!-- ================= BEST SELLERS ================= -->
  <section class="menu" id="menu">
    <h2>Our Best-Sellers</h2>
    <p class="menu-subtitle">Try our signature drinks!</p>
    
    <div class="menu-cards">
      <?php
      // Get best sellers from database
      $stmt = $pdo->prepare("SELECT * FROM products WHERE is_bestseller = 1 AND is_available = 1 ORDER BY display_order");
      $stmt->execute();
      $bestsellers = $stmt->fetchAll();
      
      foreach ($bestsellers as $product):
      ?>
      <div class="card">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <div class="card-footer">
          <span class="price"><?php echo format_price($product['price']); ?></span>
          <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
            Add to Cart
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    
    <div class="menu-actions">
      <a href="auth/login.php" class="full-menu-btn">Login for Full Menu & Ordering</a>
    </div>
  </section>


  <!-- ================= CONTACT & LOCATION ================= -->
  <section class="contact" id="contact">
    <div class="contact-main">
      <h2>Find Us</h2>
      <p>Chrysxava's Dal Tokki Café, Rodriguez, Calabarzon, Philippines</p>
      <div class="map-container">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d482.5!2d121.1322472!3d14.720859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397bb006be6e565%3A0x5c71f970dcfe3ba0!2sChrysXavA%E2%80%99s%20Dal%20Tokki%20Cafe!5e0!3m2!1sen!2sph!4v1726665000000!5m2!1sen!2sph"
          class="map-frame"
          title="ChrysXavA's Dal Tokki Cafe location on Google Maps"
          allowfullscreen 
          loading="lazy">
        </iframe>
      </div>

      <div class="social">
        <a href="#" aria-label="Facebook">Facebook</a>
        <a href="#" aria-label="Instagram">Instagram</a>
        <a href="#" aria-label="Twitter">Twitter</a>
      </div>
    </div>
    
    <!-- Footer close to Find Us content -->
    <div class="footer-content">
      <p>© <?php echo date('Y'); ?> Dal Tokki Cafe. All rights reserved.</p>
    </div>
  </section>

  <!-- JavaScript -->
  <script src="assets/js/main.js"></script>
</body>
</html>

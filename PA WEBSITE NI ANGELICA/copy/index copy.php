<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Dal Tokki Coffee - Cozy coffee shop in Rodriguez, Calabarzon. Enjoy signature drinks, pastries, and a smart loyalty experience." />
  <title>Dal Tokki Coffee</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- ================= HEADER ================= -->
  <header>
    <div class="logo">
      <img src="IMAGES/LOGO.jpg" alt="Dal Tokki Coffee Logo">
      <h1>Dal Tokki Coffee</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="navbar">
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#menu">Menu</a>
      <a href="#loyalty">Loyalty</a>
      <a href="#contact">Contact</a>
    </nav>

    <!-- Call to Action -->
    <a href="#menu" class="cta-btn">Order Now</a>

    <!-- Mobile Menu Icon -->
    <div id="menu-icon">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>

  <!-- ================= HERO SECTION ================= -->
  <section class="hero" id="home">
    <div class="hero-content">
      <h2>Brewing Happiness, One Cup at a Time ☕</h2>
      <p>Located in Rodriguez, Calabarzon, Dal Tokki Coffee brings people together with cozy spaces, flavorful drinks, and a strong community vibe.</p>
      <a href="#menu" class="hero-btn">Explore Menu</a>
    </div>
  </section>

<!-- ================= ABOUT DAL TOKKI COFFEE ================= -->
<section class="about" id="about">
  <div class="about-container">
    <!-- About Image -->
    <div class="about-img">
      <img src="IMAGES/about-coffee.jpg" alt="Dal Tokki Coffee Interior">
    </div>

    <!-- About Text -->
    <div class="about-text">
      <h2>About Dal Tokki Coffee</h2>
      <p>
        Chrysxava's <strong>Dal Tokki Café</strong> is a cozy, community-centered coffee shop
        in the heart of <strong>Rodriguez, Calabarzon</strong>. We serve carefully brewed coffee,
        delicious pastries, and signature drinks that bring people together.
      </p>
      <p>
        As a growing establishment, we are working towards integrating a
        <strong>smart order & loyalty system</strong> to improve efficiency,
        reduce customer wait times, and enhance your café experience.
      </p>
      <a href="#menu" class="about-btn">Discover Our Menu</a>
    </div>
  </div>
</section>


  <!-- ================= BEST SELLERS ================= -->
  <section class="menu" id="menu">
    <h2>Our Best-Sellers</h2>
    <div class="menu-cards">
      <div class="card">
        <img src="IMAGES/Blueberry~lemon~soda.jpeg" alt="lemon~soda">
        <h3>lemon~soda</h3>
        <p>"Crafted for refreshment, finished with a sparkle."</p>
      </div>
      <div class="card">
        <img src="IMAGES/Mango~lemon~soda.jpeg" alt="Mango~lemon~soda">
        <h3>Mango~lemon~soda</h3>
        <p>"Tropical sweetness meets citrus sparkle—refreshment in every sip.".</p>
      </div>
      <div class="card">
        <img src="IMAGES/Wildberry lemon black tea.jpeg" alt="Wildberry lemon black tea">
        <h3>Wildberry lemon black tea</h3>
        <p>"Bold black tea infused with wild berries and lemon—your perfect balance of fruity and strong."</p>
      </div>
    </div>
  </section>

 <!-- ================= LOYALTY PROGRAM ================= -->
<section class="loyalty" id="loyalty">
  <div class="loyalty-container">
    <!-- Left Side: Image -->
    <div class="loyalty-img">
      <img src="IMAGES/loyalty-card.jpg" alt="Dal Tokki Coffee Loyalty Card">
    </div>

    <!-- Right Side: Text -->
    <div class="loyalty-text">
      <h2>Dal Tokki Loyalty Program</h2>
      <p>
        Earn points every time you enjoy your favorite drinks at 
        <strong>Dal Tokki Café</strong>! Get exclusive rewards, 
        discounts, and early access to new seasonal beverages.
      </p>

      <!-- Benefits List -->
      <ul class="loyalty-benefits">
        <li>☕ <strong>Earn 1 Point</strong> for every ₱50 spent</li>
        <li>🎁 Redeem <strong>Free Drinks & Pastries</strong></li>
        <li>💳 Enjoy <strong>VIP Perks</strong> as a Gold Member</li>
        <li>📱 Access <strong>Exclusive Offers</strong> via our app</li>
      </ul>

      <!-- Join Button -->
      <a href="#join" class="loyalty-btn">Join Now</a>
    </div>
  </div>
</section>


  <!-- ================= CONTACT & LOCATION ================= -->
  <section class="contact" id="contact">
    <h2>Find Us</h2>
    <p>Chrysxava's Dal Tokki Café, Rodriguez, Calabarzon, Philippines</p>
<div class="map-container">
<div class="map-container">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18..."
    class="map-frame"
    title="Dal Tokki Coffee location on Google Maps"
    allowfullscreen 
    loading="lazy">
  </iframe>
</div>

</div>


    <div class="social">
      <a href="#" aria-label="Facebook">Facebook</a>
      <a href="#" aria-label="Instagram">Instagram</a>
      <a href="#" aria-label="Twitter">Twitter</a>
    </div>
  </section>

  <!-- ================= FOOTER ================= -->
  <footer>
    <p>© <?php echo date('Y'); ?> Dal Tokki Coffee. All rights reserved.</p>
  </footer>

  <!-- JavaScript -->
  <script src="main.js"></script>
</body>
</html>

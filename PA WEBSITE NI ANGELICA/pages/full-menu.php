<?php 
require_once '../includes/config.php';

// Redirect to dashboard for better UX
if (!is_logged_in()) {
    set_message('Please login to access your dashboard and full menu.', 'info');
    redirect('../auth/login.php');
} else {
    // If logged in, redirect to dashboard for better integrated experience
    redirect('dashboard.php');
}

$user = get_logged_in_user();
$message = get_message();

// Get all categories and products
$stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order");
$stmt->execute();
$categories = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM products WHERE is_available = 1 ORDER BY category_id, display_order");
$stmt->execute();
$products = $stmt->fetchAll();

// Group products by category
$products_by_category = [];
foreach ($products as $product) {
    $products_by_category[$product['category_id']][] = $product;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Menu - Dal Tokki Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .menu-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }
        
        .menu-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .menu-header h1 {
            color: var(--main-color);
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .welcome-message {
            background: linear-gradient(135deg, var(--main-color), #00e6a7);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .category-section {
            margin-bottom: 50px;
        }
        
        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--main-color);
        }
        
        .category-header h2 {
            color: var(--main-color);
            font-size: 28px;
            margin: 0;
            flex: 1;
        }
        
        .category-count {
            background: var(--main-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-light);
            transition: all var(--transition);
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 255, 200, 0.2);
        }
        
        .product-image {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 15px;
            background: var(--bg-dark);
            border: 2px solid rgba(0, 255, 200, 0.3);
        }
        
        .product-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--main-color);
            margin-bottom: 8px;
        }
        
        .product-description {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 255, 200, 0.2);
        }
        
        .product-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--main-color);
        }
        
        .bestseller-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff4757;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .user-info {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-details h3 {
            color: var(--main-color);
            margin: 0 0 5px 0;
        }
        
        
        .logout-btn {
            background: transparent;
            color: var(--text-light);
            border: 1px solid var(--text-light);
            padding: 8px 15px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-size: 14px;
            transition: all var(--transition);
        }
        
        .logout-btn:hover {
            background: var(--text-light);
            color: var(--bg-dark);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../assets/images/LOGO.jpg" alt="Dal Tokki Coffee Logo">
            <h1>Dal Tokki Coffee</h1>
        </div>
        
        <nav class="navbar">
            <a href="../index.php">Home</a>
            <a href="../index.php#about">About</a>
            <a href="#menu">Full Menu</a>
            <a href="../index.php#contact">Contact</a>
        </nav>
        
        <a href="cart.php" class="cart-btn" id="cart-button">
            🛒 Cart 
            <?php 
            $cart_count = get_cart_count();
            if ($cart_count > 0): 
            ?>
                <span class="cart-count"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    </header>

    <div class="menu-container">
        <div class="menu-header">
            <h1>Complete Menu</h1>
            <p>Discover all our delicious offerings</p>
        </div>

        <?php if ($message): ?>
            <div class="notification notification-<?php echo $message['type']; ?> show">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="user-info">
            <div class="user-details">
                <h3>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h3>
                <p style="color: var(--text-light); margin: 0;">Enjoy exclusive access to our complete menu</p>
            </div>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="../auth/logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <?php foreach ($categories as $category): ?>
            <?php if (isset($products_by_category[$category['id']])): ?>
                <div class="category-section" id="category-<?php echo $category['id']; ?>">
                    <div class="category-header">
                        <h2><?php echo htmlspecialchars($category['name']); ?></h2>
                        <div class="category-count">
                            <?php echo count($products_by_category[$category['id']]); ?> items
                        </div>
                    </div>
                    
                    <?php if ($category['description']): ?>
                        <p style="color: var(--text-light); margin-bottom: 25px;">
                            <?php echo htmlspecialchars($category['description']); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="products-grid">
                        <?php foreach ($products_by_category[$category['id']] as $product): ?>
                            <div class="product-card">
                                <?php if ($product['is_bestseller']): ?>
                                    <div class="bestseller-badge">Bestseller</div>
                                <?php endif; ?>
                                
                                <img src="<?php echo $product['image_url'] ?: '../assets/images/placeholder.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image">
                                
                                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                
                                <?php if ($product['description']): ?>
                                    <div class="product-description">
                                        <?php echo htmlspecialchars($product['description']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="product-footer">
                                    <div class="product-price"><?php echo format_price($product['price']); ?></div>
                                    <button class="add-to-cart-btn" 
                                            onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="continue-shopping" style="text-align: center; margin-top: 50px;">
            <a href="cart.php" class="full-menu-btn">View Cart & Checkout</a>
            <a href="../index.php" class="continue-btn" style="margin-left: 15px;">← Back to Home</a>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>

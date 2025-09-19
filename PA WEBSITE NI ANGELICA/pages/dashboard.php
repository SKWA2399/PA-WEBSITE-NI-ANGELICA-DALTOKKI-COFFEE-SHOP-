<?php 
require_once '../includes/config.php';

// Require login to access dashboard
if (!is_logged_in()) {
    set_message('Please login to access your dashboard.', 'info');
    redirect('../auth/login.php');
}

$user = get_logged_in_user();
$message = get_message();
$cart_items = get_cart_items();
$cart_total = get_cart_total();
$cart_count = get_cart_count();

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
    <title>Dashboard - Dal Tokki Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/root.css">
    <style>
        /* Ensure header styling matches index.php exactly */
        header {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            padding: 1.5rem 5% !important;
            background: white !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            z-index: 1000 !important;
            min-height: 80px !important;
        }

        .logo {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            flex-shrink: 0 !important;
        }

        .logo img {
            width: 50px !important;
            height: 50px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            background-color: #fff !important;
            border: 2px solid var(--primary-brown) !important;
        }

        .logo h1 {
            font-family: 'Dancing Script', cursive !important;
            font-size: 32px !important;
            font-weight: 600 !important;
            color: var(--primary-brown) !important;
            white-space: nowrap !important;
            letter-spacing: 0px !important;
        }

        .header-actions {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
        }

        .user-greeting {
            color: var(--text-medium) !important;
            font-weight: 500 !important;
            font-size: 14px !important;
        }

        .logout-btn {
            background: var(--accent-cream) !important;
            color: var(--primary-brown) !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: 1px solid var(--border-light) !important;
            font-size: 14px !important;
        }

        .logout-btn:hover {
            background: var(--accent-gold) !important;
            color: var(--primary-dark) !important;
            transform: translateY(-2px) !important;
        }

        .cart-btn {
            background: var(--primary-brown) !important;
            color: var(--bg-light) !important;
            padding: 10px 18px !important;
            border-radius: var(--border-radius) !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            transition: background var(--transition) !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .cart-btn:hover {
            background: var(--primary-dark) !important;
        }

        /* Mobile responsive for header actions */
        @media (max-width: 768px) {
            .header-actions {
                gap: 10px !important;
            }
            
            .user-greeting {
                display: none !important;
            }
            
            .cart-btn, .logout-btn {
                padding: 8px 12px !important;
                font-size: 13px !important;
            }
        }

        /* Dashboard Layout */
        .dashboard-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 0 20px;
            min-height: calc(100vh - 170px);
        }

        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--primary-dark) 100%);
            color: var(--bg-light);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px var(--shadow-medium);
        }

        .search-container {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 20px;
        }

        .search-bar {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            background: var(--bg-light);
            color: var(--text-dark);
            box-shadow: 0 2px 8px var(--shadow-light);
        }

        .search-bar::placeholder {
            color: var(--text-muted);
        }

        .search-bar:focus {
            outline: none;
            background: var(--bg-warm);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .user-welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .user-info h2 {
            margin: 0 0 5px 0;
            font-size: 28px;
        }

        .user-info p {
            margin: 0;
            opacity: 0.9;
        }

        .user-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .logout-btn {
            background: var(--accent-cream);
            color: var(--primary-brown);
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .logout-btn:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Menu Section */
        .menu-section {
            margin-bottom: 30px;
        }

        .menu-header {
            background: var(--main-color);
            color: white;
            padding: 20px 25px;
            margin: 0 0 30px 0;
            border-radius: 12px;
            text-align: center;
        }

        .menu-header h3 {
            margin: 0;
            font-size: 24px;
        }

        .menu-content {
            /* Remove container constraints - let it flow naturally */
        }

        .category-section {
            margin-bottom: 40px;
            padding-top: 25px;
            border-top: 1px solid var(--border-light);
        }

        .category-section:first-child {
            border-top: none;
            padding-top: 0;
        }

        .category-header {
            background: none;
            padding: 0 0 15px 0;
            border-radius: 0;
            border-left: none;
            box-shadow: none;
            border-bottom: none;
        }

        .category-header h4 {
            color: var(--primary-brown);
            font-size: 22px;
            margin: 0 0 8px 0;
            font-weight: 700;
        }

        .category-description {
            color: var(--text-medium);
            font-size: 14px;
            margin: 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .product-card {
            background: var(--card-dark);
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 4px 15px var(--shadow-medium);
            transition: all 0.3s ease;
            border: 1px solid var(--border-medium);
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px var(--shadow-strong);
            background: var(--card-dark-hover);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            object-position: center;
            display: block;
            border-radius: 0;
            background-color: var(--bg-accent);
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-on-dark);
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.3;
        }

        .product-description {
            font-size: 13px;
            color: var(--text-muted-dark);
            margin-bottom: 15px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .product-price {
            font-weight: 700;
            color: var(--accent-gold);
            font-size: 18px;
        }

        .add-btn {
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .add-btn:hover {
            background: var(--accent-cream);
            color: var(--primary-brown);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .bestseller-badge {
            background: var(--accent-gold);
            color: var(--primary-dark);
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            position: absolute;
            top: 10px;
            right: 10px;
            font-weight: 600;
            box-shadow: 0 2px 6px var(--shadow-light);
        }


        /* Responsive */
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin-top: 100px;
                padding: 0 15px;
            }

            .user-welcome {
                flex-direction: column;
                text-align: center;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }

            .category-header {
                padding: 0 0 12px 0;
            }

            .category-section {
                margin-bottom: 35px;
                padding-top: 20px;
            }

            .product-image {
                height: 180px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .product-image {
                height: 150px;
            }

            .product-info {
                padding: 12px;
            }

            .add-btn {
                padding: 8px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- ================= HEADER ================= -->
    <header>
        <div class="logo">
            <img src="../assets/images/icon.jpg" alt="Dal Tokki Cafe Logo">
            <h1>Dal Tokki Cafe</h1>
        </div>

        <div class="header-actions">
            <!-- Cart Button -->
            <a href="cart.php" class="cart-btn" id="cart-button">
                <i class="fas fa-shopping-cart"></i> Cart
                <?php if ($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>

            <!-- User Info & Logout -->
            <span class="user-greeting">Hi, <?php echo htmlspecialchars($user['full_name']); ?></span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message['type']; ?>" style="margin-bottom: 20px;">
                    <?php echo htmlspecialchars($message['text']); ?>
                </div>
            <?php endif; ?>
            
            <div class="user-welcome">
                <div class="user-info">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>! <i class="fas fa-coffee"></i></h2>
                    <p>Explore our full menu and discover new favorites</p>
                </div>
                <div class="user-actions">
                    <span style="opacity: 0.8;">Member since <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></span>
                    <a href="../auth/logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="search-container">
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); z-index: 1;"></i>
                    <input type="text" class="search-bar" id="menu-search" placeholder="Search menu items..." onkeyup="searchMenu()" style="padding-left: 45px;">
                </div>
            </div>
        </div>

        <!-- Menu Section -->
        <div class="menu-section" id="menu">
            <div class="menu-content">
                <?php foreach ($categories as $category): ?>
                    <?php if (isset($products_by_category[$category['id']])): ?>
                        <div class="category-section" id="category-<?php echo $category['id']; ?>">
                            <div class="category-header">
                                <h4><?php echo htmlspecialchars($category['name']); ?></h4>
                                <?php if ($category['description']): ?>
                                    <p class="category-description">
                                        <?php echo htmlspecialchars($category['description']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="products-grid">
                                <?php foreach ($products_by_category[$category['id']] as $product): ?>
                                    <div class="product-card" style="position: relative;">
                                        <?php if ($product['is_bestseller']): ?>
                                            <div class="bestseller-badge">Bestseller</div>
                                        <?php endif; ?>
                                        
                                        <img src="<?php echo $product['image_url'] ?: '../assets/images/placeholder.jpg'; ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                             class="product-image">
                                        
                                        <div class="product-info">
                                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                            
                                            <?php if ($product['description']): ?>
                                                <div class="product-description">
                                                    <?php echo htmlspecialchars($product['description']); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="product-footer">
                                                <span class="product-price"><?php echo format_price($product['price']); ?></span>
                                                <button class="add-btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
                                                    Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        // Dashboard-specific JavaScript
        function addToCart(productId, productName) {
            fetch('../api/add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`${productName} added to cart!`, 'success');
                    refreshCartSidebar();
                } else {
                    showNotification('Error adding item to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding item to cart', 'error');
            });
        }


        function searchMenu() {
            const searchTerm = document.getElementById('menu-search').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            const categoryHeaders = document.querySelectorAll('.category-header');
            
            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                const productDescription = card.querySelector('.product-description')?.textContent.toLowerCase() || '';
                
                if (productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Hide empty categories
            categoryHeaders.forEach(header => {
                const categorySection = header.closest('.category-section');
                const visibleProducts = categorySection.querySelectorAll('.product-card[style*="block"], .product-card:not([style*="none"])');
                
                if (searchTerm === '' || visibleProducts.length > 0) {
                    categorySection.style.display = 'block';
                } else {
                    categorySection.style.display = 'none';
                }
            });
        }

        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

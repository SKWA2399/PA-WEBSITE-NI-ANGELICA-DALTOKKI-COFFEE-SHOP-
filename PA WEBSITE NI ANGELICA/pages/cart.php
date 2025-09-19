<?php 
require_once '../includes/config.php';

// Require login to access cart
if (!is_logged_in()) {
    set_message('Please login to view your cart and place orders.', 'info');
    redirect('../auth/login.php?from=cart');
}

$cart_items = get_cart_items();
$cart_total = get_cart_total();
$message = get_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Dal Tokki Coffee</title>
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

        .back-btn {
            background: var(--primary-brown) !important;
            color: var(--bg-light) !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            font-size: 14px !important;
        }

        .back-btn:hover {
            background: var(--primary-dark) !important;
            transform: translateY(-2px) !important;
        }

        /* Mobile responsive for header actions */
        @media (max-width: 768px) {
            .header-actions {
                gap: 10px !important;
            }
            
            .user-greeting {
                display: none !important;
            }
            
            .back-btn, .logout-btn {
                padding: 8px 12px !important;
                font-size: 13px !important;
            }
        }
        .cart-container {
            max-width: 1000px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }
        
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .cart-header h1 {
            color: var(--main-color);
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: var(--bg-light);
            border-radius: 12px;
            margin: 40px 0;
        }
        
        .empty-cart h2 {
            color: var(--text-light);
            margin-bottom: 20px;
        }
        
        .cart-items {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(0, 255, 200, 0.2);
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 20px;
            background: var(--bg-dark);
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--main-color);
            margin-bottom: 5px;
        }
        
        .item-price {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 20px;
        }
        
        .qty-btn {
            background: var(--main-color);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
        }
        
        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid var(--main-color);
            border-radius: 4px;
            padding: 5px;
            background: var(--bg-dark);
            color: var(--text-color);
        }
        
        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            min-width: 100px;
        }
        
        .item-total {
            font-size: 18px;
            font-weight: 700;
            color: var(--main-color);
            text-align: right;
        }
        
        .remove-btn {
            background: #ff4757;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            transition: all var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-btn:hover {
            background: #ff3742;
            transform: scale(1.1);
        }
        
        /* Compact Checkout Bar */
        .compact-checkout-bar {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 255, 200, 0.1);
        }
        
        .total-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .total-label {
            font-size: 18px;
            color: var(--text-color);
            font-weight: 600;
        }
        
        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--main-color);
        }
        
        .checkout-actions {
            display: flex;
            gap: 15px;
        }
        
        .compact-checkout-btn {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        
        .compact-checkout-btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        
        .compact-auth-btn {
            background: var(--main-color);
            color: white;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition);
        }
        
        .compact-auth-btn:hover {
            background: #00e6a7;
            transform: translateY(-2px);
        }
        
        .auth-options {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .auth-options p {
            color: var(--text-light);
            margin: 0;
        }
        
        .register-link {
            color: var(--main-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link:hover {
            text-decoration: underline;
        }
        
        .checkout-section {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 30px;
            margin-top: 20px;
        }
        
        .login-prompt {
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, var(--main-color), #00e6a7);
            border-radius: 12px;
            color: white;
            margin-bottom: 20px;
        }
        
        .login-prompt h3 {
            margin-bottom: 15px;
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .auth-btn {
            padding: 12px 25px;
            border: 2px solid white;
            background: transparent;
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition);
        }
        
        .auth-btn:hover {
            background: white;
            color: var(--main-color);
        }
        
        .continue-shopping {
            text-align: center;
            margin-top: 30px;
        }
        
        .continue-btn {
            background: var(--bg-dark);
            color: var(--text-color);
            padding: 12px 25px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            border: 2px solid var(--main-color);
            transition: all var(--transition);
        }
        
        .continue-btn:hover {
            background: var(--main-color);
            color: white;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .compact-checkout-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 20px;
            }
            
            .total-section {
                justify-content: center;
            }
            
            .checkout-actions {
                width: 100%;
                justify-content: center;
            }
            
            .compact-checkout-btn,
            .compact-auth-btn {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Clean Cart Header -->
    <header>
        <div class="logo">
            <img src="../assets/images/icon.jpg" alt="Dal Tokki Cafe Logo">
            <h1>Dal Tokki Cafe</h1>
        </div>

        <div class="header-actions">
            <!-- Back to Dashboard Button -->
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Menu
            </a>

            <!-- User Info & Logout -->
            <span class="user-greeting">Hi, <?php echo htmlspecialchars(get_logged_in_user()['full_name']); ?></span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="cart-container">
        <div class="cart-header">
            <h1><?php echo empty($cart_items) ? 'Your shopping cart is empty' : 'Your Shopping Cart'; ?></h1>
            <?php if ($message): ?>
                <div class="notification notification-<?php echo $message['type']; ?> show">
                    <?php echo htmlspecialchars($message['text']); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Add some delicious items from our menu!</p>
                <div class="continue-shopping">
                    <a href="../index.php#menu" class="continue-btn">Browse Best Sellers</a>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['image_url'] ?: '../assets/images/placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="item-image">
                        
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="item-price"><?php echo format_price($item['price']); ?> each</div>
                        </div>
                        
                        <div class="item-quantity">
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                            <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" 
                                   onchange="setQuantity(<?php echo $item['id']; ?>, this.value)" min="1">
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                        </div>
                        
                        <div class="item-actions">
                            <div class="item-total"><?php echo format_price($item['total_price']); ?></div>
                            <button class="remove-btn" onclick="removeFromCart(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>')" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Compact Cart Summary Bar -->
            <?php if (!is_logged_in()): ?>
                <div class="compact-checkout-bar">
                    <div class="total-section">
                        <span class="total-label">Total:</span>
                        <span class="total-amount"><?php echo format_price($cart_total); ?></span>
                    </div>
                    <div class="checkout-actions">
                        <a href="../auth/login.php?from=cart" class="compact-auth-btn">Login to Checkout</a>
                    </div>
                </div>
                
                <div class="auth-options">
                    <p>Don't have an account? <a href="../auth/register.php?from=cart" class="register-link">Create one here</a></p>
                </div>
            <?php else: ?>
                <div class="compact-checkout-bar">
                    <div class="total-section">
                        <span class="total-label">Total:</span>
                        <span class="total-amount"><?php echo format_price($cart_total); ?></span>
                    </div>
                    <div class="checkout-actions">
                        <a href="checkout.php" class="compact-checkout-btn">Proceed to Checkout</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function updateQuantity(productId, change) {
            const input = document.querySelector(`input[onchange*="${productId}"]`);
            const newQty = Math.max(1, parseInt(input.value) + change);
            setQuantity(productId, newQty);
        }

        function setQuantity(productId, quantity) {
            quantity = Math.max(1, parseInt(quantity));
            
            fetch('../api/update-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.cart_count);
                    location.reload(); // Refresh to show updated totals
                } else {
                    showNotification('Error updating cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating cart', 'error');
            });
        }

        function removeFromCart(productId, productName) {
            if (confirm(`Remove ${productName} from cart?`)) {
                fetch('../api/update-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=0`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data.cart_count);
                        showNotification(`${productName} removed from cart`, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification('Error removing item', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error removing item', 'error');
                });
            }
        }

        function updateCartDisplay(count) {
            const cartBtn = document.querySelector('.cart-btn');
            const existingCount = cartBtn.querySelector('.cart-count');
            
            if (count > 0) {
                if (existingCount) {
                    existingCount.textContent = count;
                    // Trigger animation
                    existingCount.style.animation = 'none';
                    setTimeout(() => {
                        existingCount.style.animation = 'cartBounce 0.3s ease';
                    }, 10);
                } else {
                    const countSpan = document.createElement('span');
                    countSpan.className = 'cart-count';
                    countSpan.textContent = count;
                    cartBtn.appendChild(countSpan);
                }
            } else {
                if (existingCount) {
                    existingCount.remove();
                }
            }
        }
    </script>
</body>
</html>

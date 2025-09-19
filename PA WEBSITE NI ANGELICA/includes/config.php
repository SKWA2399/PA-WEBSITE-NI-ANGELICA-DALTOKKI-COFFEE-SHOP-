<?php
// Dal Tokki Coffee Configuration File
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dal_tokki_coffee');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty

// Site Configuration
define('SITE_NAME', 'Dal Tokki Coffee');
define('SITE_URL', 'http://localhost/dublinwebapp');
define('CURRENCY', '₱');


// Database Connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    // More detailed error for debugging
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check if XAMPP MySQL is running and database 'dal_tokki_coffee' exists. Error: " . $e->getMessage());
}

// Security Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Cart Functions
function get_cart_count() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return array_sum($_SESSION['cart']);
}

function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    return true;
}

function get_cart_items() {
    global $pdo;
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll();
    
    $cart_items = [];
    foreach ($products as $product) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['total_price'] = $product['price'] * $product['quantity'];
        $cart_items[] = $product;
    }
    
    return $cart_items;
}

function get_cart_total() {
    $cart_items = get_cart_items();
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['total_price'];
    }
    return $total;
}

// User Functions
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_logged_in_user() {
    global $pdo;
    
    if (!is_logged_in()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Utility Functions
function format_price($price) {
    return CURRENCY . number_format($price, 2);
}

function generate_order_number() {
    return 'DTC' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// Error and Success Messages
function set_message($message, $type = 'info') {
    $_SESSION['message'] = [
        'text' => $message,
        'type' => $type
    ];
}

function get_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}
?>

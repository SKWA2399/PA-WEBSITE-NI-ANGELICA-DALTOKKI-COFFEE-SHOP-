<?php
// Simple database connection test
echo "<h2>Database Connection Test</h2>";

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dal_tokki_coffee');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    echo "<p>1. Attempting to connect to MySQL...</p>";
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    echo "<p style='color: green;'>✓ MySQL connection successful!</p>";
    
    echo "<p>2. Checking if database 'dal_tokki_coffee' exists...</p>";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'dal_tokki_coffee'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Database 'dal_tokki_coffee' exists!</p>";
        
        echo "<p>3. Connecting to dal_tokki_coffee database...</p>";
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        echo "<p style='color: green;'>✓ Connected to dal_tokki_coffee database!</p>";
        
        echo "<p>4. Checking if 'users' table exists...</p>";
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Users table exists!</p>";
            
            echo "<p>5. Checking if admin user exists...</p>";
            $stmt = $pdo->prepare("SELECT username FROM users WHERE username = 'admin'");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "<p style='color: green;'>✓ Admin user exists!</p>";
                echo "<p><strong>You can login with:</strong><br>Username: admin<br>Password: admin123</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Admin user not found. Creating admin user...</p>";
                $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES ('admin', 'admin@daltokki.com', ?, 'Admin User')");
                $stmt->execute([$hashed_password]);
                echo "<p style='color: green;'>✓ Admin user created! Username: admin, Password: admin123</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Users table not found! Please import dal_tokki_coffee.sql</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Database 'dal_tokki_coffee' not found!</p>";
        echo "<p><strong>Please:</strong></p>";
        echo "<ol>";
        echo "<li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Import the file: dal_tokki_coffee.sql</li>";
        echo "</ol>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Common solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Make sure XAMPP is running (Apache + MySQL)</li>";
    echo "<li>Check if MySQL service is started</li>";
    echo "<li>Import dal_tokki_coffee.sql in phpMyAdmin</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Homepage</a></p>";
?>

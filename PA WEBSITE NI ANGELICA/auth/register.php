<?php 
require_once '../includes/config.php';

// Clear any existing messages when coming from other pages
if (isset($_GET['from']) && in_array($_GET['from'], ['login', 'cart'])) {
    unset($_SESSION['message']);
}

// Redirect if already logged in
if (is_logged_in()) {
    redirect('../pages/dashboard.php');
}

$message = get_message();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($full_name)) {
        $errors[] = 'Full name is required';
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $errors[] = 'Username or email already exists';
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $full_name, $phone]);
                
                $user_id = $pdo->lastInsertId();
                
                // Auto-login the new user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = $full_name;
                
                set_message('Welcome to Dal Tokki Cafe, ' . $full_name . '!', 'success');
                redirect('../pages/dashboard.php');
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $errors[] = 'Registration failed. Please try again.';
        }
    }
    
    if (!empty($errors)) {
        set_message(implode('<br>', $errors), 'error');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Dal Tokki Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/root.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-light) 100%);
        }
        
        .auth-card {
            background: var(--card-dark);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px var(--shadow-strong);
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-medium);
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .auth-header h1 {
            color: var(--text-on-dark);
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .auth-header p {
            color: var(--text-muted-dark);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-on-dark);
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-medium);
            border-radius: 8px;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .auth-btn {
            width: 100%;
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .auth-btn:hover {
            background: var(--accent-cream);
            color: var(--primary-brown);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }
        
        .auth-links {
            text-align: center;
        }
        
        .auth-links p {
            color: var(--text-on-dark);
        }
        
        .auth-links a {
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--text-muted-dark);
            text-decoration: none;
        }
        
        .back-link a:hover {
            color: var(--accent-gold);
        }
        
        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #ff4757, #ff3742);
            color: white;
            border: 1px solid #ff3742;
        }
        
        .alert-success {
            background: #00cc6a;
            color: white;
            border: 1px solid #00cc6a;
        }
        
        .alert-info {
            background: linear-gradient(135deg, var(--main-color), #00e6a7);
            color: white;
            border: 1px solid var(--main-color);
        }
        
        .benefits {
            background: rgba(212, 175, 122, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--border-light);
        }
        
        .benefits h3 {
            color: var(--accent-gold);
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .benefits ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .benefits li {
            color: var(--text-muted-dark);
            font-size: 14px;
            margin-bottom: 5px;
            padding-left: 20px;
            position: relative;
        }
        
        .benefits li:before {
            content: '✓';
            color: var(--accent-gold);
            font-weight: bold;
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Join Dal Tokki Cafe</h1>
                <p>Create your account and start ordering!</p>
            </div>
            
            <div class="benefits">
                <h3>Account Benefits:</h3>
                <ul>
                    <li>Access to full menu and exclusive items</li>
                    <li>Faster checkout with saved details</li>
                    <li>Order history and favorites</li>
                </ul>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <button type="submit" class="auth-btn">Create Account</button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="login.php?from=register">Login here</a></p>
            </div>
            
            <div class="back-link">
                <a href="../index.php">← Back to Home</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>

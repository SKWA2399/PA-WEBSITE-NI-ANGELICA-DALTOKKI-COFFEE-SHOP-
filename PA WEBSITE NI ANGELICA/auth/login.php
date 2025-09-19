<?php 
require_once '../includes/config.php';

// Clear any existing messages when coming from other pages
if (isset($_GET['from']) && in_array($_GET['from'], ['register', 'cart'])) {
    unset($_SESSION['message']);
}

// Redirect if already logged in
if (is_logged_in()) {
    redirect('../pages/dashboard.php');
}

$message = get_message();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        set_message('Please fill in all fields', 'error');
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                
                set_message('Welcome back, ' . $user['full_name'] . '!', 'success');
                redirect('../pages/dashboard.php');
            } else {
                set_message('Invalid username or password', 'error');
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            set_message('Login failed. Please try again.', 'error');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dal Tokki Cafe</title>
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
            max-width: 400px;
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
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Login to your Dal Tokki Cafe account</p>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message['type']; ?>">
                    <?php echo htmlspecialchars($message['text']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="auth-btn">Login</button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="register.php?from=login">Create one here</a></p>
            </div>
            
            <div class="back-link">
                <a href="../index.php">← Back to Home</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>

<?php
// admin/login.php – DB based admin authentication
require_once __DIR__.'/../includes/config.php';

// Generate CSRF token for the form
$csrf_token = generate_csrf_token();

// Brute-force protection: track attempts per IP in session/time
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_login_attempt'] = time();
}

// Reset attempts after 15 minutes of inactivity
if (time() - $_SESSION['last_login_attempt'] > 900) {
    $_SESSION['login_attempts'] = 0;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if account/IP is temporarily throttled
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_login_attempt'] < 300)) {
        $remaining = 300 - (time() - $_SESSION['last_login_attempt']);
        $error = "Too many failed attempts. Please wait " . ceil($remaining / 60) . " minute(s) before trying again.";
    } elseif (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security CSRF token. Please refresh and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = getUserByUsername($pdo, $username);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Successful login: reset attempts and regenerate session
            $_SESSION['login_attempts'] = 0;
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_login_attempt'] = time();
            // Constant time delay to prevent timing attacks
            usleep(200000); 
            $error = 'Invalid credentials. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Screen - Parijat Resort Admin</title>
    
    <meta name="description" content="Login Screen - Parijat Resort">
    
    <!-- Local Third-Party Libraries (100% Offline Compatible) -->
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/bootstrap-icons/bootstrap-icons.css">
    
    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <!-- ==========================================
         START: Authentication Container & Login Card
         ========================================== -->
    <div class="login-wrapper">
        <!-- Glowing background shapes for modern visual appearance -->
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>
        
        <!-- Main centered login card -->
        <div class="login-card">
            
            <!-- Brand Identity -->
            <a href="index.php" class="login-brand text-decoration-none text-center d-block mb-3">
                <i class="bi bi-asterisk"></i>
                <span>Parijat Resort Admin</span>
            </a>
            
            <p class="login-subtitle">Please sign in to access your dashboard</p>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form action="login.php" method="POST" id="loginForm" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <!-- Username Input Group -->
                <div class="login-form-group">
                    <label for="username" class="login-form-label">Username</label>
                    <div class="login-input-group">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="username" id="username" class="login-input" placeholder="admin" required>
                    </div>
                </div>
                
                <!-- Password Input Group -->
                <div class="login-form-group">
                    <label for="password" class="login-form-label">Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="login-input login-input-password" placeholder="••••••••" required>
                        <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-login mt-4" id="btn-submit">
                    <span>Sign In to Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
                
            </form>
            
        </div>
    </div>
    <!-- END: Authentication Container -->

    <!-- Local Bootstrap bundle -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Authentication interactions script -->
    <script src="assets/js/auth.js"></script>
</body>
</html>

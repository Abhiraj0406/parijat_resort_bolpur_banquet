<?php
/**
 * config.php – Global configuration and PDO connection
 *
 * Keep this file outside of web‑root in production. For this demo we place it
 * in `includes/` and protect it via `.htaccess` (will be added later).
 */

// ----- Database credentials (use environment variables in real deployment) -----
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'parijat_resort';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // In production we would log this error instead of displaying it.
    error_log('Database connection failed: ' . $e->getMessage());
    // Show generic message to user
    die('Database connection error.');
}

// ----- Session settings (secure) -----
session_name('parijat_admin');
// Use httponly and secure flags if served over HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
ini_set('session.cookie_httponly', '1');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----- CSRF helper functions -----
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ----- SMTP configuration (placeholders, update with real credentials) -----
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USER', 'user@example.com');
define('SMTP_PASS', 'secret');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('FROM_EMAIL', 'no-reply@example.com');
define('FROM_NAME', 'Parijat Resort');

// Helper to fetch an admin by username
function getUserByUsername(PDO $pdo, string $username): ?array {
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username AND status = "active"');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    return $user ?: null;
}
?>

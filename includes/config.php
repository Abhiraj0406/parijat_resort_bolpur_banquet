<?php
/**
 * config.php – Global configuration and PDO connection
 *
 * Keep this file outside of web‑root in production. For this demo we place it
 * in `includes/` and protect it via `.htaccess` (will be added later).
 */

// ----- Load Environment Variables from .env -----
require_once __DIR__ . '/env.php';

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
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // In production we would log this error instead of displaying it.
    error_log('Database connection failed: ' . $e->getMessage());
    // Show generic message to user
    die('Database connection error.');
}

// Auto-sync: ensure room categories and gallery use Queen Bed everywhere
try {
    $pdo->exec("UPDATE room_categories SET description = 'Spacious cottage rooms nestled amidst serene garden landscapes, featuring private sit-out balconies, comfortable queen bed, high-speed WiFi, LED television, tea/coffee station, and premium bathroom amenities.' WHERE id = 1");
    $pdo->exec("UPDATE room_categories SET description = 'Convenient ground-floor executive rooms with direct lawn and garden access, private balcony, comfortable queen bed, and modern ensuite bathroom.' WHERE id = 2");
    $pdo->exec("UPDATE room_categories SET description = 'Elevated premium rooms offering panoramic views of the landscaped grounds and swimming pool, tranquil private balcony, and peaceful natural ambiance.' WHERE id = 3");
    $pdo->exec("UPDATE room_categories SET description = 'Expansive suite designed for family vacations or small groups of up to four guests, featuring dual queen beds, spacious balcony, and modern conveniences.' WHERE id = 4");
    $pdo->exec("UPDATE room_categories SET facilities = REPLACE(facilities, 'King Bed', 'Queen Bed') WHERE facilities LIKE '%King Bed%'");
    $pdo->exec("UPDATE room_categories SET facilities = REPLACE(facilities, 'king bed', 'Queen Bed') WHERE facilities LIKE '%king bed%'");
    $pdo->exec("UPDATE gallery SET caption = REPLACE(caption, 'King Bed', 'Queen Bed') WHERE caption LIKE '%King Bed%'");
    $pdo->exec("UPDATE gallery SET caption = REPLACE(caption, 'king bed', 'Queen Bed') WHERE caption LIKE '%king bed%'");
} catch (Exception $e) {}

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

// ----- SMTP configuration (dynamically loaded from .env) -----
defined('SMTP_HOST')   || define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.example.com');
defined('SMTP_USER')   || define('SMTP_USER', getenv('SMTP_USER') ?: 'user@example.com');
defined('SMTP_PASS')   || define('SMTP_PASS', getenv('SMTP_PASS') ?: 'secret');
defined('SMTP_PORT')   || define('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));
defined('SMTP_SECURE') || define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'tls');
defined('FROM_EMAIL')  || define('FROM_EMAIL', getenv('FROM_EMAIL') ?: 'no-reply@example.com');
defined('FROM_NAME')   || define('FROM_NAME', getenv('FROM_NAME') ?: 'Parijat Resort');

// Helper to fetch an admin by username
function getUserByUsername(PDO $pdo, string $username): ?array {
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username AND status = "active"');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    return $user ?: null;
}
?>

<?php
/**
 * includes/bootstrap.php – Application Bootstrap for Front Controller
 *
 * Establishes core system paths, environment configuration, database connection,
 * security headers, and common runtime helpers.
 */

// 1. Define Core Path Constants
defined('ROOT_PATH')   || define('ROOT_PATH', dirname(__DIR__));
defined('APP_PATH')    || define('APP_PATH', __DIR__);
defined('PUBLIC_PATH') || define('PUBLIC_PATH', ROOT_PATH);

// 2. Load Configuration and Database PDO Instance
require_once __DIR__ . '/config.php';

// 3. Emit Core HTTP Security Headers
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

// 4. Global URL Helper
if (!function_exists('base_url')) {
    /**
     * Generate an absolute or relative URL based on APP_URL
     *
     * @param string $path
     * @return string
     */
    function base_url(string $path = ''): string {
        $baseUrl = rtrim(getenv('APP_URL') ?: '', '/');
        $path = ltrim($path, '/');
        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

// 5. Safe HTML Escaper Helper
if (!function_exists('e')) {
    /**
     * Escape strings for safe HTML output
     *
     * @param string|null $value
     * @return string
     */
    function e(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

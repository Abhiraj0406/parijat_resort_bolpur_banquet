<?php
/**
 * includes/router.php – Front Controller Router Engine
 *
 * Resolves clean URLs, handles HTTP methods, dispatches to page views
 * and form processors, performs canonical 301 redirects for legacy .php URLs,
 * and renders the 404 page for unmatched routes.
 */

class Router {
    /**
     * Normalize the current request URI relative to script base directory.
     *
     * @return string Normalized relative route (e.g. '', 'about', 'contact')
     */
    public static function getRequestPath(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        // Detect base directory if project is in a subdirectory (e.g. /parijat_resort_bolpur_banquet/)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir    = str_replace('\\', '/', dirname($scriptName));
        
        if ($baseDir !== '/' && strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }

        return trim($uri, '/');
    }

    /**
     * Dispatch the current request to the appropriate handler.
     *
     * @param PDO $pdo Active database connection
     * @return void
     */
    public static function dispatch(PDO $pdo): void {
        $path   = self::getRequestPath();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Canonical 301 redirect for legacy .php URLs (e.g. /about.php -> /about)
        if (preg_match('/^([a-zA-Z0-9_-]+)\.php$/i', $path, $matches)) {
            $cleanRoute = $matches[1];
            if ($cleanRoute === 'index') {
                $cleanRoute = '';
            }
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $baseDir    = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
            if (function_exists('base_url') && getenv('APP_URL')) {
                $targetUrl = base_url($cleanRoute);
            } else {
                $targetUrl = $baseDir . ($cleanRoute !== '' ? '/' . $cleanRoute : '/');
            }
            if (!empty($_SERVER['QUERY_STRING'])) {
                $targetUrl .= '?' . $_SERVER['QUERY_STRING'];
            }
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $targetUrl);
            exit;
        }

        // Global variables for page views
        global $site_settings;

        // Route Table Mapping
        switch ($path) {
            case '':
            case 'home':
            case 'index':
                $current_page = 'home';
                // Serve homepage
                if (file_exists(ROOT_PATH . '/views/home.php')) {
                    require ROOT_PATH . '/views/home.php';
                }
                break;

            case 'about':
            case 'about-us':
                $current_page = 'about';
                require ROOT_PATH . '/about.php';
                break;

            case 'accomodation':
            case 'accommodation':
            case 'rooms':
                $current_page = 'accomodation';
                require ROOT_PATH . '/accomodation.php';
                break;

            case 'dining':
            case 'restaurant':
                $current_page = 'dining';
                require ROOT_PATH . '/dining.php';
                break;

            case 'banquet':
            case 'events':
            case 'hall':
                $current_page = 'banquet';
                require ROOT_PATH . '/banquet.php';
                break;

            case 'gallery':
            case 'photos':
                $current_page = 'gallery';
                require ROOT_PATH . '/gallery.php';
                break;

            case 'rules':
            case 'policies':
            case 'terms':
                $current_page = 'rules';
                require ROOT_PATH . '/rules.php';
                break;

            case 'contact':
            case 'contact-us':
                $current_page = 'contact';
                require ROOT_PATH . '/contact.php';
                break;

            case 'booking':
            case 'book':
            case 'enquiry':
                $current_page = 'booking';
                require ROOT_PATH . '/booking.php';
                break;

            case 'book_process':
            case 'book-process':
                require ROOT_PATH . '/book_process.php';
                break;

            case 'contact_process':
            case 'contact-process':
                require ROOT_PATH . '/contact_process.php';
                break;

            case 'confirmation':
                $current_page = 'confirmation';
                require ROOT_PATH . '/confirmation.php';
                break;

            default:
                // 404 Not Found
                http_response_code(404);
                $current_page = '404';
                if (file_exists(ROOT_PATH . '/404.php')) {
                    require ROOT_PATH . '/404.php';
                } else {
                    echo "<h1>404 Not Found</h1><p>The requested page does not exist.</p>";
                }
                break;
        }
    }
}

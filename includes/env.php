<?php
/**
 * includes/env.php – Zero-Dependency .env Environment Variable Loader
 *
 * Automatically parses key-value pairs from .env files, safely handling
 * comments, quotes, inline values, and populates $_ENV, $_SERVER, and putenv().
 */

if (!function_exists('load_env')) {
    /**
     * Load environment variables from a given file path.
     *
     * @param string|null $filePath Path to .env file (optional; auto-detects if null)
     * @return bool True if loaded successfully, false otherwise.
     */
    function load_env(?string $filePath = null): bool {
        static $loaded = false;
        if ($loaded && $filePath === null) {
            return true;
        }

        // Auto-detect path if not specified
        if ($filePath === null) {
            $candidates = [
                __DIR__ . '/../.env',                  // Standard root layout
                __DIR__ . '/../../.env',               // Two-tier public_html layout (one level above)
                dirname(__DIR__, 2) . '/parijat_app/.env', // Dedicated private app folder layout
                $_SERVER['DOCUMENT_ROOT'] . '/../.env', // Standard cPanel above webroot
                $_SERVER['DOCUMENT_ROOT'] . '/.env'    // Inside webroot (protected via .htaccess)
            ];

            foreach ($candidates as $candidate) {
                if (file_exists($candidate) && (is_readable($candidate) || is_file($candidate))) {
                    $filePath = realpath($candidate) ?: $candidate;
                    break;
                }
            }
        }

        if (!$filePath || !file_exists($filePath) || (!is_readable($filePath) && !is_file($filePath))) {
            return false;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Split into key and value by the first '=' character
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key   = trim($parts[0]);
            $value = trim($parts[1]);

            // Remove surrounding inline comment if value is not enclosed in quotes
            if (!str_starts_with($value, '"') && !str_starts_with($value, "'")) {
                $commentPos = strpos($value, '#');
                if ($commentPos !== false) {
                    $value = trim(substr($value, 0, $commentPos));
                }
            }

            // Strip enclosing double or single quotes
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            // Cast boolean / null equivalents
            $lowerValue = strtolower($value);
            if ($lowerValue === 'true') {
                $parsedValue = true;
            } elseif ($lowerValue === 'false') {
                $parsedValue = false;
            } elseif ($lowerValue === 'null') {
                $parsedValue = null;
            } elseif ($lowerValue === 'empty') {
                $parsedValue = '';
            } else {
                $parsedValue = $value;
            }

            // Set in $_ENV and $_SERVER
            $_ENV[$key]    = $parsedValue;
            $_SERVER[$key] = $parsedValue;

            // Put in environment if value is string or scalar
            if (is_scalar($parsedValue)) {
                putenv("$key=$value");
            }
        }

        $loaded = true;
        return true;
    }
}

if (!function_exists('env')) {
    /**
     * Retrieve an environment variable with an optional fallback default.
     *
     * @param string $key Variable name
     * @param mixed $default Fallback value if not set
     * @return mixed
     */
    function env(string $key, $default = null) {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        if ($val !== false) {
            $lowerVal = strtolower($val);
            if ($lowerVal === 'true') return true;
            if ($lowerVal === 'false') return false;
            if ($lowerVal === 'null') return null;
            return $val;
        }
        return $default;
    }
}

// Auto-load on include
load_env();

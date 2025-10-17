<?php
/**
 * index.php (root) — forward requests to Laravel's public/index.php
 *
 * Place this file in your project root if your webserver's document root
 * cannot be pointed at the "public" directory. It simply delegates to
 * public/index.php while keeping built-in server handling for static files.
 */

$publicPath = __DIR__ . '/public';
$publicIndex = $publicPath . '/index.php';

if (!file_exists($publicIndex)) {
    http_response_code(500);
    echo 'Error: public/index.php not found. Ensure you have a Laravel "public" directory.';
    exit(1);
}

// When using PHP's built-in server, let it serve static files from public/
if (php_sapi_name() === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $file = $publicPath . ($url ?: '/');

    if ($file !== $publicIndex && file_exists($file) && is_file($file)) {
        // Return false so the built-in server serves the requested resource.
        return false;
    }
}

// Change working directory to public and require its index
chdir($publicPath);
require $publicIndex;
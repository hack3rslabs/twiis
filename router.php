<?php
/**
 * Twiis Innovations Local Development Router
 * Mimics Apache .htaccess rewrite rules for clean URLs and PHP APIs.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$filePath = __DIR__ . $uri;

// 1. Direct file access (CSS, JS, Images, etc.)
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    // Let the PHP built-in server serve static files directly with correct MIME types
    return false;
}

// 2. Root Access
if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/index.html')) {
        require __DIR__ . '/index.html';
        return true;
    }
}

// 3. Clean URLs for directories (e.g., /services/ or /services)
if (is_dir($filePath)) {
    $normalizedPath = rtrim($filePath, '/');
    if (file_exists($normalizedPath . '/index.html')) {
        require $normalizedPath . '/index.html';
        return true;
    } elseif (file_exists($normalizedPath . '/index.php')) {
        require $normalizedPath . '/index.php';
        return true;
    }
}

// 4. Clean URLs without extension (.html first, then .php)
if (file_exists($filePath . '.html')) {
    require $filePath . '.html';
    return true;
}
if (file_exists($filePath . '.php')) {
    require $filePath . '.php';
    return true;
}

// 5. Clean URLs for sub-paths (e.g., /services/it-solutions)
$cleanPath = rtrim($filePath, '/');
if (file_exists($cleanPath . '.html')) {
    require $cleanPath . '.html';
    return true;
}
if (file_exists($cleanPath . '.php')) {
    require $cleanPath . '.php';
    return true;
}

// 6. Support for index files inside folders matching route (e.g. /services/it-solutions/index.html)
if (file_exists($cleanPath . '/index.html')) {
    require $cleanPath . '/index.html';
    return true;
}
if (file_exists($cleanPath . '/index.php')) {
    require $cleanPath . '/index.php';
    return true;
}

// 7. Custom 404 Fallback
if (file_exists(__DIR__ . '/404/index.html')) {
    http_response_code(404);
    require __DIR__ . '/404/index.html';
    return true;
}

return false;

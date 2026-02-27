<?php
// Front controller for PHP built-in server
// Simulates Apache mod_rewrite behavior

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route /app/* to router.php
if (preg_match('#^/app(/.*)?$#', $uri, $matches)) {
    $_GET['path'] = isset($matches[1]) ? trim($matches[1], '/') : '';
    require __DIR__ . '/router.php';
    exit;
}

// Route everything else to index.php
require __DIR__ . '/index.php';

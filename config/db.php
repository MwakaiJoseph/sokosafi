<?php
// Simple PDO configuration. Update credentials to match your local MySQL.

if (file_exists(__DIR__ . '/config.local.php')) {
    include_once __DIR__ . '/config.local.php';
}

if (!defined('DB_HOST'))
    define('DB_HOST', 'localhost');
if (!defined('DB_NAME'))
    define('DB_NAME', 'ecommerce_db');
if (!defined('DB_USER'))
    define('DB_USER', 'root');
if (!defined('DB_PASS'))
    define('DB_PASS', '');

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
catch (Throwable $e) {
    // In dev, show a friendly message; in prod, log this instead.
    $pdo = null;
    error_log('DB connection failed: ' . $e->getMessage());
}
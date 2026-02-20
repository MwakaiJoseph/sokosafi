<?php
// Simple PDO configuration. Update credentials to match your local MySQL.

if (file_exists(__DIR__ . '/config.local.php')) {
    include_once __DIR__ . '/config.local.php';
}

if (!defined('DB_HOST'))
    define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
if (!defined('DB_NAME'))
    define('DB_NAME', getenv('MYSQLDATABASE') ?: 'ecommerce_db');
if (!defined('DB_USER'))
    define('DB_USER', getenv('MYSQLUSER') ?: 'root');
if (!defined('DB_PASS'))
    // Hosted environments might use an empty or zero password, so we check carefully
    define('DB_PASS', getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : '');

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
catch (Throwable $e) {
    // Collect available environment variable keys to see what Railway provided
    $env_keys = implode(', ', array_keys($_ENV));
    $server_keys = implode(', ', array_keys($_SERVER));
    $dbg = "DB connection failed: " . $e->getMessage() . "<br>";
    $dbg .= "Tried connecting to: " . DB_HOST . " as " . DB_USER . "<br>";
    $dbg .= "Available ENV keys: " . $env_keys . "<br>";
    $dbg .= "Available SERVER keys: " . $server_keys . "<br>";
    // Also check getenv manually
    $dbg .= "getenv('MYSQLHOST') = " . (getenv('MYSQLHOST') ?: 'false/empty');
    if (!headers_sent())
        header('HTTP/1.1 500 Internal Server Error');
    die($dbg);
}
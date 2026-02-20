<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/db_functions.php';

$products = get_products(100);
foreach ($products as $p) {
    echo "ID: " . $p['id'] . " | Name: " . $p['name'] . " | Category: " . ($p['categories'] ?? 'None') . " | Image: " . ($p['image_path'] ?? 'None') . "\n";
}

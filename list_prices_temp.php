<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/db_functions.php';

$products = get_products(1000); // Get all
foreach ($products as $p) {
    echo "ID: {$p['id']} | Name: {$p['name']} | Price: {$p['price']} | Sale: {$p['sale_price']}\n";
}

<?php
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("SELECT id, name FROM products WHERE id = 1");
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    echo "Product ID 1 is: " . $product['name'] . "\n";
}
else {
    echo "Product ID 1 does not exist.\n";
}
?>

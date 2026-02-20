<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Check a few diverse products (7: Electronics, 107: Home, 183: Shoes)
    $stmt = $pdo->query("SELECT id, name, price, sale_price FROM products WHERE id IN (7, 107, 183)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "--- Current Prices for Sample Products ---\n";
    foreach ($products as $p) {
        $sp = $p['sale_price'] ? $p['sale_price'] : "(None)";
        echo "ID {$p['id']}: {$p['name']} | Price: {$p['price']} | Sale: $sp\n";
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

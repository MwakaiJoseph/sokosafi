<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Electronics category ID is 2
    $catId = 2;

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, 
               (SELECT COUNT(*) FROM product_images pi WHERE pi.product_id = p.id) as image_count
        FROM products p 
        JOIN product_category pc ON p.id = pc.product_id
        WHERE pc.category_id = ?
        ORDER BY p.id ASC
    ");
    $stmt->execute([$catId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "--- Image Records for Electronics (Category $catId) ---\n";
    $missing_count = 0;
    foreach ($products as $p) {
        if ($p['image_count'] == 0) {
            echo "product [{$p['id']}] {$p['name']} - MISSING IMAGE RECORD\n";
            $missing_count++;
        }
        else {
            echo "product [{$p['id']}] {$p['name']} - OK ({$p['image_count']} images)\n";
        }
    }

    if ($missing_count > 0) {
        echo "\nTotal products missing image records: $missing_count\n";
    }
    else {
        echo "\nAll products have image records.\n";
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

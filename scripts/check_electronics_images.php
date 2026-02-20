<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Get Electronics category ID (usually 2, judging from previous steps)
    // But let's look it up
    $stmtCat = $pdo->query("SELECT id FROM categories WHERE name = 'Electronics'");
    $catId = $stmtCat->fetchColumn();

    if (!$catId) {
        die("Electronics category not found.\n");
    }

    echo "Electronics Category ID: $catId\n\n";

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.image, pi.image_path 
        FROM products p 
        JOIN product_category pc ON p.id = pc.product_id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE pc.category_id = ?
        LIMIT 10
    ");
    $stmt->execute([$catId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $p) {
        echo "ID: {$p['id']} | Name: {$p['name']} | Main Image (col): {$p['image']} | Gallery Image (tbl): {$p['image_path']}\n";
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

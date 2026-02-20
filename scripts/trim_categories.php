<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Categories to trim
    $categories = [
        2 => 'Electronics',
        7 => 'Fashion',
        8 => 'Home & Living',
        9 => 'Beauty',
        10 => 'Accessories',
        11 => 'Shoes'
    ];

    foreach ($categories as $cat_id => $cat_name) {
        // Find products in this category
        $stmt = $pdo->prepare("SELECT product_id FROM product_category WHERE category_id = ? ORDER BY product_id ASC");
        $stmt->execute([$cat_id]);
        $all_products = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $total = count($all_products);
        if ($total > 30) {
            $to_keep = array_slice($all_products, 0, 30);
            $to_delete = array_slice($all_products, 30);

            $delete_count = count($to_delete);
            echo "Category: $cat_name (ID: $cat_id) - Found $total products. Deleting $delete_count extras.\n";

            // Delete logic
            $placeholders = implode(',', array_fill(0, count($to_delete), '?'));

            // Delete from products table (and cascade usually handles the rest, but let's be safe)
            $stmtDel = $pdo->prepare("DELETE FROM products WHERE id IN ($placeholders)");
            $stmtDel->execute($to_delete);

            // Cleanup mapping table too just in case
            $stmtDelCat = $pdo->prepare("DELETE FROM product_category WHERE product_id IN ($placeholders)");
            $stmtDelCat->execute($to_delete);

            // Cleanup images
            $stmtDelImg = $pdo->prepare("DELETE FROM product_images WHERE product_id IN ($placeholders)");
            $stmtDelImg->execute($to_delete);

            // Cleanup reviews
            $stmtDelRev = $pdo->prepare("DELETE FROM reviews WHERE product_id IN ($placeholders)");
            $stmtDelRev->execute($to_delete);

            // Cleanup order items
            $stmtDelOrd = $pdo->prepare("DELETE FROM order_items WHERE product_id IN ($placeholders)");
            $stmtDelOrd->execute($to_delete);

        }
        else {
            echo "Category: $cat_name (ID: $cat_id) - Count is $total. No action needed.\n";
        }
    }

    echo "\n--- Trim Complete ---\n";

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

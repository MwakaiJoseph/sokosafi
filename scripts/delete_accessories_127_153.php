<?php
require_once __DIR__ . '/../config/db.php';

try {
    $start_id = 127;
    $end_id = 153;

    // Check count before delete
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE id BETWEEN ? AND ?");
    $stmtCheck->execute([$start_id, $end_id]);
    $count = $stmtCheck->fetchColumn();

    echo "Found $count products between ID $start_id and $end_id.\n";

    if ($count > 0) {
        // Delete products
        $stmtDelete = $pdo->prepare("DELETE FROM products WHERE id BETWEEN ? AND ?");
        $stmtDelete->execute([$start_id, $end_id]);

        echo "Successfully deleted $count products (IDs $start_id - $end_id).\n";

        // Clean up related tables just in case (though cascade might handle it, explicit is safer if no FKs)
        $pdo->prepare("DELETE FROM product_category WHERE product_id BETWEEN ? AND ?")->execute([$start_id, $end_id]);
        $pdo->prepare("DELETE FROM product_images WHERE product_id BETWEEN ? AND ?")->execute([$start_id, $end_id]);
        $pdo->prepare("DELETE FROM reviews WHERE product_id BETWEEN ? AND ?")->execute([$start_id, $end_id]);
        $pdo->prepare("DELETE FROM order_items WHERE product_id BETWEEN ? AND ?")->execute([$start_id, $end_id]);
    }
    else {
        echo "No products found in that range to delete.\n";
    }


}
catch (PDOException $e) {
    echo "Error deleting products: " . $e->getMessage();
}
?>

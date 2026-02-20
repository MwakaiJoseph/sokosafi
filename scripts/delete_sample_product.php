<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Delete product ID 1
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = 1");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Successfully deleted 'Sample Product' (ID 1).\n";
    }
    else {
        echo "Product ID 1 was not found or already deleted.\n";
    }

    // Clean up related tables if cascade delete isn't set up (just in case)
    $pdo->exec("DELETE FROM product_category WHERE product_id = 1");
    $pdo->exec("DELETE FROM product_images WHERE product_id = 1");
    $pdo->exec("DELETE FROM reviews WHERE product_id = 1");
    $pdo->exec("DELETE FROM order_items WHERE product_id = 1");


}
catch (PDOException $e) {
    echo "Error deleting product: " . $e->getMessage();
}
?>

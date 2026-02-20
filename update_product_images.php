<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/db_functions.php';

echo "<pre>";
echo "--- Bulk ID-Based Image URL Update ---\n";

// 1. Get all products
$stmt = $pdo->query("SELECT id, name FROM products ORDER BY id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($products) . " products.\n";

// 2. Prepare statements
$stmt_check = $pdo->prepare("SELECT id FROM product_images WHERE product_id = ?");
$stmt_update = $pdo->prepare("UPDATE product_images SET file_path = ? WHERE product_id = ?");
$stmt_insert = $pdo->prepare("INSERT INTO product_images (product_id, file_path, `order`) VALUES (?, ?, 1)");

$pdo->beginTransaction();

try {
    foreach ($products as $p) {
        $expected_path = "uploads/products/" . $p['id'] . ".jpg";

        // Check if image record exists
        $stmt_check->execute([$p['id']]);
        if ($stmt_check->fetch()) {
            // Update existing
            $stmt_update->execute([$expected_path, $p['id']]);
            echo "Updated Product {$p['id']} -> $expected_path\n";
        }
        else {
            // Insert new
            $stmt_insert->execute([$p['id'], $expected_path]);
            echo "Inserted Product {$p['id']} -> $expected_path\n";
        }
    }

    $pdo->commit();
    echo "--- Update Complete ---\n";
    echo "IMPORTANT: You must now manually ensure that image files exist at these paths!\n";
}
catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}

echo "</pre>";
?>

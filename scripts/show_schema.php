<?php
require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "--- Products Table Schema ---\n";
    foreach ($columns as $col) {
        echo "{$col['Field']} ({$col['Type']})\n";
    }

    echo "\n--- Product Images Table Schema ---\n";
    $stmt2 = $pdo->query("DESCRIBE product_images");
    $columns2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns2 as $col) {
        echo "{$col['Field']} ({$col['Type']})\n";
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<?php
require_once __DIR__ . '/../config/db.php';

try {
    $catId = 2; // Electronics

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, pi.file_path
        FROM products p 
        JOIN product_category pc ON p.id = pc.product_id
        JOIN product_images pi ON p.id = pi.product_id
        WHERE pc.category_id = ?
        ORDER BY p.id ASC
    ");
    $stmt->execute([$catId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "--- Checking File Existence for Electronics Images ---\n";
    echo "Web Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n"; // Might be empty in CLI
    $docRoot = __DIR__ . '/..'; // Assuming script is in /scripts

    foreach ($results as $row) {
        $dbPath = $row['file_path']; // e.g., 'uploads/products/some_image.jpg'

        // Handle absolute vs relative paths if needed
        $fullPath = $docRoot . '/' . $dbPath;

        if (file_exists($fullPath)) {
        // echo "[OK] ID {$row['id']} ({$row['name']}): $dbPath exists.\n";
        }
        else {
            echo "[MISSING] ID {$row['id']} ({$row['name']}): DB expects '$dbPath' but file not found at '$fullPath'\n";
        }
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

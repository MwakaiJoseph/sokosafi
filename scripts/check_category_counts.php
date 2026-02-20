<?php
require_once __DIR__ . '/../config/db.php';

try {
    $sql = "SELECT c.name, c.id, COUNT(pc.product_id) as count 
            FROM categories c 
            LEFT JOIN product_category pc ON c.id = pc.category_id 
            GROUP BY c.id 
            ORDER BY count DESC";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "--- Product Counts per Category ---\n";
    foreach ($results as $row) {
        echo str_pad($row['name'], 20) . " (ID: " . $row['id'] . "): " . $row['count'] . "\n";
    }


}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

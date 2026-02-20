<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

header('Content-Type: text/plain');

if (!db_has_connection()) {
    die("Database connection failed.\n");
}

echo "=== Product Categorization Diagnostic ===\n\n";

// 1. Total Active Products
$stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1");
$total_active = $stmt->fetchColumn();
echo "Total Active Products: $total_active\n";

// 2. Products with NO Category
$stmt = $pdo->query("
    SELECT COUNT(p.id) 
    FROM products p 
    LEFT JOIN product_category pc ON p.id = pc.product_id 
    WHERE p.is_active = 1 AND pc.category_id IS NULL
");
$uncategorized = $stmt->fetchColumn();
echo "Products with NO Category: $uncategorized\n";

// 3. Products per Category
echo "\nProducts per Category:\n";
$stmt = $pdo->query("
    SELECT c.name, COUNT(pc.product_id) as count
    FROM categories c
    LEFT JOIN product_category pc ON c.id = pc.category_id
    LEFT JOIN products p ON pc.product_id = p.id
    WHERE p.is_active = 1
    GROUP BY c.id
    ORDER BY count DESC
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($categories as $cat) {
    echo "- " . str_pad($cat['name'], 20) . ": " . $cat['count'] . "\n";
}

// 4. List a few uncategorized products (if any)
if ($uncategorized > 0) {
    echo "\nSample Uncategorized Products (First 10):\n";
    $stmt = $pdo->query("
        SELECT p.id, p.name 
        FROM products p 
        LEFT JOIN product_category pc ON p.id = pc.product_id 
        WHERE p.is_active = 1 AND pc.category_id IS NULL
        LIMIT 10
    ");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($samples as $p) {
        echo "- [ID: " . $p['id'] . "] " . $p['name'] . "\n";
    }
}
?>

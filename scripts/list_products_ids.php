<?php
require_once __DIR__ . '/../config/db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Product List for Image Renaming (Grouped by Category)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 20px auto; background-color: #f9f9f9; padding: 20px; }
        h1 { text-align: center; color: #333; }
        h2 { border-bottom: 2px solid #555; padding-bottom: 5px; margin-top: 30px; color: #444; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #eee; }
        tr:nth-child(even) { background-color: #f8f8f8; }
        .filename { color: #d63384; font-weight: bold; font-family: monospace; }
        .id-cell { font-family: monospace; font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <h1>Product List by Category</h1>
    <div style='text-align: center; margin-bottom: 20px;'>
        <a href='export_products.php' style='background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Download CSV</a>
        <button onclick='window.print()' style='background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Print / Save as PDF</button>
    </div>
    <p style='text-align: center;'>Rename your image files to match the <strong>Required Filename</strong>.</p>";

// Fetch products with their category names
$sql = "
    SELECT p.id, p.name, c.name as category_name
    FROM products p
    LEFT JOIN product_category pc ON p.id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.id
    ORDER BY c.name ASC, p.id DESC
";

$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by category
$grouped = [];
foreach ($products as $p) {
    $cat = $p['category_name'] ?: 'Uncategorized';
    $grouped[$cat][] = $p;
}

// Display
if (empty($grouped)) {
    echo "<p>No products found.</p>";
}
else {
    foreach ($grouped as $category => $items) {
        echo "<h2>$category</h2>";
        echo "<table>
                <thead>
                    <tr>
                        <th width='10%'>ID</th>
                        <th width='50%'>Product Name</th>
                        <th width='40%'>Required Filename</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($items as $item) {
            echo "<tr>
                    <td class='id-cell'>{$item['id']}</td>
                    <td>{$item['name']}</td>
                    <td class='filename'>{$item['id']}.jpg</td>
                  </tr>";
        }

        echo "</tbody></table>";
    }
}

echo "</body>
</html>";
?>

<?php
require_once __DIR__ . '/../config/db.php';

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=products_list.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('ID', 'Product Name', 'Category', 'Required Filename', 'Price', 'Sale Price'));

// Fetch products
$sql = "
    SELECT p.id, p.name, c.name as category_name, p.price, p.sale_price
    FROM products p
    LEFT JOIN product_category pc ON p.id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.id
    ORDER BY c.name ASC, p.id DESC
";

$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $category = $row['category_name'] ?: 'Uncategorized';
    $filename = $row['id'] . '.jpg';
    $price = $row['price'];
    $sale_price = $row['sale_price'] ?: '';

    fputcsv($output, array(
        $row['id'],
        $row['name'],
        $category,
        $filename,
        $price,
        $sale_price
    ));
}

fclose($output);
exit();
?>

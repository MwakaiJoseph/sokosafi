<?php
require_once __DIR__ . '/../config/db.php';

$message = '';

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($tmpName, "r")) !== FALSE) {
            $updated = 0;
            $row = 0;
            $headers = [];
            $header_indices = []; // Map standardized name => index

            // Prepare statement for updating prices
            $stmt = $pdo->prepare("UPDATE products SET price = :price, sale_price = :sale_price WHERE id = :id");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Get header indices
                if ($row === 0) {
                    // Remove BOM if present from the first element
                    if (isset($data[0])) {
                        $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
                    }

                    $headers = $data; // Store original headers for debug

                    // Normalize headers: trim whitespace and lowercase for matching
                    foreach ($data as $index => $colName) {
                        $cleanName = strtolower(trim($colName));
                        $header_indices[$cleanName] = $index;
                    }

                    // Basic validation: Check critical columns (allow 'id', 'ID', 'Id', etc.)
                    if (!isset($header_indices['id']) || !isset($header_indices['price'])) {
                        $found_headers = implode(", ", $headers);
                        $message = '<div class="alert alert-danger">Error: CSV must contain "ID" and "Price" columns.<br>Found headers: <strong>' . htmlspecialchars($found_headers) . '</strong></div>';
                        break;
                    }
                }
                else {
                    // Get values by column name using our normalized map
                    $id_idx = $header_indices['id'];
                    $price_idx = $header_indices['price'];
                    $sale_idx = isset($header_indices['sale price']) ? $header_indices['sale price'] : null;

                    $id = isset($data[$id_idx]) ? $data[$id_idx] : null;
                    $raw_price = isset($data[$price_idx]) ? $data[$price_idx] : null;
                    $raw_sale_price = ($sale_idx !== null && isset($data[$sale_idx])) ? $data[$sale_idx] : null;

                    // Clean values (remove currency symbols like KSh, commas, etc.)
                    $price = preg_replace('/[^0-9.]/', '', $raw_price);

                    $sale_price = null;
                    if ($raw_sale_price !== null && trim($raw_sale_price) !== '') {
                        $cleaned_sale = preg_replace('/[^0-9.]/', '', $raw_sale_price);
                        if (is_numeric($cleaned_sale) && $cleaned_sale > 0) {
                            $sale_price = $cleaned_sale;
                        }
                    }

                    // Check if ID is valid and price is numeric
                    if ($id && is_numeric($price)) {
                        $stmt->execute([
                            ':price' => $price,
                            ':sale_price' => $sale_price,
                            ':id' => $id
                        ]);
                        $updated++;
                    }
                }
                $row++;
            }
            fclose($handle);

            if (!$message) {
                $message = "<div class='alert alert-success'>Success! Updated prices for <strong>$updated</strong> products.</div>";
            }
        }
    }
    else {
        $message = '<div class="alert alert-danger">Error uploading file.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .upload-card { max-width: 600px; margin: 50px auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card upload-card p-4">
            <h1 class="h3 mb-4 text-center">Update Prices from CSV</h1>
            
            <?php echo $message; ?>
            
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="csvFile" class="form-label fw-bold">Select CSV File</label>
                    <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                    <div class="form-text">
                        File must be a CSV with "ID" and "Price" columns (matching the export format).
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update Prices</button>
                    <a href="list_products_ids.php" class="btn btn-outline-secondary">Cancel / Back to List</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/cloudinary.php';

use Cloudinary\Api\Upload\UploadApi;

echo "Starting image migration to Cloudinary...\n";

// Find products with local image paths
$stmt = $pdo->query("SELECT id, product_id, file_path FROM product_images WHERE file_path LIKE '%uploads/products/%' OR file_path LIKE '%assets/%'");
$images = $stmt->fetchAll();

echo "Found " . count($images) . " local images to migrate.\n";

$uploadApi = new UploadApi();
$successCount = 0;

foreach ($images as $img) {
    echo "Uploading Image ID: {$img['id']} for Product {$img['product_id']}... ";
    $localPath = __DIR__ . '/../' . ltrim(str_replace('./', '', $img['file_path']), '/\\');

    if (!file_exists($localPath)) {
        echo "SKIPPED (File not found locally: $localPath)\n";
        continue;
    }

    try {
        // Upload and compress!
        $response = $uploadApi->upload($localPath, [
            'folder' => 'sokosafi/products',
            'use_filename' => true,
            'unique_filename' => false,
            'overwrite' => true,
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto', // Auto-converts to lightweight WebP
                'width' => 800,
                'crop' => 'limit'
            ]
        ]);

        $newUrl = $response['secure_url'];

        // Update database with new cloud link
        $update = $pdo->prepare("UPDATE product_images SET file_path = ? WHERE id = ?");
        $update->execute([$newUrl, $img['id']]);

        echo "SUCCESS! " . round(filesize($localPath) / 1024) . "KB -> Compressed on Cloudinary.\n";
        $successCount++;
    }
    catch (\Exception $e) {
        echo "FAILED. " . $e->getMessage() . "\n";
    }
}

echo "\nMigration complete! $successCount images uploaded and database updated.\n";

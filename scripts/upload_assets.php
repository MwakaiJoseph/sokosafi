<?php
require_once __DIR__ . '/../config/cloudinary.php';

use Cloudinary\Api\Upload\UploadApi;

echo "Uploading static assets...\n";

$uploadApi = new UploadApi();
$assets = [
    __DIR__ . '/../assets/images/logo.png' => 'sokosafi/logo',
    __DIR__ . '/../assets/images/favicon.png' => 'sokosafi/favicon'
];

foreach ($assets as $localPath => $publicId) {
    if (file_exists($localPath)) {
        try {
            $response = $uploadApi->upload($localPath, [
                'public_id' => $publicId,
                'overwrite' => true,
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'width' => 800,
                    'crop' => 'limit'
                ]
            ]);
            echo "Uploaded " . basename($localPath) . ": " . $response['secure_url'] . "\n";
        }
        catch (\Exception $e) {
            echo "Failed to upload " . basename($localPath) . ": " . $e->getMessage() . "\n";
        }
    }
    else {
        echo "File not found: $localPath\n";
    }
}

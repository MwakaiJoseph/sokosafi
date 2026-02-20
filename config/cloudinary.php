<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME') ?: 'dmnbjskbz',
        'api_key' => getenv('CLOUDINARY_API_KEY') ?: '829766879496718',
        'api_secret' => getenv('CLOUDINARY_API_SECRET') ?: '9TEU8H6FCsPdiVXwqHlMhmdrty4',
    ],
    'url' => [
        'secure' => true
    ]
]);

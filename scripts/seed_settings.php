<?php
require_once __DIR__ . '/../config/db.php';

$settings = [
    'site_name' => 'E-Commerce Store',
    'site_description' => 'Your one-stop shop for electronics and gaming gear',
    'currency' => 'KSh',
    'tax_rate' => '0.08',
    'delivery_base_fee' => '150',
    'delivery_per_km' => '50',
    'free_delivery_threshold' => '0',
    'shipping_rate' => '0',
    'free_shipping_threshold' => '0.00'
];

echo "--- Seeding Settings ---\n";

foreach ($settings as $key => $val) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        $stmt->execute([$key, $val]);
        echo "Set '$key' = '$val'\n";
    }
    catch (Exception $e) {
        echo "Error setting '$key': " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
?>

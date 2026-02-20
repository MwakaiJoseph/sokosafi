<?php
require_once __DIR__ . '/../config/db.php';
if (php_sapi_name() !== 'cli') {
    die('CLI only');
}
$stmt = $pdo->query("SELECT id, name, slug FROM categories");
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cats as $c) {
    echo "ID: {$c['id']} | Slug: {$c['slug']} | Name: {$c['name']}\n";
}
?>

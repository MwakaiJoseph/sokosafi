<?php
require_once __DIR__ . '/config/db.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `deleted_accounts` (
      `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      `email` varchar(191) NOT NULL,
      `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    $pdo->exec($sql);
    echo "Successfully created deleted_accounts table!";
}
catch (PDOException $e) {
    echo "Failed to create table: " . $e->getMessage();
}
?>

<?php
require_once __DIR__ . '/config/db.php';
try {
    // Add reset_token
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL");
        echo "Added reset_token column.<br>";
    }

    // Add reset_expires
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'reset_expires'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN reset_expires DATETIME DEFAULT NULL");
        echo "Added reset_expires column.<br>";
    }

    // Add remember_token just in case it's needed later
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'remember_token'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN remember_token VARCHAR(255) DEFAULT NULL");
        echo "Added remember_token column.<br>";
    }

    echo "<b>Migration Complete! You can now use the Forgot Password feature!</b>";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

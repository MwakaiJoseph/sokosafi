<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/db_functions.php';

echo "Testing create_google_user...<br>";

// We need to bypass log_pdo_exception to see the exact error if it fails
try {
    $email = "test_google_db_" . time() . "@example.com";
    $first_name = "Test";
    $last_name = "User";
    $google_id = "test_google_id_" . time();

    $pdo->beginTransaction();

    $random_password = bin2hex(random_bytes(16));
    $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (email, password, first_name, last_name, google_id) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$email, $hashed_password, $first_name, $last_name, $google_id]);
    $user_id = $pdo->lastInsertId();

    // Assign default customer role
    $role_stmt = $pdo->prepare('SELECT id FROM roles WHERE name = "customer" LIMIT 1');
    $role_stmt->execute();
    $role_id = $role_stmt->fetchColumn();

    if ($role_id) {
        $ur_stmt = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)');
        $ur_stmt->execute([$user_id, $role_id]);
    }

    $pdo->commit();
    echo "SUCCESS! Created user ID: " . $user_id . "<br>";
}
catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "PDO EXCEPTION: " . $e->getMessage() . "<br>";
}
catch (Exception $e) {
    echo "GENERAL EXCEPTION: " . $e->getMessage() . "<br>";
}
?>

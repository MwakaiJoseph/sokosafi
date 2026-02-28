<?php
require_once __DIR__ . '/config/db.php';
$email = 'customer@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
if ($stmt->execute([$password, $email])) {
    echo "Password updated successfully.\n";
}
else {
    echo "Failed to update password.\n";
}
?>

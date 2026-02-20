<?php
// Mock session
// Use a simple ID to avoid length/char issues in CLI
session_id('test12345');

@session_start();
if (!isset($_SESSION))
    $_SESSION = [];

$_SESSION['user'] = ['roles' => ['admin']]; // admin_guard checks in_array('admin', $roles)
$_SESSION['csrf_token'] = 'valid_token_123'; // Logic checks against this

// Simulate POST request with INVALID token
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['csrf_token'] = 'invalid_token_999';
$_POST['sku'] = 'TEST-SKU';
$_POST['name'] = 'Test Product';
$_POST['price'] = 100;

// Set up error handler to catch "die" if possible, or just capture output
ob_start();

// Include the target file
// Note: We need to be careful about relative paths.
// admin_guard.php uses session_start(), which might complain if already started, but @session_start usually suppresses
// db.php creates connection. That's fine.
// We expect execution to STOP at verify_csrf_token and print "Security check failed..."

try {
    include __DIR__ . '/../admin/add_product.php';
}
catch (Throwable $e) {
    echo "Exception: " . $e->getMessage();
}

$output = ob_get_clean();

if (strpos($output, 'Security check failed') !== false) {
    echo "SUCCESS: CSRF Check Blocked Invalid Token.\n";
    echo "Output: " . substr($output, 0, 100) . "...\n";
}
else {
    echo "FAILURE: CSRF Check Did Not Block Invalid Token.\n";
    echo "Output Preview: " . substr($output, 0, 200) . "...\n";
}
?>

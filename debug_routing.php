<?php
// Test script to verify routing logic
$allowed = ['home', 'product', 'products', 'cart', 'checkout', 'login', 'register', 'logout', 'mpesa_pay', 'mpesa_callback', 'cart_add', 'contact', 'faq', 'shipping', 'returns', 'featured', 'new_arrivals'];

$tests = ['contact', 'faq', 'shipping', 'returns', 'Contact', 'FAQ'];

echo "Testing Routing Logic:\n";
foreach ($tests as $t) {
    $result = in_array($t, $allowed, true) ? "Allowed" : "Blocked (Defaults to Home)";
    echo "Page '$t': $result\n";
}

// Check file existence
echo "\nChecking Files:\n";
foreach (['contact', 'faq', 'shipping', 'returns'] as $p) {
    $file = __DIR__ . "/pages/$p.php";
    echo "$p.php exists? " . (file_exists($file) ? "YES" : "NO") . "\n";
}

<?php
$code = '
// Unified add to cart (handles auth check)
function add_item_to_cart_unified($product_id, $quantity = 1) {
    if (session_status() === PHP_SESSION_NONE) {
        // Session should be started by index.php
    }
    
    if (isset($_SESSION[\'user\']) && isset($_SESSION[\'user\'][\'id\'])) {
        return add_to_cart($_SESSION[\'user\'][\'id\'], $product_id, $quantity);
    } else {
        return add_to_cart_guest($product_id, $quantity);
    }
}
';

$file = __DIR__ . '/includes/db_functions.php';
$content = file_get_contents($file);

if (strpos($content, 'function add_item_to_cart_unified') === false) {
    // Remove closing tag if present (regex simpler)
    $content = preg_replace('/\?>\s*$/', '', $content);
    $content .= "\n" . $code . "\n?>";
    if (file_put_contents($file, $content)) {
        echo "Appended.\n";
    }
    else {
        echo "Write failed.\n";
    }
}
else {
    echo "Already exists.\n";
}
?>

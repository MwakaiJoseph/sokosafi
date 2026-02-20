<?php
$file_path = __DIR__ . '/includes/db_functions.php';
$content = file_get_contents($file_path);

if (strpos($content, 'function add_item_to_cart_unified') !== false) {
    echo "Function already exists.\n";
} else {
    // Coding the string explicitly to avoid heredoc issues
    $new_code = "\n" .
"// Unified add to cart (handles auth check)\n" .
"function add_item_to_cart_unified(\$product_id, \$quantity = 1) {\n" .
"    if (session_status() === PHP_SESSION_NONE) {\n" .
"        // Session should be started by index.php\n" .
"    }\n" .
"    \n" .
"    if (isset(\$_SESSION['user']) && isset(\$_SESSION['user']['id'])) {\n" .
"        return add_to_cart(\$_SESSION['user']['id'], \$product_id, \$quantity);\n" .
"    } else {\n" .
"        return add_to_cart_guest(\$product_id, \$quantity);\n" .
"    }\n" .
"}\n" .
"?>";
    
    // Remove the last ?> if it exists
    // We scan from the end
    $trimmed = rtrim($content);
    if (substr($trimmed, -2) === '?>') {
        $content = substr($trimmed, 0, -2);
    }
    
    $content .= $new_code;
    
    if (file_put_contents($file_path, $content)) {
        echo "Function appended successfully.\n";
    } else {
        echo "Error writing to file.\n";
    }
}
?>

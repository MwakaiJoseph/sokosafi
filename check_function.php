<?php
require_once __DIR__ . '/includes/db_functions.php';

if (function_exists('add_item_to_cart_unified')) {
    echo "SUCCESS: Function add_item_to_cart_unified exists.\n";
}
else {
    echo "FAILURE: Function add_item_to_cart_unified does NOT exist.\n";
}
?>

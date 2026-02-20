<?php
// Cart helper functions

// Get total count of items in the cart
function get_cart_count()
{
    global $pdo;

    // Ensure db_functions.php is loaded/available for get_user_cart/get_session_cart
    if (!function_exists('get_user_cart')) {
        return 0;
    }

    // Check if user is logged in
    // Start session if not started (though index.php handles this)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

    if ($user_id) {
        $cart = get_user_cart($user_id);
    }
    else {
        $cart = get_session_cart();
    }

    if (!$cart || empty($cart['items'])) {
        return 0;
    }

    $count = 0;
    foreach ($cart['items'] as $item) {
        $count += (int)$item['quantity'];
    }

    return $count;
}

// Wrapper to add item to either user cart or session cart
function add_item_to_cart_unified($product_id, $quantity)
{
    // Ensure session is started to check for user
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

    // Delegate to appropriate db_function
    if ($user_id) {
        return add_to_cart($user_id, $product_id, $quantity);
    }
    else {
        return add_to_cart_guest($product_id, $quantity);
    }
}
?>

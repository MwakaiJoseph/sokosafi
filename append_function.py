import os

file_path = r'c:/xampp/htdocs/E-Commerce2/includes/db_functions.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

if 'function add_item_to_cart_unified' in content:
    print("Function already exists.")
else:
    # Find the last closing tag
    if '?>' in content:
        # Replace the last occurrence of ?> with our code + ?>
        new_code = """
// Unified add to cart (handles auth check)
function add_item_to_cart_unified($product_id, $quantity = 1) {
    if (session_status() === PHP_SESSION_NONE) {
        // Session should be started by index.php
    }
    
    if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
        return add_to_cart($_SESSION['user']['id'], $product_id, $quantity);
    } else {
        return add_to_cart_guest($product_id, $quantity);
    }
}
?>"""
        # Replace the very last ?>. We can just rstrip and append if it ends with ?>
        content = content.rstrip()
        if content.endswith('?>'):
            content = content[:-2] + new_code
        else:
             content += new_code
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print("Function appended successfully.")
    else:
        print("Error: Closing PHP tag not found.")

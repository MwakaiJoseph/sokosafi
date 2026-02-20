<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/db_functions.php';

if (php_sapi_name() !== 'cli') {
    die('CLI only');
}

// Helper to generate random price ending in 00 or 50
function get_random_price($min, $max)
{
    $price = rand($min, $max);
    return round($price / 50) * 50;
}

$products = get_products(1000);

$stmt = $pdo->prepare("UPDATE products SET price = :price, sale_price = :sale_price WHERE id = :id");

foreach ($products as $p) {
    $name = strtolower($p['name']);
    $price = 0;
    $sale_price = null;

    // Logic to determine price based on keywords
    if (strpos($name, 'laptop') !== false || strpos($name, 'book air') !== false) {
        $price = get_random_price(65000, 180000);
    }
    elseif (strpos($name, 'phone') !== false || strpos($name, 'galaxy') !== false) {
        $price = get_random_price(35000, 140000);
    }
    elseif (strpos($name, 'tv') !== false) {
        $price = get_random_price(45000, 120000);
    }
    elseif (strpos($name, 'camera') !== false) {
        $price = get_random_price(50000, 150000);
    }
    elseif (strpos($name, 'headphone') !== false || strpos($name, 'speaker') !== false || strpos($name, 'buds') !== false) {
        $price = get_random_price(4000, 25000);
    }
    elseif (strpos($name, 'watch') !== false) {
        $price = get_random_price(8000, 60000);
    }
    elseif (strpos($name, 'sofa') !== false || strpos($name, 'couch') !== false) {
        $price = get_random_price(45000, 120000);
    }
    elseif (strpos($name, 'table') !== false || strpos($name, 'desk') !== false || strpos($name, 'chair') !== false) {
        $price = get_random_price(15000, 45000);
    }
    elseif (strpos($name, 'lamp') !== false || strpos($name, 'light') !== false) {
        $price = get_random_price(3500, 12000);
    }
    elseif (strpos($name, 'shoe') !== false || strpos($name, 'sneaker') !== false || strpos($name, 'boot') !== false || strpos($name, 'loafer') !== false || strpos($name, 'oxford') !== false) {
        $price = get_random_price(4500, 18000);
    }
    elseif (strpos($name, 'sandal') !== false || strpos($name, 'flip flop') !== false || strpos($name, 'slipper') !== false) {
        $price = get_random_price(1500, 6000);
    }
    elseif (strpos($name, 'jacket') !== false || strpos($name, 'coat') !== false || strpos($name, 'hoodie') !== false) {
        $price = get_random_price(3500, 12000);
    }
    elseif (strpos($name, 'dress') !== false || strpos($name, 'shirt') !== false || strpos($name, 'pants') !== false || strpos($name, 'jeans') !== false) {
        $price = get_random_price(2500, 8000);
    }
    elseif (strpos($name, 'bag') !== false || strpos($name, 'backpack') !== false || strpos($name, 'tote') !== false) {
        $price = get_random_price(3500, 15000);
    }
    elseif (strpos($name, 'wallet') !== false || strpos($name, 'belt') !== false || strpos($name, 'hat') !== false || strpos($name, 'scarf') !== false || strpos($name, 'pouch') !== false) {
        $price = get_random_price(1500, 5000);
    }
    elseif (strpos($name, 'ring') !== false || strpos($name, 'necklace') !== false || strpos($name, 'earring') !== false || strpos($name, 'bracelet') !== false) {
        $price = get_random_price(2000, 15000); // Costume/Semi-fine jewelry
    }
    elseif (strpos($name, 'serum') !== false || strpos($name, 'cream') !== false || strpos($name, 'lotion') !== false || strpos($name, 'perfume') !== false) {
        $price = get_random_price(2500, 9000);
    }
    elseif (strpos($name, 'lipstick') !== false || strpos($name, 'mascara') !== false || strpos($name, 'palette') !== false || strpos($name, 'cleanser') !== false || strpos($name, 'scrub') !== false) {
        $price = get_random_price(1200, 4500);
    }
    elseif (strpos($name, 'towel') !== false || strpos($name, 'sheet') !== false || strpos($name, 'pillow') !== false) {
        $price = get_random_price(2000, 8000);
    }
    elseif (strpos($name, 'candle') !== false || strpos($name, 'vase') !== false || strpos($name, 'art') !== false || strpos($name, 'plant') !== false) {
        $price = get_random_price(1500, 6000);
    }
    elseif (strpos($name, 'sunglasses') !== false) {
        $price = get_random_price(2500, 12000);
    }
    else {
        // Default fallback if no keyword matches, try to keep existing magnitude if high, else bump up
        if ($p['price'] > 1000) {
            $price = $p['price']; // Keep existing if it looks like KES
        }
        else {
            $price = get_random_price(2000, 10000); // Assume it was USD, convert to approx KES
        }
    }

    // Apply random sale (30% chance)
    if (rand(1, 10) <= 3) {
        $discount = rand(10, 30); // 10% to 30% off
        $sale_price = $price * (1 - ($discount / 100));
        $sale_price = round($sale_price / 50) * 50; // Round
    }

    $stmt->execute([
        ':price' => $price,
        ':sale_price' => $sale_price,
        ':id' => $p['id']
    ]);

    echo "Updated {$p['name']}: $price" . ($sale_price ? " (Sale: $sale_price)" : "") . "\n";
}
echo "Prices updated successfully.\n";

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

header('Content-Type: text/plain');

if (!db_has_connection()) {
    die("Database connection failed.\n");
}

echo "=== Auto-Assigning Product Categories ===\n\n";

// 1. Get All Categories
$categories = get_categories();
$cat_map = []; // name -> id
foreach ($categories as $c) {
    $cat_map[strtolower($c['name'])] = $c['id'];
    $cat_map[strtolower($c['slug'])] = $c['id'];
}

// 2. Define Keyword Mapping (Keyword -> Category Slug/Name)
$keyword_rules = [
    // Electronics
    'phone' => 'electronics',
    'galaxy' => 'electronics',
    'iphone' => 'electronics',
    'android' => 'electronics',
    'laptop' => 'electronics',
    'macbook' => 'electronics',
    'dell' => 'electronics',
    'hp ' => 'electronics',
    'earbud' => 'electronics',
    'headphone' => 'electronics',
    'camera' => 'electronics',
    'watch' => 'electronics',
    'tablet' => 'electronics',
    'ipad' => 'electronics',
    'tv' => 'electronics',
    'audio' => 'electronics',
    'speaker' => 'electronics',
    'mouse' => 'electronics',
    'keyboard' => 'electronics',
    'monitor' => 'electronics',
    'tracker' => 'electronics',
    'hub' => 'electronics',

    // Fashion
    'shirt' => 'fashion',
    'pant' => 'fashion',
    'dress' => 'fashion',
    'jacket' => 'fashion',
    'hoodie' => 'fashion',
    'jeans' => 'fashion',
    'coat' => 'fashion',
    'sweater' => 'fashion',
    'clothing' => 'fashion',
    'suit' => 'fashion',
    'blazer' => 'fashion',
    'skirt' => 'fashion',
    'chinos' => 'fashion',

    // Beauty
    'cream' => 'beauty',
    'lotion' => 'beauty',
    'lipstick' => 'beauty',
    'makeup' => 'beauty',
    'perfume' => 'beauty',
    'cologne' => 'beauty',
    'fragrance' => 'beauty',
    'oil' => 'beauty',
    'cleanser' => 'beauty',
    'serum' => 'beauty',
    'mask' => 'beauty',
    'shampoo' => 'beauty',
    'conditioner' => 'beauty',
    'soap' => 'beauty',
    'scrub' => 'beauty',
    'sunscreen' => 'beauty',
    'mascara' => 'beauty',
    'eyeshadow' => 'beauty',

    // Home & Living
    'chair' => 'home-living',
    'table' => 'home-living',
    'sofa' => 'home-living',
    'bed' => 'home-living',
    'lamp' => 'home-living',
    'desk' => 'home-living',
    'pillow' => 'home-living',
    'blanket' => 'home-living',
    'decor' => 'home-living',
    'plant' => 'home-living',
    'shelf' => 'home-living',
    'rug' => 'home-living',
    'furniture' => 'home-living',
    'kitchen' => 'home-living',
    'cookware' => 'home-living',
    'bottle' => 'home-living',
    'mug' => 'home-living',
    'drill' => 'home-living',
    'mat' => 'home-living',
    'novel' => 'home-living',
    'vase' => 'home-living',
    'art' => 'home-living',
    'candle' => 'home-living',
    'coffee' => 'home-living',
    'towel' => 'home-living',

    // Accessories
    'bag' => 'accessories',
    'backpack' => 'accessories',
    'wallet' => 'accessories',
    'belt' => 'accessories',
    'hat' => 'accessories',
    'cap' => 'accessories',
    'scarf' => 'accessories',
    'glasses' => 'accessories',
    'sunglasses' => 'accessories',
    'jewelry' => 'accessories',
    'ring' => 'accessories',
    'necklace' => 'accessories',
    'earring' => 'accessories',
    'bracelet' => 'accessories',
    'pouch' => 'accessories',
    'organizer' => 'accessories',

    // Shoes
    'shoe' => 'shoes',
    'sneaker' => 'shoes',
    'boot' => 'shoes',
    'sandal' => 'shoes',
    'heel' => 'shoes',
    'slipper' => 'shoes',
    'trainer' => 'shoes',
    'loafer' => 'shoes',
    'slip-ons' => 'shoes',
    'oxfords' => 'shoes',
    'flip flops' => 'shoes',

    // Default fallback logic provided below
];

// 3. Fetch Uncategorized Products
$stmt = $pdo->query("
    SELECT p.id, p.name 
    FROM products p 
    LEFT JOIN product_category pc ON p.id = pc.product_id 
    WHERE p.is_active = 1 AND pc.category_id IS NULL
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($products) . " uncategorized products.\n";

$fixed_count = 0;

foreach ($products as $p) {
    $name_lower = strtolower($p['name']);
    $assigned_cat_id = null;
    $assigned_reason = '';

    // Check keywords
    foreach ($keyword_rules as $keyword => $cat_slug) {
        if (strpos($name_lower, $keyword) !== false) {
            // Find category ID
            // Try to find by slug first
            if (isset($cat_map[$cat_slug])) {
                $assigned_cat_id = $cat_map[$cat_slug];
                $assigned_reason = "Matched keyword '$keyword'";
                break;
            }
        }
    }

    // Fallback: if "Sample Product" assign to Home & Living or Accessories based on randomness/even distribution if needed,
    // but for now let's just use "Accessories" for "Sample Product" if not matched, or maybe "Home & Living"
    if (!$assigned_cat_id && strpos($name_lower, 'sample product') !== false) {
        // Distribute sample products to random categories to populate them
        $sample_cats = ['electronics', 'fashion', 'home-living', 'beauty', 'accessories', 'shoes'];
        $random_slug = $sample_cats[array_rand($sample_cats)];
        if (isset($cat_map[$random_slug])) {
            $assigned_cat_id = $cat_map[$random_slug];
            $assigned_reason = "Sample Product -> Random Assigment";
        }
    }

    if ($assigned_cat_id) {
        // Insert into product_category
        try {
            $stmt = $pdo->prepare("INSERT INTO product_category (product_id, category_id) VALUES (:pid, :cid)");
            $stmt->execute([':pid' => $p['id'], ':cid' => $assigned_cat_id]);
            echo "[FIXED] Product {$p['id']} ('{$p['name']}') assigned to Category ID $assigned_cat_id ($assigned_reason)\n";
            $fixed_count++;
        }
        catch (Exception $e) {
            echo "[ERROR] Failed to assign Product {$p['id']}: " . $e->getMessage() . "\n";
        }
    }
    else {
        echo "[SKIP] Could not categorize Product {$p['id']} ('{$p['name']}')\n";
    }
}

echo "\nSummary: Assigned categories to $fixed_count products.\n";
?>

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

// Allow running from browser (removed CLI check)
echo "<pre>";
echo "--- Seeding More Products ---\n";

// Categories to seed (ID => Name/Keyword for Unsplash)
$categories = [
    2 => ['name' => 'Electronics', 'term' => 'electronics', 'count' => 30],
    7 => ['name' => 'Fashion', 'term' => 'fashion', 'count' => 30],
    8 => ['name' => 'Home & Living', 'term' => 'furniture', 'count' => 30],
    9 => ['name' => 'Beauty', 'term' => 'cosmetics', 'count' => 30],
    10 => ['name' => 'Accessories', 'term' => 'accessories', 'count' => 30],
    11 => ['name' => 'Shoes', 'term' => 'shoes', 'count' => 30]

];

// Base product templates - 30 UNIQUE items per category
$templates = [
    'Electronics' => [
        ['name' => 'iPhone 15 Pro Max', 'price' => 1200, 'image_term' => 'iphone'],
        ['name' => 'MacBook Air M3', 'price' => 1100, 'image_term' => 'macbook'],
        ['name' => 'Sony PlayStation 5', 'price' => 500, 'image_term' => 'playstation'],
        ['name' => 'Nintendo Switch OLED', 'price' => 350, 'image_term' => 'nintendo switch'],
        ['name' => 'Dell XPS 15 Laptop', 'price' => 1500, 'image_term' => 'laptop'],
        ['name' => 'iPad Pro 12.9"', 'price' => 1099, 'image_term' => 'ipad'],
        ['name' => 'Samsung Galaxy S24 Ultra', 'price' => 1199, 'image_term' => 'samsung galaxy'],
        ['name' => 'Bose QuietComfort Ultra', 'price' => 429, 'image_term' => 'headphones'],
        ['name' => 'GoPro Hero 12 Black', 'price' => 399, 'image_term' => 'action camera'],
        ['name' => 'Kindle Paperwhite Sig', 'price' => 189, 'image_term' => 'kindle'],
        ['name' => 'Apple Watch Series 9', 'price' => 399, 'image_term' => 'smartwatch'],
        ['name' => 'Logitech MX Master 3S', 'price' => 99, 'image_term' => 'mouse'],
        ['name' => 'Canon EOS R6 Mark II', 'price' => 2499, 'image_term' => 'camera'],
        ['name' => 'DJI Mini 4 Pro Drone', 'price' => 759, 'image_term' => 'drone'],
        ['name' => 'Samsung Odyssey Monitor', 'price' => 1299, 'image_term' => 'monitor'],
        ['name' => 'Dyson Airwrap Styler', 'price' => 599, 'image_term' => 'hair styler'],
        ['name' => 'Sonos Arc Soundbar', 'price' => 899, 'image_term' => 'soundbar'],
        ['name' => 'Apple AirPods Pro 2', 'price' => 249, 'image_term' => 'airpods'],
        ['name' => 'Google Pixel 8 Pro', 'price' => 999, 'image_term' => 'pixel phone'],
        ['name' => 'Microsoft Surface Pro 9', 'price' => 999, 'image_term' => 'surface pro'],
        ['name' => 'NVIDIA RTX 4090 GPU', 'price' => 1599, 'image_term' => 'graphics card'],
        ['name' => 'Anker Solix Power Stn', 'price' => 999, 'image_term' => 'power station'],
        ['name' => 'Fitbit Charge 6', 'price' => 159, 'image_term' => 'fitness tracker'],
        ['name' => 'Razer BlackWidow V4', 'price' => 169, 'image_term' => 'gaming keyboard'],
        ['name' => 'Elgato Stream Deck +', 'price' => 199, 'image_term' => 'stream deck'],
        ['name' => 'Philips Hue Starter Kit', 'price' => 199, 'image_term' => 'smart bulb'],
        ['name' => 'Amazon Echo Dot 5', 'price' => 49, 'image_term' => 'smart speaker'],
        ['name' => 'Samsung T7 Shield SSD', 'price' => 119, 'image_term' => 'external ssd'],
        ['name' => 'Wacom Cintiq 16', 'price' => 649, 'image_term' => 'drawing tablet'],
        ['name' => 'Starlink Standard Kit', 'price' => 599, 'image_term' => 'satellite dish']
    ],
    'Fashion' => [
        ['name' => 'Classic Trench Coat', 'price' => 120, 'image_term' => 'trench coat'],
        ['name' => 'Levis 501 Original Jeans', 'price' => 98, 'image_term' => 'jeans'],
        ['name' => 'Cashmere Wool Sweater', 'price' => 150, 'image_term' => 'sweater'],
        ['name' => 'Floral Summer Sundress', 'price' => 45, 'image_term' => 'sundress'],
        ['name' => 'Slim Fit Tuxedo Suit', 'price' => 250, 'image_term' => 'tuxedo'],
        ['name' => 'Leather Biker Jacket', 'price' => 180, 'image_term' => 'leather jacket'],
        ['name' => 'Oversized Hoodie', 'price' => 60, 'image_term' => 'hoodie'],
        ['name' => 'Silk Satin Blouse', 'price' => 85, 'image_term' => 'blouse'],
        ['name' => 'High-Waist Yoga Leggings', 'price' => 55, 'image_term' => 'leggings'],
        ['name' => 'Vintage Graphic T-Shirt', 'price' => 35, 'image_term' => 't-shirt'],
        ['name' => 'Puffer Down Jacket', 'price' => 140, 'image_term' => 'puffer jacket'],
        ['name' => 'Checkered Flannel Shirt', 'price' => 40, 'image_term' => 'flannel shirt'],
        ['name' => 'Midi Pleated Skirt', 'price' => 50, 'image_term' => 'pleated skirt'],
        ['name' => 'Cargo Utility Pants', 'price' => 65, 'image_term' => 'cargo pants'],
        ['name' => 'Linen Button-Down Shirt', 'price' => 55, 'image_term' => 'linen shirt'],
        ['name' => 'Luxury Agbada 3-Piece Set', 'price' => 350, 'image_term' => 'agbada robe'],
        ['name' => 'Kente Off-Shoulder Gown', 'price' => 180, 'image_term' => 'kente dress'],
        ['name' => 'Ankara Print Blazer', 'price' => 120, 'image_term' => 'ankara blazer'],
        ['name' => 'Silk Adire Kaftan', 'price' => 150, 'image_term' => 'adire kaftan'],
        ['name' => 'Hand-Woven Smock Fugu', 'price' => 140, 'image_term' => 'fugu smock'],
        ['name' => 'Modern Dashiki Shirt', 'price' => 60, 'image_term' => 'dashiki shirt'],
        ['name' => 'Bogolan Mud Cloth Kimono', 'price' => 130, 'image_term' => 'mud cloth kimono'],
        ['name' => 'Embellished Boubou Robe', 'price' => 200, 'image_term' => 'boubou gown'],
        ['name' => 'Kitenge Mermaid Skirt', 'price' => 85, 'image_term' => 'kitenge skirt'],
        ['name' => 'Senator Suit Navy Blue', 'price' => 220, 'image_term' => 'senator suit'],
        ['name' => 'Maasai Shuka Poncho', 'price' => 95, 'image_term' => 'maasai shuka'],
        ['name' => 'Ghanian Batakari Tunic', 'price' => 110, 'image_term' => 'batakari'],
        ['name' => 'Lace Voile Wrapper Set', 'price' => 160, 'image_term' => 'wrapper set lace'],
        ['name' => 'Afro-Fusion Denim Jacket', 'price' => 140, 'image_term' => 'african print jacket'],
        ['name' => 'Swahili Kanzu Tunic', 'price' => 70, 'image_term' => 'kanzu tunic']
    ],
    'Home & Living' => [
        ['name' => 'Ninja Air Fryer Max', 'price' => 129, 'image_term' => 'air fryer'],
        ['name' => 'Dyson V15 Detect Vacuum', 'price' => 749, 'image_term' => 'vacuum cleaner'],
        ['name' => 'KitchenAid Stand Mixer', 'price' => 449, 'image_term' => 'stand mixer'],
        ['name' => 'Nespresso Vertuo Machine', 'price' => 199, 'image_term' => 'coffee machine'],
        ['name' => 'Weighted Anxiety Blanket', 'price' => 89, 'image_term' => 'weighted blanket'],
        ['name' => 'Ergonomic Standing Desk', 'price' => 399, 'image_term' => 'standing desk'],
        ['name' => 'Herman Miller Aeron', 'price' => 1200, 'image_term' => 'office chair'],
        ['name' => 'Le Creuset Dutch Oven', 'price' => 420, 'image_term' => 'dutch oven'],
        ['name' => 'Philips Air Purifier', 'price' => 250, 'image_term' => 'air purifier'],
        ['name' => 'NutriBullet Pro Blender', 'price' => 99, 'image_term' => 'blender'],
        ['name' => 'Egyptian Cotton Sheets', 'price' => 150, 'image_term' => 'bed sheets'],
        ['name' => 'Memory Foam Mattress', 'price' => 899, 'image_term' => 'mattress'],
        ['name' => 'Smart LED Strip Lights', 'price' => 40, 'image_term' => 'led strip'],
        ['name' => 'Robotic Vacuum Cleaner', 'price' => 300, 'image_term' => 'robot vacuum'],
        ['name' => 'Cast Iron Skillet Set', 'price' => 70, 'image_term' => 'cast iron skillet'],
        ['name' => 'Modern Abstract Rug', 'price' => 120, 'image_term' => 'rug'],
        ['name' => 'Aromatherapy Diffuser', 'price' => 35, 'image_term' => 'diffuser'],
        ['name' => 'Instant Pot Duo Plus', 'price' => 129, 'image_term' => 'pressure cooker'],
        ['name' => 'SodaStream Water Maker', 'price' => 99, 'image_term' => 'sodastream'],
        ['name' => 'Ring Video Doorbell', 'price' => 99, 'image_term' => 'video doorbell'],
        ['name' => 'Minimalist Floor Lamp', 'price' => 85, 'image_term' => 'floor lamp'],
        ['name' => 'Bamboo Bathtub Caddy', 'price' => 45, 'image_term' => 'bathtub tray'],
        ['name' => 'Electric Kettle Control', 'price' => 60, 'image_term' => 'kettle'],
        ['name' => 'Velvet Throw Pillows', 'price' => 30, 'image_term' => 'cushions'],
        ['name' => 'Steak Knife Set', 'price' => 80, 'image_term' => 'knife set'],
        ['name' => 'Tupperware Storage Set', 'price' => 50, 'image_term' => 'food storage'],
        ['name' => 'Handheld Steamer', 'price' => 40, 'image_term' => 'steamer'],
        ['name' => 'Zero Gravity Patio Chair', 'price' => 70, 'image_term' => 'patio chair'],
        ['name' => 'Bookshelf Industrial', 'price' => 150, 'image_term' => 'bookshelf'],
        ['name' => 'Orthopedic Seat Cushion', 'price' => 40, 'image_term' => 'seat cushion']
    ],
    'Beauty' => [
        ['name' => 'La Mer Moisturizing Cream', 'price' => 380, 'image_term' => 'face cream'],
        ['name' => 'Dyson Supersonic Dryer', 'price' => 429, 'image_term' => 'hair dryer'],
        ['name' => 'Olaplex No. 3 Perfector', 'price' => 30, 'image_term' => 'hair mask'],
        ['name' => 'Estee Lauder Night Repair', 'price' => 110, 'image_term' => 'serum'],
        ['name' => 'Fenty Beauty Foundation', 'price' => 40, 'image_term' => 'foundation makeup'],
        ['name' => 'CeraVe Hydrating Cleanser', 'price' => 18, 'image_term' => 'face wash'],
        ['name' => 'The Ordinary Niacinamide', 'price' => 10, 'image_term' => 'face serum'],
        ['name' => 'Laneige Lip Sleep Mask', 'price' => 24, 'image_term' => 'lip mask'],
        ['name' => 'Charlotte Tilbury Cream', 'price' => 100, 'image_term' => 'face moisturizer'],
        ['name' => 'Dior Sauvage Elixir', 'price' => 150, 'image_term' => 'perfume bottle'],
        ['name' => 'Chanel No. 5 Perfume', 'price' => 160, 'image_term' => 'perfume'],
        ['name' => 'Anastasia Brow Wiz', 'price' => 25, 'image_term' => 'eyebrow pencil'],
        ['name' => 'Urban Decay Setting Spray', 'price' => 33, 'image_term' => 'setting spray'],
        ['name' => 'Paulas Choice Exfoliant', 'price' => 35, 'image_term' => 'toner'],
        ['name' => 'Rare Beauty Liquid Blush', 'price' => 23, 'image_term' => 'blush'],
        ['name' => 'Glossier Boy Brow', 'price' => 17, 'image_term' => 'eyebrow gel'],
        ['name' => 'Drunk Elephant Cream', 'price' => 68, 'image_term' => 'face cream'],
        ['name' => 'Maybelline Sky Mascara', 'price' => 12, 'image_term' => 'mascara'],
        ['name' => 'Huda Beauty Palette', 'price' => 65, 'image_term' => 'eyeshadow'],
        ['name' => 'Sol de Janeiro Cream', 'price' => 48, 'image_term' => 'body cream'],
        ['name' => 'Moroccanoil Treatment', 'price' => 44, 'image_term' => 'hair oil'],
        ['name' => 'Supergoop Sunscreen', 'price' => 38, 'image_term' => 'sunscreen'],
        ['name' => 'Beautyblender Original', 'price' => 20, 'image_term' => 'makeup sponge'],
        ['name' => 'Mac Retro Matte Lipstick', 'price' => 22, 'image_term' => 'lipstick'],
        ['name' => 'Kiehls Ultra Facial Cream', 'price' => 38, 'image_term' => 'face cream'],
        ['name' => 'Foreo Luna Cleansing', 'price' => 139, 'image_term' => 'facial brush'],
        ['name' => 'Vitamin C Serum', 'price' => 85, 'image_term' => 'vitamin c serum'],
        ['name' => 'Hyaluronic Acid Serum', 'price' => 75, 'image_term' => 'face serum'],
        ['name' => 'Gel Nail Polish Kit', 'price' => 45, 'image_term' => 'nail polish'],
        ['name' => 'Beard Grooming Kit', 'price' => 40, 'image_term' => 'beard oil']
    ],
    'Accessories' => [
        ['name' => 'Smartphone Gimbal Stabilizer', 'price' => 89, 'image_term' => 'gimbal stabilizer'],
        ['name' => 'Universal Travel Adapter', 'price' => 35, 'image_term' => 'travel adapter'],
        ['name' => 'Tactical Pen Multi-Tool', 'price' => 25, 'image_term' => 'tactical pen'],
        ['name' => 'Portable Laptop Stand', 'price' => 45, 'image_term' => 'laptop stand'],
        ['name' => 'Resistance Bands Set', 'price' => 30, 'image_term' => 'resistance bands'],
        ['name' => 'Digital Luggage Scale', 'price' => 20, 'image_term' => 'luggage scale'],
        ['name' => 'LED Ring Light Tripod', 'price' => 55, 'image_term' => 'ring light'],
        ['name' => 'Car Phone Mount Magnetic', 'price' => 25, 'image_term' => 'car phone mount'],
        ['name' => 'Travel Jewelry Organizer', 'price' => 35, 'image_term' => 'jewelry organizer'],
        ['name' => 'Cable Organizer Case', 'price' => 20, 'image_term' => 'cable organizer bag'],
        ['name' => 'Blue Light Glasses', 'price' => 30, 'image_term' => 'blue light glasses'],
        ['name' => 'Hydration Running Belt', 'price' => 25, 'image_term' => 'running belt'],
        ['name' => 'Clip-on Phone Lens Kit', 'price' => 40, 'image_term' => 'phone lens kit'],
        ['name' => 'Waterproof Phone Pouch', 'price' => 15, 'image_term' => 'waterproof phone pouch'],
        ['name' => 'Shoe Cleaning Kit', 'price' => 35, 'image_term' => 'shoe cleaning kit'],
        ['name' => 'Insulated Lunch Bag', 'price' => 30, 'image_term' => 'lunch bag'],
        ['name' => 'Car Trunk Organizer', 'price' => 45, 'image_term' => 'car organizer'],
        ['name' => 'Portable Neck Fan', 'price' => 25, 'image_term' => 'neck fan'],
        ['name' => 'LED Book Reading Light', 'price' => 18, 'image_term' => 'book light'],
        ['name' => 'Silk Sleep Mask', 'price' => 20, 'image_term' => 'sleep mask'],
        ['name' => 'Manicure Grooming Set', 'price' => 25, 'image_term' => 'manicure set'],
        ['name' => 'Desk Pad Mouse Mat', 'price' => 30, 'image_term' => 'desk mat'],
        ['name' => 'Headphone Stand Holder', 'price' => 35, 'image_term' => 'headphone stand'],
        ['name' => 'Reusable Metal Straws', 'price' => 15, 'image_term' => 'metal straws'],
        ['name' => 'Webcam Privacy Cover', 'price' => 10, 'image_term' => 'privacy cover'],
        ['name' => 'Paracord Survival Bracelet', 'price' => 22, 'image_term' => 'paracord bracelet'],
        ['name' => 'Yoga Mat Carrying Strap', 'price' => 18, 'image_term' => 'yoga mat strap'],
        ['name' => 'RFID Passport Sleeve', 'price' => 15, 'image_term' => 'passport sleeve'],
        ['name' => 'Key Finder Tracker', 'price' => 29, 'image_term' => 'key finder'],
        ['name' => 'Microphone Boom Arm', 'price' => 45, 'image_term' => 'microphone arm']
    ],
    'Shoes' => [
        ['name' => 'Nike Air Jordan 1', 'price' => 180, 'image_term' => 'sneakers nike'],
        ['name' => 'Adidas Yeezy Boost 350', 'price' => 250, 'image_term' => 'yeezy'],
        ['name' => 'Converse Chuck Taylor', 'price' => 65, 'image_term' => 'converse'],
        ['name' => 'Dr Martens 1460 Boots', 'price' => 170, 'image_term' => 'combat boots'],
        ['name' => 'Timberland 6-Inch Boot', 'price' => 198, 'image_term' => 'work boots'],
        ['name' => 'Vans Old Skool Skate', 'price' => 70, 'image_term' => 'vans shoes'],
        ['name' => 'Christian Louboutin', 'price' => 795, 'image_term' => 'red bottom heels'],
        ['name' => 'Birkenstock Arizona', 'price' => 110, 'image_term' => 'sandals'],
        ['name' => 'New Balance 550', 'price' => 110, 'image_term' => 'dad shoes'],
        ['name' => 'Crocs Classic Clog', 'price' => 50, 'image_term' => 'crocs'],
        ['name' => 'UGG Classic Mini Boots', 'price' => 160, 'image_term' => 'ugg boots'],
        ['name' => 'Gucci Ace Sneakers', 'price' => 680, 'image_term' => 'gucci sneakers'],
        ['name' => 'Reebok Club C 85', 'price' => 85, 'image_term' => 'white sneakers'],
        ['name' => 'Asics Gel-Kayano Run', 'price' => 160, 'image_term' => 'running shoes'],
        ['name' => 'Clarks Desert Boots', 'price' => 140, 'image_term' => 'chukka boots'],
        ['name' => 'Steve Madden Platform', 'price' => 90, 'image_term' => 'platform sandals'],
        ['name' => 'Balenciaga Triple S', 'price' => 1100, 'image_term' => 'chunky sneakers'],
        ['name' => 'Hoka One One Clifton', 'price' => 145, 'image_term' => 'running shoes'],
        ['name' => 'Skechers Go Walk', 'price' => 60, 'image_term' => 'walking shoes'],
        ['name' => 'Hunter Tall Rain Boots', 'price' => 160, 'image_term' => 'rain boots'],
        ['name' => 'Allen Edmonds Oxfords', 'price' => 395, 'image_term' => 'dress shoes'],
        ['name' => 'Nike Air Force 1 07', 'price' => 115, 'image_term' => 'white trainers'],
        ['name' => 'Adidas Stan Smith', 'price' => 85, 'image_term' => 'tennis shoes'],
        ['name' => 'Puma Suede Classic', 'price' => 70, 'image_term' => 'suede sneakers'],
        ['name' => 'Doc Martens Chelsea', 'price' => 170, 'image_term' => 'chelsea boots'],
        ['name' => 'Brooks Ghost Running', 'price' => 140, 'image_term' => 'jogging shoes'],
        ['name' => 'Loafers Gucci Horsebit', 'price' => 850, 'image_term' => 'loafers'],
        ['name' => 'High-Top Basketball', 'price' => 130, 'image_term' => 'basketball shoes'],
        ['name' => 'Ballet Flats Tory', 'price' => 220, 'image_term' => 'ballet flats'],
        ['name' => 'Flip Flops Havaianas', 'price' => 26, 'image_term' => 'flip flops']
    ]
];


// Unsplash Image Collections (Generic terms to get decent random images)
function get_image_url($term, $i)
{
    // Use LoremFlickr for reliable random images based on keyword
    // Adding random param to burst cache and ensure variety
    return "https://loremflickr.com/800/600/" . urlencode($term) . "?random=" . $i;
}

// Prepare statements
// Notice: We do NOT insert image_path here anymore
$stmtProduct = $pdo->prepare("INSERT INTO products (name, slug, description, price, sale_price, stock, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
$stmtCategory = $pdo->prepare("INSERT INTO product_category (product_id, category_id) VALUES (?, ?)");
$stmtImage = $pdo->prepare("INSERT INTO product_images (product_id, file_path, `order`) VALUES (?, ?, 1)");

$pdo->beginTransaction();

try {
    foreach ($categories as $cat_id => $info) {
        echo "Seeding {$info['name']}...\n";
        $list = $templates[$info['name']];
        for ($i = 0; $i < $info['count']; $i++) {
            // Pick a template cyclically
            $tpl = $list[$i % count($list)];
            $name = $tpl['name'];
            $base_name = $name;

            // Ensure uniqueness
            $variant = $i + 1;
            // optional: append distinct number in slug only
            $slug_base = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            $slug = $slug_base . '-' . uniqid();

            $desc = "Experience distinct quality with the " . $base_name . ". Designed for durability and style, perfect for " . strtolower($info['name']) . " enthusiasts.";


            $price = $tpl['price'] + rand(-5, 15);
            $sale_price = (rand(0, 10) > 8) ? ($price * 0.85) : null;
            $stock = rand(10, 100);

            // 1. Insert Product
            $stmtProduct->execute([$name, $slug, $desc, $price, $sale_price, $stock]);
            $product_id = $pdo->lastInsertId();

            // 2. Link Category
            $stmtCategory->execute([$product_id, $cat_id]);

            // 3. Insert Image
            // Strict ID-based naming convention
            $image_path = "uploads/products/" . $product_id . ".jpg";
            $stmtImage->execute([$product_id, $image_path]);


        }
        echo "  Added {$info['count']} items to {$info['name']}.\n";
    }

    $pdo->commit();
    echo "Seeding Complete.\n";

}
catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}

echo "</pre>";
?>

<?php require_once __DIR__ . '/../includes/admin_guard.php'; ?>
<?php require_once __DIR__ . '/../config/db.php'; ?>
<?php require_once __DIR__ . '/../includes/db_functions.php'; ?>
<?php
$message = null;

// Load categories
if (db_has_connection()) {
    ensure_core_categories_seeded();
}
$allCategories = db_has_connection() ? get_categories(null) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die('Security check failed. Please refresh the page.');
    }

    if (db_has_connection()) {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $sale_price = $_POST['sale_price'] !== '' ? (float)$_POST['sale_price'] : null;
        $stock = (int)($_POST['stock'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        
        if ($name === '') {
            $message = 'Error: Product name is required.';
        } else {
            // Auto-generate slug if empty
            if ($slug === '') {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }
            
            try {
                $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, sale_price, stock, created_at, is_active) VALUES (:name, :slug, :description, :price, :sale_price, :stock, NOW(), 1)");
                $stmt->execute([
                    ':name' => $name,
                    ':slug' => $slug,
                    ':description' => $description,
                    ':price' => $price,
                    ':sale_price' => $sale_price,
                    ':stock' => $stock
                ]);
                $product_id = $pdo->lastInsertId();
                
                // Handle Categories
                $catIds = isset($_POST['category_ids']) ? (array)$_POST['category_ids'] : [];
                set_product_categories($product_id, $catIds);

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../assets/images/products/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $filename = 'prod_' . $product_id . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $stmtImg = $pdo->prepare("INSERT INTO product_images (product_id, file_path, `order`) VALUES (?, ?, 1)");
                        $stmtImg->execute([$product_id, './assets/images/products/' . $filename]);
                    }
                }

                $message = 'Product added successfully.';
                // Clear form?
                $_POST = []; 
            } catch (Throwable $e) {
                $message = 'Error: ' . $e->getMessage();
            }
        }
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="container">
    <h2>Add New Product</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data" class="d-grid gap-3" style="max-width: 600px;">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        
        <div>
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        
        <div>
            <label class="form-label">Slug (Optional)</label>
            <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>">
        </div>
        
        <div class="row">
            <div class="col">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>
            <div class="col">
                <label class="form-label">Sale Price</label>
                <input type="number" step="0.01" name="sale_price" class="form-control" value="<?php echo htmlspecialchars($_POST['sale_price'] ?? ''); ?>">
            </div>
        </div>
        
        <div>
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>">
        </div>
        
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        
        <div>
            <label class="form-label">Categories</label>
            <div class="card p-2">
                <?php foreach ($allCategories as $cat): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="category_ids[]" value="<?php echo (int)$cat['id']; ?>" id="cat_<?php echo (int)$cat['id']; ?>">
                        <label class="form-check-label" for="cat_<?php echo (int)$cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div>
            <label class="form-label">Main Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

header('Content-Type: text/plain; charset=utf-8');
set_time_limit(0);

$apply = true; // execute changes immediately
$root = realpath(__DIR__ . '/..');
$imgDir = $root . '/assets/images/products';
$relPrefix = './assets/images/products/';

if (!db_has_connection()) {
    echo "DB_CONNECTED=0\n";
    exit(1);
}
echo "DB_CONNECTED=1\n";

// Optional product name mapping (JSON: { "1": "New Name", "2": "Another Name" })
$mappingFile = __DIR__ . '/product_name_map.json';
$nameMap = [];
if (file_exists($mappingFile)) {
    $json = file_get_contents($mappingFile);
    $nameMap = json_decode($json, true) ?: [];
    echo "NAME_MAP_ENTRIES=" . count($nameMap) . "\n";
} else {
    echo "NAME_MAP_ENTRIES=0\n";
}

// Helpers
function title_case_from_filename($filename) {
    $base = strtolower(pathinfo($filename, PATHINFO_FILENAME));
    $norm = preg_replace('/[^a-z0-9]+/',' ', $base);
    $words = array_values(array_filter(explode(' ', $norm), function($w){ return strlen($w) >= 3; }));
    $words = array_slice($words, 0, 4);
    $words = array_map(function($w){ return ucfirst($w); }, $words);
    return $words ? implode(' ', $words) : null;
}
function is_placeholder_name($name) {
    $n = trim($name);
    if ($n === '') return true;
    $lower = strtolower($n);
    if (in_array($lower, ['product','item','sample','test','demo'], true)) return true;
    if (preg_match('/^product\\s*\\d*$/i', $n)) return true;
    if (strlen($n) < 4) return true;
    return false;
}

// Load products
$stmt = $pdo->query("SELECT id, name FROM products ORDER BY id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "PRODUCTS_FOUND=" . count($products) . "\n";

$updatedImages = 0;
$renamedFiles = 0;
$nameUpdates = 0;
$missingLocal = 0;

foreach ($products as $p) {
    $pid = (int)$p['id'];
    $pname = $p['name'];

    // Resolve the best image using existing resolver
    $resolved = resolve_product_image($p);
    $resolvedLocal = null;
    if ($resolved && str_starts_with($resolved, $relPrefix)) {
        $resolvedLocal = $imgDir . '/' . basename($resolved);
    }

    // Ensure product_images primary row points to selected image
    $pri = get_primary_image_path($pid);
    if ($resolved && $resolved !== $pri) {
        // Find first image row (if any)
        $sel = $pdo->prepare("SELECT id, file_path FROM product_images WHERE product_id = :pid ORDER BY `order` ASC, id ASC LIMIT 1");
        $sel->bindParam(':pid', $pid, PDO::PARAM_INT);
        $sel->execute();
        $row = $sel->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $upd = $pdo->prepare("UPDATE product_images SET file_path = :fp WHERE id = :id");
            $upd->execute([':fp' => $resolved, ':id' => $row['id']]);
            echo "UPDATE product_images id={$row['id']} product_id=$pid file_path=" . basename($resolved) . "\n";
        } else {
            $ins = $pdo->prepare("INSERT INTO product_images (product_id, file_path, `order`) VALUES (:pid, :fp, 0)");
            $ins->execute([':pid' => $pid, ':fp' => $resolved]);
            echo "INSERT product_images product_id=$pid file_path=" . basename($resolved) . "\n";
        }
        $updatedImages++;
    }

    // Rename local file to include -{id}- pattern
    if ($resolvedLocal && file_exists($resolvedLocal)) {
        $bn = basename($resolvedLocal);
        if (!preg_match('/-\d+-/',$bn)) {
            $info = pathinfo($bn);
            $newBase = $info['filename'] . '-' . $pid . '-' . '.' . $info['extension'];
            $newPath = $imgDir . '/' . $newBase;
            // Guard against collisions
            if (file_exists($newPath)) {
                $newBase = $info['filename'] . '-' . $pid . '-' . substr(md5($bn),0,6) . '.' . $info['extension'];
                $newPath = $imgDir . '/' . $newBase;
            }
            if (@rename($resolvedLocal, $newPath)) {
                $renamedFiles++;
                $newRel = $relPrefix . basename($newPath);
                // Update product_images primary to renamed path
                $upd = $pdo->prepare("UPDATE product_images SET file_path = :fp WHERE product_id = :pid ORDER BY `order` ASC, id ASC LIMIT 1");
                // MySQL doesn't allow ORDER BY/LIMIT in UPDATE without tricks; do a select-then-update
                $sel = $pdo->prepare("SELECT id FROM product_images WHERE product_id = :pid ORDER BY `order` ASC, id ASC LIMIT 1");
                $sel->execute([':pid' => $pid]);
                if ($rid = $sel->fetchColumn()) {
                    $pdo->prepare("UPDATE product_images SET file_path = :fp WHERE id = :id")->execute([':fp'=>$newRel, ':id'=>$rid]);
                }
                echo "RENAME " . $bn . " -> " . basename($newPath) . " (product_id=$pid)\n";
            }
        }
    } elseif ($resolved && !$resolvedLocal) {
        $missingLocal++;
        echo "MISSING_LOCAL_FILE product_id=$pid resolved=$resolved\n";
    }

    // Bulk-correct placeholder names:
    // 1) If mapping provided, use it
    // 2) Else if placeholder, infer from filename
    if (isset($nameMap[$pid]) && is_string($nameMap[$pid]) && trim($nameMap[$pid]) !== '' && $nameMap[$pid] !== $pname) {
        $newName = trim($nameMap[$pid]);
        $stmtU = $pdo->prepare("UPDATE products SET name = :n WHERE id = :id");
        $stmtU->execute([':n' => $newName, ':id' => $pid]);
        $nameUpdates++;
        echo "UPDATE_NAME id=$pid \"$pname\" -> \"$newName\"\n";
    } elseif (is_placeholder_name($pname) && $resolvedLocal && file_exists($resolvedLocal)) {
        $guess = title_case_from_filename($resolvedLocal);
        if ($guess && $guess !== $pname) {
            $stmtU = $pdo->prepare("UPDATE products SET name = :n WHERE id = :id");
            $stmtU->execute([':n' => $guess, ':id' => $pid]);
            $nameUpdates++;
            echo "INFER_NAME id=$pid \"$pname\" -> \"$guess\"\n";
        }
    }
}

echo "SUMMARY updated_images=$updatedImages renamed_files=$renamedFiles name_updates=$nameUpdates missing_local=$missingLocal\n";
echo "DONE\n";

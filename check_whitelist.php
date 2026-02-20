<?php
// Read index.php
$content = file_get_contents('index.php');

// Extract the line with $allowed
if (preg_match('/\$allowed\s*=\s*\[(.*?)\];/', $content, $matches)) {
    $array_str = $matches[1];
    // Eval it to get the array (safe-ish in this context as we wrote it)
    eval("\$allowed = [$array_str];");

    echo "Found Allowed Pages (" . count($allowed) . "):\n";
    foreach ($allowed as $p) {
        echo "- '$p'\n";
    }

    $required = ['contact', 'faq', 'shipping', 'returns', 'featured', 'new_arrivals'];
    $missing = [];
    foreach ($required as $r) {
        if (!in_array($r, $allowed)) {
            $missing[] = $r;
        }
    }

    if (empty($missing)) {
        echo "\n✅ SUCCESS: All support pages are in the whitelist.\n";
    }
    else {
        echo "\n❌ FAILURE: Missing pages: " . implode(', ', $missing) . "\n";
    }
}
else {
    echo "❌ FALURE: Could not parse \$allowed array from index.php\n";
}

<?php
$content = file_get_contents(__DIR__ . '/includes/db_functions.php');
if (strpos($content, 'password_hash') !== false) {
    echo "password_hash IS PRESENT<br>";
}
else {
    echo "password_hash IS MISSING!<br>";
}
if (strpos($content, 'test_google_db') !== false) {
    echo "Wait, what?<br>";
}
echo "<pre>" . htmlspecialchars(substr($content, -1500)) . "</pre>";
?>

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

if (db_has_connection()) {
    echo "DB Connection Successful\n";
    exit(0);
}
else {
    echo "DB Connection Failed\n";
    exit(1);
}
?>

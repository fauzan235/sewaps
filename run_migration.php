<?php
require_once 'config/db.php';

try {
    $sql = file_get_contents('add_avatar.sql');
    $pdo->exec($sql);
    echo "Column 'avatar' added successfully (or already exists).\n";
} catch (PDOException $e) {
    echo "Error adding column: " . $e->getMessage();
}

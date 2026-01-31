<?php
require_once 'config/db.php';

try {
    $sql = file_get_contents('seed_products.sql');
    $pdo->exec($sql);
    echo "Seed data inserted successfully.\n";
} catch (PDOException $e) {
    echo "Error inserting seed data: " . $e->getMessage();
}

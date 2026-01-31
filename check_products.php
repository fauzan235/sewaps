<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $row = $stmt->fetch();
    echo "Total products: " . $row['count'] . "\n";

    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
    foreach ($products as $p) {
        echo "ID: " . $p['id'] . " | Name: " . $p['name'] . " | Status: " . $p['status'] . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

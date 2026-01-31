<?php
// index.php
require_once 'config/db.php';
session_start();

// Fetch Available Products
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'available' OR status = 'rented' ORDER BY created_at DESC");
$products = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container" style="margin-top: 30px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1>Sewa PlayStation</h1>
        <p>Pilih konsol, sewa, dan mainkan di rumah!</p>
    </div>

    <div class="product-grid">
        <?php foreach($products as $p): ?>
        <div class="product-item">
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <div class="product-info">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p style="color: #666; font-size: 0.9rem;"><?= htmlspecialchars($p['description']) ?></p>
                <div style="margin: 10px 0; font-weight: bold; color: var(--primary);">
                    Rp <?= number_format($p['price_per_day'], 0, ',', '.') ?> / Hari
                </div>
                
                <?php if($p['status'] === 'available'): ?>
                    <a href="rent.php?id=<?= $p['id'] ?>" class="btn" style="width: 100%; text-align: center; display: block;">Sewa Sekarang</a>
                <?php else: ?>
                    <button class="btn btn-secondary" style="width: 100%;" disabled>Sedang Disewa</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

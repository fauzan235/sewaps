<?php
// history.php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT r.*, p.name as product_name, p.image 
    FROM rentals r 
    JOIN products p ON r.product_id = p.id 
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$user_id]);
$rentals = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container" style="margin-top: 30px;">
    <h2>Riwayat Sewa Saya</h2>
    
    <div class="card">
        <?php if(count($rentals) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Konsol</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rentals as $r): ?>
                    <tr>
                        <td>
                            <img src="uploads/<?= htmlspecialchars($r['image']) ?>" style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($r['product_name']) ?></strong><br>
                            <small>ID Sewa: #<?= $r['id'] ?></small>
                        </td>
                        <td><?= $r['rent_date'] ?> s/d <?= $r['return_date'] ?></td>
                        <td>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge" style="padding: 5px 10px; background: #eee; border-radius: 4px;">
                                <?= ucfirst($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada riwayat sewa.</p>
            <a href="index.php" class="btn">Sewa Sekarang</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

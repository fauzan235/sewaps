<?php
// admin/products.php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Kelola Produk</h2>
        <a href="product_add.php" class="btn">Tambah Produk</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th width="100">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga / Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td>
                        <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($p['name']) ?></strong><br>
                        <small style="color: grey;">ID: <?= $p['id'] ?></small>
                    </td>
                    <td>Rp <?= number_format($p['price_per_day'], 0, ',', '.') ?></td>
                    <td>
                        <?php if($p['status'] == 'available'): ?>
                            <span style="color: green; font-weight: bold;">Tersedia</span>
                        <?php elseif($p['status'] == 'rented'): ?>
                            <span style="color: orange; font-weight: bold;">Disewa</span>
                        <?php else: ?>
                            <span style="color: red; font-weight: bold;">Maintenance</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

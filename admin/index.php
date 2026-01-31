<?php
// admin/index.php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get stats
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$totalUsers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'active'");
$activeRentals = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'pending'");
$pendingRentals = $stmt->fetchColumn();

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 20px;">
    <h2>Dashboard Admin</h2>
    <p style="color: grey;">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

    <div class="dashboard-grid" style="margin-top: 30px;">
        <div class="stat-card" style="border-color: #007bff;">
            <h3><?= $totalUsers ?></h3>
            <p>Total User</p>
        </div>
        <div class="stat-card" style="border-color: #28a745;">
            <h3><?= $totalProducts ?></h3>
            <p>Produk / Konsol</p>
        </div>
        <div class="stat-card" style="border-color: #ffc107;">
            <h3><?= $activeRentals ?></h3>
            <p>Sewa Aktif</p>
        </div>
        <div class="stat-card" style="border-color: #dc3545;">
            <h3><?= $pendingRentals ?></h3>
            <p>Menunggu Konfirmasi</p>
        </div>
    </div>
    
    <div class="card" style="margin-top: 30px;">
        <h3>Quick Actions</h3>
        <div style="margin-top: 15px;">
            <a href="products.php" class="btn">Kelola Produk</a>
            <a href="rentals.php" class="btn btn-secondary" style="margin-left: 10px;">Cek Penyewaan</a>
        </div>
    </div>
</div>

</body>
</html>

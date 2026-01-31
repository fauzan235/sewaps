<?php
// admin/rentals.php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Status Update
if (isset($_POST['update_status'])) {
    $rental_id = $_POST['rental_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE rentals SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $rental_id]);

    if ($new_status === 'active') {
        $stmt = $pdo->prepare("UPDATE products SET status = 'rented' WHERE id = (SELECT product_id FROM rentals WHERE id = ?)");
        $stmt->execute([$rental_id]);
    }
    elseif ($new_status === 'returned') {
        $stmt = $pdo->prepare("UPDATE products SET status = 'available' WHERE id = (SELECT product_id FROM rentals WHERE id = ?)");
        $stmt->execute([$rental_id]);
    }
}

$stmt = $pdo->query("
    SELECT r.*, u.username, p.name as product_name, p.image 
    FROM rentals r 
    JOIN users u ON r.user_id = u.id 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC
");
$rentals = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 20px;">
    <h2>Data Penyewaan</h2>
    
    <div class="card" style="margin-top: 20px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Penyewa</th>
                    <th>Produk</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rentals as $r): ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['username']) ?></td>
                    <td><?= htmlspecialchars($r['product_name']) ?></td>
                    <td>
                        <?= $r['rent_date'] ?> s/d <?= $r['return_date'] ?>
                        <br>
                        <strong>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></strong>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; background: #eee; font-size: 0.9rem;">
                            <?= ucfirst($r['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="rental_id" value="<?= $r['id'] ?>">
                            <select name="status" style="padding: 5px;">
                                <option value="pending" <?= $r['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="active" <?= $r['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="returned" <?= $r['status'] == 'returned' ? 'selected' : '' ?>>Returned</option>
                                <option value="cancelled" <?= $r['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                            <button type="submit" class="btn btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

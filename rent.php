<?php
// rent.php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) header("Location: index.php");

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'available'");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<script>alert('Produk tidak tersedia!'); window.location='index.php';</script>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = (int) $_POST['duration'];
    
    if ($duration > 0) {
        $rent_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime("+$duration days"));
        $total_price = $product['price_per_day'] * $duration;
        
        try {
            // Insert Rental
            $stmt = $pdo->prepare("INSERT INTO rentals (user_id, product_id, rent_date, return_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $id, $rent_date, $return_date, $total_price]);
            
            // Set Product status to Rented
            $stmt = $pdo->prepare("UPDATE products SET status = 'rented' WHERE id = ?");
            $stmt->execute([$id]);

            $success = "Permintaan sewa berhasil dikirim!";
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Durasi sewa minimal 1 hari.";
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 800px; margin-top: 30px;">
    <?php if($success): ?>
        <div class="alert" style="background: #d4edda; color: #155724; border-color: #c3e6cb;">
            <h4>Permintaan Terkirim!</h4>
            <p>Admin akan segera memproses pesanan Anda.</p>
            <a href="history.php" class="btn">Lihat Riwayat</a>
        </div>
    <?php else: ?>
        
        <div class="card" style="display: flex; gap: 30px;">
            <div style="flex: 1;">
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" style="border-radius: 8px;">
            </div>
            
            <div style="flex: 1;">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <h3 style="color: var(--primary);">Rp <?= number_format($product['price_per_day'], 0, ',', '.') ?> / hari</h3>
                
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <?php if($error): ?>
                    <div class="alert alert-error"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" style="margin-top: 20px;">
                    <div class="form-group">
                        <label>Durasi Sewa (Hari)</label>
                        <input type="number" name="duration" min="1" value="1" id="duration" class="form-control" oninput="calculateTotal()">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <strong>Total: </strong> <span id="totalPrice" style="font-size: 1.2rem;">Rp <?= number_format($product['price_per_day'], 0, ',', '.') ?></span>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%;">Ajukan Sewa</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function calculateTotal() {
    const price = <?= $product['price_per_day'] ?>;
    const duration = document.getElementById('duration').value;
    const total = price * duration;
    document.getElementById('totalPrice').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}
</script>

<?php include 'includes/footer.php'; ?>

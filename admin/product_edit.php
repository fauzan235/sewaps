<?php
// admin/product_edit.php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) header("Location: products.php");

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) header("Location: products.php");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $status = $_POST['status'];
    
    // Handle Image Upload if exists
    $imagePath = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $destination = '../uploads/' . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $imagePath = $filename;
        } else {
            $error = "Gagal upload gambar baru.";
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price_per_day=?, image=?, status=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $imagePath, $status, $id]);
        $success = "Produk berhasil diupdate!";
        // Refresh data
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    }
}

include 'includes/header.php';
?>

    <div class="admin-header">
        <div>
            <h2>Edit Produk</h2>
            <p class="text-muted">Perbarui informasi konsol ID: #<?= $product['id'] ?></p>
        </div>
        <a href="products.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card" style="max-width: 800px;">
        <?php if($success): ?>
            <div class="alert badge-success mb-20" style="padding: 15px; border-radius: 12px;"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert badge-danger mb-20" style="padding: 15px; border-radius: 12px;"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="d-flex gap-20" style="align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="5" class="form-control" style="resize: vertical;" required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-20">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Harga Sewa / Hari</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price_per_day'] ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>Tersedia</option>
                                <option value="rented" <?= $product['status'] == 'rented' ? 'selected' : '' ?>>Disewa</option>
                                <option value="maintenance" <?= $product['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div style="width: 250px;">
                    <label class="form-label">Gambar Produk</label>
                    <div style="border: 2px dashed var(--border-color); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer;" onclick="document.getElementById('imgInp').click()">
                        <img id="imgPreview" src="../uploads/<?= $product['image'] ?>" alt="Preview" style="display: block; width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
                        <p class="text-muted mt-20" style="font-size: 0.8rem;">Klik untuk ganti gambar</p>
                    </div>
                    <input type='file' id="imgInp" name="image" accept="image/*" style="display: none;" onchange="readURL(this);" />
                </div>
            </div>

            <div class="mt-20 text-center">
                <button type="submit" class="btn" style="width: 100%;"><i class="fas fa-save"></i> Update Produk</button>
            </div>
        </form>
    </div>

    <script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imgPreview').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

<?php include 'includes/footer.php'; ?>

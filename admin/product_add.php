<?php
// admin/product_add.php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $status = $_POST['status'];
    
    // Handle Image Upload
    $image = $_FILES['image'];
    $imagePath = '';
    
    if ($image['error'] === 0) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $destination = '../uploads/' . $filename;
        
        if (move_uploaded_file($image['tmp_name'], $destination)) {
            $imagePath = $filename;
        } else {
            $error = "Gagal upload gambar.";
        }
    } else {
        $error = "Harap pilih gambar produk.";
    }

    if (!$error) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price_per_day, image, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $imagePath, $status]);
            $success = "Produk berhasil ditambahkan!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

    <div class="admin-header">
        <div>
            <h2>Tambah Produk</h2>
            <p class="text-muted">Isi form di bawah untuk menambahkan konsol baru.</p>
        </div>
        <a href="products.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card" style="max-width: 800px;">
        <?php if($error): ?>
            <div class="alert badge-danger mb-20" style="padding: 15px; border-radius: 12px;"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert badge-success mb-20" style="padding: 15px; border-radius: 12px;"><?= $success ?></div>
            <script>setTimeout(() => window.location.href='products.php', 1500);</script>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="d-flex gap-20" style="align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: PlayStation 5 Disc Edition">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="5" class="form-control" style="resize: vertical;" required placeholder="Jelaskan spesifikasi console..."></textarea>
                    </div>
                    
                    <div class="d-flex gap-20">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Harga Sewa / Hari</label>
                            <input type="number" name="price" class="form-control" required placeholder="150000">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Status Awal</label>
                            <select name="status" class="form-control">
                                <option value="available">Tersedia</option>
                                <option value="rented">Sedang Disewa</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div style="width: 250px;">
                    <label class="form-label">Gambar Produk</label>
                    <div style="border: 2px dashed var(--border-color); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; position: relative;" onclick="document.getElementById('imgInp').click()">
                        <div id="previewContainer">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 10px;"></i>
                            <p class="text-muted" style="font-size: 0.9rem;">Klik untuk upload</p>
                        </div>
                        <img id="imgPreview" src="#" alt="Preview" style="display: none; width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <input type='file' id="imgInp" name="image" accept="image/*" style="display: none;" required onchange="readURL(this);" />
                </div>
            </div>

            <div class="mt-20 text-center">
                <button type="submit" class="btn" style="width: 100%;"><i class="fas fa-save"></i> Simpan Produk</button>
            </div>
        </form>
    </div>

    <script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewContainer').style.display = 'none';
                document.getElementById('imgPreview').setAttribute('src', e.target.result);
                document.getElementById('imgPreview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

<?php include 'includes/footer.php'; ?>

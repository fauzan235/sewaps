<?php
// settings.php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Profile Logic
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        // Image Upload
        if (!empty($_FILES['avatar']['name'])) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = "user_" . $user_id . "_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $filename)) {
                $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->execute([$filename, $user_id]);
            }
        }

        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$username, $email, $user_id])) {
            $_SESSION['username'] = $username;
            $success = "Profil berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui profil.";
        }
    }

    // 2. Update Password Logic
    if (isset($_POST['update_password'])) {
        $old_pass = $_POST['old_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (password_verify($old_pass, $user['password'])) {
            if ($new_pass === $confirm_pass) { // Removed strlen check for simplicity, normally >= 8
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hash, $user_id]);
                $success = "Password berhasil diubah.";
            } else {
                $error = "Konfirmasi password baru tidak cocok.";
            }
        } else {
            $error = "Password lama salah.";
        }
    }
}

// Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$avatar = $user['avatar'] ?? 'default_avatar.png'; // Make sure default image exists or handle logic
// Using a placeholder API for default if local file missing?
// Let's rely on fallback in UI

include 'includes/header.php';
?>

<div class="container" style="max-width: 900px;">
    <h2>Pengaturan Akun</h2>
    
    <?php if($success): ?><div class="alert" style="background: rgba(85, 239, 196, 0.2); color: var(--success); padding: 15px; border-radius: 10px; margin-bottom: 20px;"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert" style="background: rgba(255, 118, 117, 0.2); color: var(--danger); padding: 15px; border-radius: 10px; margin-bottom: 20px;"><?= $error ?></div><?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        
        <!-- Profile Card -->
        <div class="product-card" style="padding: 30px;">
            <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">Edit Profil</h3>
            <form method="POST" enctype="multipart/form-data" class="auth-form" style="background: none; box-shadow: none; padding: 0;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="uploads/<?= $avatar ?>" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=random'" 
                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary);">
                    <br>
                    <label for="avatar" style="color: var(--primary); cursor: pointer; font-size: 0.9rem; font-weight: 500;">Ganti Foto</label>
                    <input type="file" name="avatar" id="avatar" style="display: none;">
                </div>

                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <button type="submit" name="update_profile" class="btn" style="width: 100%;">Simpan Profil</button>
            </form>
        </div>

        <!-- Security & Appearance -->
        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            <!-- Appearance Card -->
            <div class="product-card" style="padding: 30px;">
                <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">Tampilan</h3>
                <label style="display: block; margin-bottom: 10px; font-weight: 500;">Mode Tema</label>
                <select id="themeSelect" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                    <option value="light">Light Mode ☀️</option>
                    <option value="dark">Dark Mode 🌙</option>
                    <option value="system">Ikuti Sistem 💻</option>
                </select>
            </div>

            <!-- Security Card -->
            <div class="product-card" style="padding: 30px;">
                <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">Keamanan</h3>
                <form method="POST" class="auth-form" style="background: none; box-shadow: none; padding: 0;">
                    <label>Password Lama</label>
                    <input type="password" name="old_password" required>
                    
                    <label>Password Baru</label>
                    <input type="password" name="new_password" required>
                    
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required>

                    <button type="submit" name="update_password" class="btn btn-secondary" style="width: 100%;">Ubah Password</button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
// register.php
require_once 'config/db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            // Cek email dulu
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Email sudah terdaftar!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $success = "Pendaftaran berhasil! Silakan login.";
                    // Redirect delay or link to login
                } else {
                    $error = "Gagal mendaftar.";
                }
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    } else {
        $error = "Silakan isi semua kolom.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Web Sewa PS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-container">
    <h2>Daftar Akun Baru</h2>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert" style="background-color: rgba(3, 218, 198, 0.2); color: var(--secondary); border: 1px solid var(--secondary);">
            <?= htmlspecialchars($success) ?> <br>
            <a href="login.php" style="font-weight: bold;">Login sekarang</a>
        </div>
    <?php endif; ?>

    <form class="auth-form" method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn btn-secondary">Daftar</button>
    </form>
    
    <p style="margin-top: 15px; color: var(--text-muted);">
        Sudah punya akun? <a href="login.php">Login disini</a>
    </p>
</div>

</body>
</html>

<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for user avatar if logged in
if (isset($_SESSION['user_id'])) {
    // We might want to store avatar in session to avoid query on every page load
    // But for now let's query just to span 'users' or assume session has it if we updated login (we didn't yet)
    // To be safe/simple without modifying login.php massively, let's fetch lazily or just trust session if updated.
    // Ideally: $avatar_url = $_SESSION['avatar'] ?? 'default.png';
}

// Detect if we are in admin directory
$isInAdmin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;
$basePath = $isInAdmin ? '../' : './';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Sewa PS</title>
    <!-- Dynamic CSS Path -->
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= $basePath ?>assets/js/theme.js" defer></script>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <div class="logo">
            <i class="fas fa-gamepad" style="color: var(--primary);"></i>
            <a href="<?= $basePath ?>index.php" style="color: var(--text-main); text-decoration:none;">SewaPS</a>
        </div>
        
        <div class="nav-toggle" id="navToggle">
            <i class="fas fa-bars"></i>
        </div>

        <div class="nav-links" id="navLinks">
            <a href="<?= $basePath ?>index.php" class="<?= !$isInAdmin && $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="<?= $basePath ?>admin/index.php" class="<?= $isInAdmin ? 'active' : '' ?>">Admin Panel</a>
                    <a href="<?= $basePath ?>settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">Setting</a>
                <?php else: ?>
                    <a href="<?= $basePath ?>history.php" class="<?= $current_page == 'history.php' ? 'active' : '' ?>">Riwayat</a>
                    <a href="<?= $basePath ?>settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">Setting</a>
                <?php endif; ?>
                
                <a href="<?= $basePath ?>logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 8px 20px; font-size: 0.9rem;">Logout</a>
            <?php else: ?>
                <a href="<?= $basePath ?>login.php" class="<?= $current_page == 'login.php' ? 'active' : '' ?>">Login</a>
                <a href="<?= $basePath ?>register.php" class="btn">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
</script>

<div class="container main-content" style="min-height: 80vh; padding-top: 40px;">

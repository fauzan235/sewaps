<?php
// admin/includes/sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-gamepad" style="font-size: 1.8rem;"></i>
        <span>AdminPanel</span>
    </div>
    
    <nav class="sidebar-menu">
        <a href="index.php" class="sidebar-link <?= $currentPage == 'index.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="products.php" class="sidebar-link <?= (strpos($currentPage, 'product') !== false) || $currentPage == 'products.php' ? 'active' : '' ?>">
            <i class="fas fa-box"></i> Produk
        </a>
        <a href="rentals.php" class="sidebar-link <?= $currentPage == 'rentals.php' ? 'active' : '' ?>">
            <i class="fas fa-file-invoice-dollar"></i> Penyewaan
        </a>
        
        <!-- Spacer for bottom items -->
        <div style="flex: 1;"></div>
        
        <a href="../logout.php" class="sidebar-link" style="color: var(--danger);">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>

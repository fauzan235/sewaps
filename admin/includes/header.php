<?php
// admin/includes/header.php
// This header is just for the META tags and opening BODY structure.
// Sidebar is included manually in layouts.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SewaPS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: var(--bg-body);">

<div class="admin-layout">
    <!-- Sidebar Included via PHP -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <!-- Top Mobile Toggle (Visible only on mobile via CSS) -->
        <div class="admin-topbar d-md-none" style="margin-bottom: 20px; display: none;"> <!-- Hidden for now unless media query needs it -->
             <button id="sidebarToggle" class="btn btn-sm btn-secondary"><i class="fas fa-bars"></i></button>
        </div>

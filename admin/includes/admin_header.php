<?php
/**
 * FILE: admin/includes/admin_header.php
 * PURPOSE: Admin panel HTML head, sidebar navigation, and opening body.
 * EXPECTS: $admin_page_title (string) — set before including this file.
 */

$admin_page_title = $admin_page_title ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($admin_page_title) ?> — HRI Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="<?= SITE_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body style="font-family:'Poppins',sans-serif;">

<div class="d-flex" id="hri-admin-wrapper">

    <!-- ===== SIDEBAR ===== -->
    <nav class="hri-admin-sidebar bg-dark text-white" style="width:250px;min-height:100vh;">
        <div class="p-3">
            <h5 class="text-white mb-0">🟢 HRI Admin</h5>
            <small class="text-white-50">Panel d'administration</small>
        </div>
        <hr class="border-secondary mx-3">
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/index.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/products.php">
                    <i class="bi bi-box-seam me-2"></i> Produits
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/product_add.php">
                    <i class="bi bi-plus-circle me-2"></i> Ajouter Produit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/categories.php">
                    <i class="bi bi-folder me-2"></i> Catégories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/orders.php">
                    <i class="bi bi-receipt me-2"></i> Commandes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/customers.php">
                    <i class="bi bi-people me-2"></i> Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>/admin/settings.php">
                    <i class="bi bi-gear me-2"></i> Paramètres
                </a>
            </li>
            <hr class="border-secondary mx-1">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= SITE_URL ?>" target="_blank">
                    <i class="bi bi-eye me-2"></i> Voir le site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= SITE_URL ?>/admin/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </a>
            </li>
        </ul>
    </nav>

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-grow-1">
        <!-- Top bar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-text fw-semibold"><?= htmlspecialchars($admin_page_title) ?></span>
            <span class="navbar-text small text-muted">
                <i class="bi bi-person-circle"></i>
                <?= htmlspecialchars($_SESSION['hri_admin_name'] ?? 'Admin') ?>
            </span>
        </nav>

        <div class="p-4">

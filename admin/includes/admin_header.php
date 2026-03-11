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
    <title><?= htmlspecialchars($admin_page_title) ?> — Admin Control Center</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --princeton-orange: #ff8200;
            --carbon-black: #171711;
            --white: #ffffff;
            --gray-bg: #f8fafc;
            --gray-border: #e2e8f0;
            --text-muted: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --sidebar-width: 280px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* UI Scaling */
            --font-size-base: 16px;
            --font-size-lg: 18px;
            --font-size-xl: 24px;
            --border-radius-lg: 24px;
            --border-radius-md: 16px;
        }

        body {
            margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--gray-bg); color: var(--carbon-black);
            display: flex; min-height: 100vh;
            font-size: var(--font-size-base);
        }

        /* --- SIDEBAR --- */
        aside {
            width: var(--sidebar-width); background: var(--carbon-black);
            color: white; padding: 40px 25px; display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 100;
            box-sizing: border-box;
        }
        .brand { font-weight: 900; font-size: 20px; margin-bottom: 50px; display: flex; align-items: center; gap: 12px; text-decoration: none; color: white;}
        .brand span { color: var(--princeton-orange); }
        .nav-link {
            display: flex; align-items: center; gap: 14px; padding: 16px 20px;
            color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 700;
            font-size: 15px; border-radius: 16px; margin-bottom: 8px; cursor: pointer; transition: var(--transition);
        }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .nav-link.active i, .nav-link.active [data-lucide] { color: var(--princeton-orange); }

        /* --- MAIN CONTENT --- */
        main { margin-left: var(--sidebar-width); flex-grow: 1; padding: 50px; width: calc(100% - var(--sidebar-width)); box-sizing: border-box; }
        .view-section { animation: fadeIn 0.4s ease; max-width: 1400px; margin: 0 auto; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* --- UI COMPONENTS --- */
        .card { background: white; border-radius: var(--border-radius-lg); border: 1px solid var(--gray-border); padding: 35px; margin-bottom: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-bottom: 40px; }
        .stat-box { background: white; padding: 30px; border-radius: var(--border-radius-md); border: 1px solid var(--gray-border); text-decoration: none; color: inherit; transition: var(--transition); }
        .stat-box:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-box small { font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .stat-box h2 { margin: 12px 0 0; font-size: 32px; font-weight: 900; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 12px; text-transform: uppercase; color: var(--text-muted); padding: 20px; border-bottom: 1px solid var(--gray-bg); font-weight: 800; letter-spacing: 0.5px; }
        td { padding: 20px; font-size: 15px; font-weight: 600; border-bottom: 1px solid var(--gray-bg); }

        .btn { padding: 14px 24px; border-radius: 14px; font-weight: 800; font-size: 14px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; transition: var(--transition); }
        .btn-orange { background: var(--princeton-orange); color: white; }
        .btn-orange:hover { background: #e67500; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255,130,0,0.2); }
        .btn-light { background: var(--gray-bg); color: var(--carbon-black); border: 1px solid var(--gray-border); }
        .btn-light:hover { background: var(--gray-border); }
        
        .status-pill { padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: 800; }
        .status-pending { background: #fffbeb; color: #92400e; }
        .status-success { background: #f0fdf4; color: #166534; }
        .status-info { background: #eff6ff; color: #1e40af; }
        .status-danger { background: #fef2f2; color: #991b1b; }
        
        .form-group { margin-bottom: 25px; text-align: left; }
        label { display: block; font-size: 13px; font-weight: 800; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { 
            width: 100%; padding: 16px 20px; border-radius: 14px; 
            border: 1px solid var(--gray-border); font-family: inherit; 
            font-weight: 600; box-sizing: border-box; font-size: 15px;
            transition: var(--transition);
        }
        .form-control:focus { outline: none; border-color: var(--princeton-orange); box-shadow: 0 0 0 4px rgba(255,130,0,0.1); }
        
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .top-bar h1 { margin: 0; font-weight: 900; font-size: 32px; }

        /* Notification Toast */
        #hri-notifications-container {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
        }
        .hri-notification {
            background: white; border-radius: 16px; border: 1px solid var(--gray-border);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 20px;
            width: 320px; display: flex; gap: 15px;
            animation: slideInRight 0.3s ease-out; position: relative;
        }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .hri-notification__icon {
            width: 40px; height: 40px; background: #fffbeb; color: #92400e;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .hri-notification__close { position: absolute; top: 10px; right: 10px; cursor: pointer; color: var(--text-muted); }
        .hri-notification__title { font-weight: 800; font-size: 14px; margin-bottom: 2px; }
        .hri-notification__text { font-size: 13px; color: var(--text-muted); font-weight: 600; }
        .hri-notification__btn { margin-top: 12px; font-size: 12px; font-weight: 800; color: var(--princeton-orange); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            aside { width: 80px; padding: 30px 15px; }
            .brand span, .nav-link span { display: none; }
            main { margin-left: 80px; width: calc(100% - 80px); padding: 30px; }
            .nav-link { justify-content: center; padding: 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .top-bar h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

    <aside id="main-sidebar">
        <a href="<?= SITE_URL ?>/admin/index.php" class="brand">
            <i data-lucide="layout-grid"></i> <span>MATJAR<span>.</span>ADMIN</span>
        </a>
        
        <a href="<?= SITE_URL ?>/admin/index.php" class="nav-link <?= $admin_page_title == 'Tableau de Bord' ? 'active' : '' ?>">
            <i data-lucide="home"></i> <span>Dashboard</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/orders.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'order') !== false ? 'active' : '' ?>">
            <i data-lucide="shopping-bag"></i> <span>Commandes</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/products.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], 'product') !== false && strpos($_SERVER['PHP_SELF'], 'product_add') === false) ? 'active' : '' ?>">
            <i data-lucide="package"></i> <span>Produits</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/product_add.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'product_add') !== false ? 'active' : '' ?>">
            <i data-lucide="plus-square"></i> <span>Ajouter Produit</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/categories.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'categor') !== false ? 'active' : '' ?>">
            <i data-lucide="layers"></i> <span>Catégories</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/customers.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'customer') !== false ? 'active' : '' ?>">
            <i data-lucide="users"></i> <span>Clients</span>
        </a>
        <a href="<?= SITE_URL ?>/admin/settings.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : '' ?>">
            <i data-lucide="settings"></i> <span>Paramètres</span>
        </a>
        
        <a href="<?= SITE_URL ?>/admin/logout.php" class="nav-link" style="margin-top: auto; color: var(--danger);">
            <i data-lucide="log-out"></i> <span>Déconnexion</span>
        </a>
    </aside>

    <div id="hri-notifications-container"></div>

    <main id="main-content">
        <div class="top-bar">
            <h1><?= htmlspecialchars($admin_page_title) ?></h1>
            <div style="display: flex; align-items: center; gap: 20px;">
                <span style="font-size: 15px; font-weight: 700; color: var(--text-muted);">
                    <i data-lucide="user" size="18" style="vertical-align: middle; margin-right: 8px;"></i>
                    <?= htmlspecialchars($_SESSION['hri_admin_name'] ?? 'Admin') ?>
                </span>
                <a href="<?= SITE_URL ?>" target="_blank" class="btn btn-light" style="padding: 10px 20px;">
                    <i data-lucide="external-link" size="18"></i> <span>Voir site</span>
                </a>
            </div>
        </div>

        <div class="view-section">

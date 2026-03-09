<?php
/**
 * FILE: includes/header.php
 * PURPOSE: Rebuilt header with Hero Loader and Search Overlay.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$cart_count = get_cart_item_count();
$active_categories = get_active_categories();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : $lang['site_name']; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <?php if ($is_rtl): ?>
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/rtl.css">
    <?php endif; ?>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Global App Configuration -->
    <script>
        const SITE_URL = "<?php echo SITE_URL; ?>";
        const HriApp = {
            siteUrl: "<?php echo SITE_URL; ?>",
            ajaxUrl: "<?php echo SITE_URL; ?>/ajax",
            currentLanguage: "<?php echo $current_language; ?>",
            isRtl: <?php echo $is_rtl ? 'true' : 'false'; ?>,
            currency: "DH"
        };
    </script>
</head>
<body id="hri-body" class="<?php echo $is_rtl ? 'is-rtl' : ''; ?>">

    <!-- 1. HERO HOME LOADER (Initial Scan) -->
    <div id="hri-home-loader">
        <div class="barcode-wrap">
            <div class="laser"></div>
            <i data-lucide="barcode" size="64"></i>
            <div style="margin-top:15px; font-weight:900; letter-spacing:1px;">MATJAR<span style="color:var(--princeton-orange)">.</span>KOTOBIA</div>
        </div>
    </div>

    <!-- 2. MICRO DATA LOADER (Processing State) -->
    <div id="hri-data-handler">
        <div class="loading-center-icon">
            <div class="pulse-ring"></div>
            <i id="dynamic-loader-icon" data-lucide="shopping-basket" size="32"></i>
        </div>
    </div>
    
    <!-- Top Progress Bar -->
    <div id="hri-top-bar"></div>

    <!-- 3. FULL-SCREEN SEARCH OVERLAY -->
    <div id="hri-search-overlay">
        <div class="container h-100 d-flex flex-column p-0">
            <div class="search-top">
                <div class="close-search" onclick="closeSearch()">
                    <i data-lucide="arrow-left" size="28"></i>
                </div>
                <div class="flex-grow-1">
                    <input type="text" id="main-search-input" class="full-search-input" placeholder="<?php echo $current_language === 'ar' ? 'أنا أبحث عن...' : 'Je cherche...'; ?>" autocomplete="off">
                </div>
            </div>

            <div id="hri-search-loader">
                <div style="position:relative; display:flex; align-items:center; justify-content:center;">
                    <div style="position:absolute; width:60px; height:60px; border:2px solid var(--princeton-orange); border-radius:50%; animation: ring-pulse 1s infinite;"></div>
                    <i data-lucide="refresh-cw" class="spin" style="color:var(--princeton-orange)"></i>
                </div>
            </div>

            <div class="results-container" id="hri-search-results"></div>
        </div>
    </div>

    <!-- Main Navigation & Content -->
    <div id="hri-main-wrapper">
        
        <!-- Flash Notification Bar -->
        <div id="hri-flash-bar" class="hri-flash-bar">
            <div class="container d-flex justify-content-center align-items-center">
                <span><?php echo $current_language === 'ar' ? 'تمت إضافة المنتج بنجاح' : 'Produit ajouté avec succès'; ?></span>
            </div>
        </div>

        <div class="container mt-3">
            <?php echo render_flash_messages(); ?>
        </div>

        <!-- Main Navbar (Sticky) -->
        <nav class="hri-navbar sticky-top">
            <div class="container d-flex align-items-center justify-content-between h-100">
                <div class="d-flex align-items-center gap-2">
                    <button id="hri-drawer-open" class="icon-trigger" type="button">
                        <i data-lucide="menu"></i>
                    </button>
                    <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                        MATJAR<span>.</span>KOTOBIA
                    </a>
                </div>

                <!-- Search Trigger Bar -->
                <div class="hri-search-trigger d-none d-md-flex" onclick="openSearch()">
                    <i data-lucide="search" size="18"></i>
                    <span><?php echo $current_language === 'ar' ? 'ابحث عن منتج...' : 'Rechercher un produit...'; ?></span>
                </div>

                <!-- Icons Area -->
                <div class="d-flex align-items-center gap-2">
                    <button class="icon-trigger d-md-none" onclick="openSearch()">
                        <i data-lucide="search"></i>
                    </button>
                    <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="icon-trigger position-relative text-decoration-none">
                        <i data-lucide="shopping-cart"></i>
                        <span id="hri-cart-badge" class="badge-cart <?php echo $cart_count > 0 ? '' : 'd-none'; ?>">
                            <?php echo $cart_count; ?>
                        </span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Drawer -->
        <div id="hri-drawer-overlay" class="hri-drawer-overlay"></div>
        <div id="hri-drawer" class="hri-drawer">
            <div class="hri-drawer-header">
                <button id="hri-drawer-close" class="icon-trigger">
                    <i data-lucide="x"></i>
                </button>
                <div class="logo">MATJAR<span>.</span>KOTOBIA</div>
            </div>
            <div class="hri-drawer-content">
                <div class="hri-drawer-section-title"><?php echo $current_language === 'ar' ? 'القائمة الرئيسية' : 'MENU PRINCIPAL'; ?></div>
                <nav class="drawer-links">
                    <a href="<?php echo SITE_URL; ?>/index.php" class="hri-drawer-item">
                        <i data-lucide="home"></i> <span class="label"><?php echo $lang['home']; ?></span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/order_history.php" class="hri-drawer-item">
                        <i data-lucide="package"></i> <span class="label"><?php echo $lang['order_history'] ?? 'Mes Commandes'; ?></span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="hri-drawer-item">
                        <i data-lucide="user"></i> <span class="label"><?php echo $lang['my_account']; ?></span>
                    </a>

                    <div class="hri-drawer-section-title mt-4"><?php echo $lang['categories']; ?></div>
                    <?php foreach($active_categories as $cat): ?>
                        <a href="<?php echo SITE_URL; ?>/pages/category.php?slug=<?php echo $cat['category_slug']; ?>" class="hri-drawer-item">
                            <i class="bi <?php echo $cat['category_icon'] ?? 'bi-grid'; ?>"></i> 
                            <span class="label"><?php echo htmlspecialchars($current_language === 'ar' ? $cat['category_name_ar'] : $cat['category_name_fr']); ?></span>
                        </a>
                    <?php endforeach; ?>

                    <div class="hri-drawer-section-title mt-4"><?php echo $lang['language']; ?></div>
                    <a href="?lang=fr" class="hri-drawer-item <?php echo $current_language === 'fr' ? 'active' : ''; ?>">
                        <i data-lucide="languages"></i> <span class="label">Français</span>
                    </a>
                    <a href="?lang=ar" class="hri-drawer-item <?php echo $current_language === 'ar' ? 'active' : ''; ?>">
                        <i data-lucide="languages"></i> <span class="label">العربية</span>
                    </a>
                </nav>
            </div>
        </div>

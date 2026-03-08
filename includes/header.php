<?php
/**
 * FILE: includes/header.php
 * PURPOSE: Global site header with Jumia-inspired layout and new brand identity.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$cart_count = get_cart_item_count();
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
        const HriApp = {
            siteUrl: "<?php echo SITE_URL; ?>",
            ajaxUrl: "<?php echo SITE_URL; ?>/ajax",
            currentLanguage: "<?php echo $current_language; ?>",
            isRtl: <?php echo $is_rtl ? 'true' : 'false'; ?>,
            currency: "DH"
        };
    </script>
</head>
<body class="<?php echo $is_rtl ? 'rtl-mode' : ''; ?>">

    <!-- Drawer Overlay -->
    <div id="hri-drawer-overlay" class="hri-drawer-overlay"></div>

    <!-- Jumia-style Drawer -->
    <div id="hri-drawer" class="hri-drawer">
        <div class="hri-drawer-header">
            <i data-lucide="x" class="hri-drawer-close" id="hri-drawer-close"></i>
            <div class="hri-drawer-logo">
                <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo.png" alt="Logo" height="30" onerror="this.style.display='none'">
            </div>
        </div>

        <div class="hri-drawer-content">
            <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="hri-drawer-item header-link">
                <span class="label">BESOIN D'AIDE?</span>
                <i data-lucide="chevron-right" class="chevron-right"></i>
            </a>
            <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="hri-drawer-item header-link">
                <span class="label">VOTRE COMPTE</span>
                <i data-lucide="chevron-right" class="chevron-right"></i>
            </a>

            <div class="hri-drawer-list">
                <a href="<?php echo SITE_URL; ?>/pages/order_history.php" class="hri-drawer-item"><i data-lucide="package"></i><span class="label">Vos commandes</span></a>
                <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="hri-drawer-item"><i data-lucide="heart"></i><span class="label">Favoris</span></a>
            </div>

            <div class="hri-drawer-section-title">
                <span>NOS CATÉGORIES</span>
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="view-more">Voir plus</a>
            </div>

            <div class="hri-drawer-list">
                <?php 
                $drawer_cats = get_active_categories();
                foreach($drawer_cats as $cat): 
                ?>
                    <a href="<?php echo SITE_URL; ?>/pages/category.php?slug=<?php echo $cat['category_slug']; ?>" class="hri-drawer-item">
                        <i class="<?php echo $cat['category_icon'] ?? 'bi-grid'; ?>"></i>
                        <span class="label"><?php echo htmlspecialchars($current_language === 'ar' ? $cat['category_name_ar'] : $cat['category_name_fr']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Flash Notification Bar -->
    <div id="hri-flash-bar" class="hri-flash-bar">
        <div class="container d-flex justify-content-center align-items-center position-relative">
            <span><i class="bi bi-check-circle-fill me-2"></i> <?php echo $current_language === 'ar' ? 'تمت إضافة المنتج بنجاح' : 'Produit ajouté avec succès'; ?></span>
            <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" onclick="this.closest('#hri-flash-bar').style.display='none'"></button>
        </div>
    </div>

    <!-- Top Bar (Desktop Only) -->
    <div class="hri-top-bar d-none d-lg-block py-1">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="text-primary fw-bold">
                <i class="bi bi-box-seam me-1"></i> <?php echo $current_language === 'ar' ? 'توصيل في آسفي' : 'Livraison à Safi'; ?>
            </div>
            <div class="store-name fw-bold text-dark">
                <?php echo $lang['site_name']; ?>
            </div>
            <div class="language-switcher">
                <a href="?lang=fr" class="text-decoration-none <?php echo $current_language === 'fr' ? 'fw-bold text-primary' : 'text-muted'; ?>">Français</a>
                <span class="text-muted mx-1">|</span>
                <a href="?lang=ar" class="text-decoration-none <?php echo $current_language === 'ar' ? 'fw-bold text-primary' : 'text-muted'; ?>">العربية</a>
            </div>
        </div>
    </div>

    <!-- Main Navbar (Sticky) -->
    <nav class="hri-navbar sticky-top shadow-sm">
        <div class="container">
            <div class="row align-items-center g-2 g-lg-3">
                <!-- Toggle + Logo -->
                <div class="col-auto d-flex align-items-center">
                    <button id="hri-drawer-open" class="btn border-0 p-1 me-2" type="button">
                        <i class="bi bi-list fs-2"></i>
                    </button>
                    <a href="<?php echo SITE_URL; ?>/index.php" class="navbar-brand m-0">
                        <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo.png" alt="Matjar El Kotobia" height="40" onerror="this.src='https://placehold.co/120x40/2E7D32/white?text=LOGO'">
                    </a>
                </div>

                <!-- Center: Search Bar -->
                <div class="col col-lg-6 mx-lg-auto order-3 order-lg-2">
                    <form action="<?php echo SITE_URL; ?>/pages/products.php" method="GET" class="hri-search-group shadow-sm">
                        <input type="text" name="q" class="hri-search-input" placeholder="<?php echo $current_language === 'ar' ? 'ابحث عن منتج...' : 'Cherchez un produit...'; ?>" autocomplete="off" id="search-input">
                        <button type="submit" class="hri-search-btn d-none d-md-block">
                            <?php echo $current_language === 'ar' ? 'بحث' : 'Rechercher'; ?>
                        </button>
                        <button type="submit" class="btn border-0 d-md-none text-primary">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                    </form>
                    <div id="search-results" class="position-absolute bg-white shadow-sm w-100 rounded-bottom d-none" style="z-index: 1050; max-height: 400px; overflow-y: auto; margin-top: -5px;"></div>
                </div>

                <!-- Right: Icons/Links -->
                <div class="col-auto ms-auto order-2 order-lg-3 d-flex align-items-center gap-2 gap-md-4">
                    <!-- Help (Desktop) -->
                    <div class="dropdown d-none d-xl-block">
                        <a href="#" class="text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-question-circle fs-5"></i> <span class="ms-1">Aide</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="#">Centre d'assistance</a></li>
                            <li><a class="dropdown-item" href="#">Suivre ma commande</a></li>
                        </ul>
                    </div>

                    <!-- User Account (Desktop Only) -->
                    <div class="dropdown d-none d-lg-block">
                        <a href="#" class="text-dark text-decoration-none d-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="bi bi-person fs-4"></i>
                            <span class="ms-1 d-none d-lg-inline"><?php echo $lang['login'] ?? 'Se connecter'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item fw-bold text-primary" href="<?php echo SITE_URL; ?>/pages/login.php"><?php echo $lang['login']; ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/profile.php"><i class="bi bi-person me-2"></i> Mon Compte</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/order_history.php"><i class="bi bi-bag me-2"></i> Mes Commandes</a></li>
                        </ul>
                    </div>

                    <!-- Cart -->
                    <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="text-dark text-decoration-none position-relative d-flex align-items-center">
                        <i class="bi bi-cart3 fs-4"></i>
                        <span class="ms-1 d-none d-lg-inline"><?php echo $lang['cart'] ?? 'Panier'; ?></span>
                        <span id="hri-cart-badge" class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-warning text-dark <?php echo $cart_count > 0 ? '' : 'd-none'; ?>" style="font-size: 0.65rem;">
                            <?php echo $cart_count; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

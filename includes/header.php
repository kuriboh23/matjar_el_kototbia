<?php
/**
 * FILE: includes/header.php
 * PURPOSE: Customer-facing HTML <head>, navbar, and opening <body> tag.
 *          Included at the top of every customer page.
 * EXPECTS: $page_title (string) — set before including this file.
 * GLOBALS USED: $current_language, $is_rtl, $text_direction, $font_family, $lang
 */

// Default page title if not set
$page_title = $page_title ?? translate('site_name');
$cart_count = get_cart_item_count();
?>
<!DOCTYPE html>
<html lang="<?= $current_language ?>" dir="<?= $text_direction ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars(translate('site_tagline')) ?>">
    <title><?= htmlspecialchars($page_title) ?> — <?= SITE_NAME_FR ?></title>

    <!-- Google Fonts: Poppins (French) + Cairo (Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <?php if ($is_rtl): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Stylesheets -->
    <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
    <?php if ($is_rtl): ?>
        <link href="<?= SITE_URL ?>/assets/css/rtl.css" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/logo/favicon.png">

    <!-- Pass PHP variables to JavaScript -->
    <script>
        const HriApp = {
            siteUrl: '<?= SITE_URL ?>',
            ajaxUrl: '<?= SITE_URL ?>/ajax',
            currentLanguage: '<?= $current_language ?>',
            isRtl: <?= $is_rtl ? 'true' : 'false' ?>,
            currency: '<?= DEFAULT_CURRENCY ?>',
            whatsappNumber: '<?= STORE_WHATSAPP_NUMBER ?>',
            minimumOrderAmount: <?= MINIMUM_ORDER_AMOUNT ?>,
            deliveryFee: <?= DELIVERY_FEE ?>,
            freeDeliveryThreshold: <?= FREE_DELIVERY_THRESHOLD ?>,
            csrfToken: '<?= generate_csrf_token() ?>',
        };
    </script>
</head>
<body class="<?= $is_rtl ? 'hri-rtl' : 'hri-ltr' ?>" style="font-family: <?= $font_family ?>;">

    <!-- ===== TOP NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark hri-navbar sticky-top">
        <div class="container">
            <!-- Logo / Brand -->
            <a class="navbar-brand d-flex align-items-center" href="<?= SITE_URL ?>">
                <span class="fw-bold"><?= translate('site_name') ?></span>
            </a>

            <!-- Mobile: Cart badge + Hamburger -->
            <div class="d-flex align-items-center d-lg-none">
                <a href="<?= SITE_URL ?>/pages/cart.php" class="btn btn-outline-light position-relative me-2">
                    <i class="bi bi-cart3"></i>
                    <span id="cart-badge-mobile" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= $cart_count === 0 ? 'd-none' : '' ?>">
                        <?= $cart_count ?>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#hriNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Collapsible Nav Content -->
            <div class="collapse navbar-collapse" id="hriNavbar">

                <!-- Search Bar -->
                <form class="d-flex mx-lg-3 my-2 my-lg-0 flex-grow-1" action="<?= SITE_URL ?>/pages/search_results.php" method="GET" role="search">
                    <div class="input-group">
                        <input type="search" id="search-input" name="q" class="form-control"
                               placeholder="<?= translate('search_placeholder') ?>"
                               autocomplete="off">
                        <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                    <!-- Live search results dropdown -->
                    <div id="search-results" class="hri-search-results d-none"></div>
                </form>

                <!-- Nav Links -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>"><?= translate('home') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/pages/products.php"><?= translate('products') ?></a>
                    </li>

                    <!-- Language Switcher -->
                    <li class="nav-item">
                        <?php if ($current_language === 'fr'): ?>
                            <a class="nav-link" href="<?= SITE_URL ?>/pages/switch_language.php?lang=ar">العربية</a>
                        <?php else: ?>
                            <a class="nav-link" href="<?= SITE_URL ?>/pages/switch_language.php?lang=fr">Français</a>
                        <?php endif; ?>
                    </li>

                    <!-- Account -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (is_customer_logged_in()): ?>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/profile.php"><?= translate('profile') ?></a></li>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/order_history.php"><?= translate('order_history') ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/logout.php"><?= translate('logout') ?></a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/login.php"><?= translate('login') ?></a></li>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/register.php"><?= translate('register') ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- Desktop Cart -->
                    <li class="nav-item d-none d-lg-block">
                        <a href="<?= SITE_URL ?>/pages/cart.php" class="btn btn-outline-light position-relative ms-2">
                            <i class="bi bi-cart3"></i> <?= translate('cart') ?>
                            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= $cart_count === 0 ? 'd-none' : '' ?>">
                                <?= $cart_count ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===== FLASH MESSAGES ===== -->
    <div class="container mt-3">
        <?= render_flash_messages() ?>
    </div>

    <!-- ===== MAIN CONTENT STARTS ===== -->
    <main class="hri-main-content">

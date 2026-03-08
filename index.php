<?php
/**
 * FILE: index.php
 * PURPOSE: Homepage of Matjar El Kotobia.
 *          Displays hero banner, category bar, featured products,
 *          sale products, and general store information.
 */

// Load master configuration (DB, constants, functions, language, session)
require_once __DIR__ . '/config/config.php';

// Set page title for <head>
$page_title = translate('home');

// Fetch data for homepage
$category_list      = get_active_categories();
$featured_products  = get_featured_products(8);
$sale_products      = get_sale_products(8);

// Include header (opens HTML, navbar)
require_once __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO BANNER ===== -->
<section class="hri-hero-banner">
    <div id="heroBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hri-hero-banner__content text-center py-5" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <div class="container text-white py-4">
                        <h1 class="display-5 fw-bold"><?= translate('site_name') ?></h1>
                        <p class="lead"><?= translate('site_tagline') ?></p>
                        <a href="<?= SITE_URL ?>/pages/products.php" class="btn btn-warning btn-lg mt-2">
                            <?= translate('all_products') ?> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CATEGORY BAR (Horizontal Scrollable) ===== -->
<section class="py-3">
    <div class="container">
        <h2 class="hri-section-title mb-3"><?= translate('categories') ?></h2>
        <div class="hri-category-bar d-flex overflow-auto pb-2">
            <?php foreach ($category_list as $category_data): ?>
                <a href="<?= SITE_URL ?>/pages/category.php?slug=<?= htmlspecialchars($category_data['category_slug']) ?>"
                   class="hri-category-bar__item text-center text-decoration-none mx-2 flex-shrink-0">
                    <div class="hri-category-bar__icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                         style="width:60px;height:60px;background:var(--color-primary-light);">
                        <i class="bi <?= htmlspecialchars($category_data['category_icon'] ?? 'bi-box') ?> fs-4" style="color:var(--color-primary-dark);"></i>
                    </div>
                    <span class="hri-category-bar__label small"><?= htmlspecialchars(get_category_name($category_data)) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== FEATURED PRODUCTS ===== -->
<?php if (!empty($featured_products)): ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="hri-section-title mb-0"><?= translate('featured') ?></h2>
            <a href="<?= SITE_URL ?>/pages/products.php" class="text-decoration-none"><?= translate('all_products') ?> →</a>
        </div>
        <div class="row g-3">
            <?php foreach ($featured_products as $product_data): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php include __DIR__ . '/includes/../pages/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== SALE PRODUCTS ===== -->
<?php if (!empty($sale_products)): ?>
<section class="py-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="hri-section-title mb-0">🔥 <?= translate('on_sale') ?></h2>
            <a href="<?= SITE_URL ?>/pages/products.php?filter=sale" class="text-decoration-none"><?= translate('all_products') ?> →</a>
        </div>
        <div class="row g-3">
            <?php foreach ($sale_products as $product_data): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php include __DIR__ . '/includes/../pages/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== DELIVERY INFO BANNER ===== -->
<section class="py-4">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-4">
                <i class="bi bi-truck fs-2 text-success"></i>
                <p class="small mt-1"><?= translate('delivery_info') ?><br><strong><?= DEFAULT_CITY ?></strong></p>
            </div>
            <div class="col-4">
                <i class="bi bi-whatsapp fs-2 text-success"></i>
                <p class="small mt-1"><?= translate('send_via_whatsapp') ?></p>
            </div>
            <div class="col-4">
                <i class="bi bi-cash-coin fs-2 text-success"></i>
                <p class="small mt-1"><?= translate('payment_on_delivery') ?></p>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer (closes HTML, loads scripts)
require_once __DIR__ . '/includes/footer.php';
?>

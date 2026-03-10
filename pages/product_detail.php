<?php
/**
 * FILE: pages/product_detail.php
 * PURPOSE: Rebuilt detailed product view with New Design and Sticky Bar.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$slug = isset($_GET['slug']) ? sanitize_input($_GET['slug']) : '';
$product = get_product_by_slug($slug);

if (!$product) {
    redirect(SITE_URL . '/pages/products.php');
}

$main_product_id = (int)$product['product_id'];
$main_product_name = get_product_name($product);
$main_product_desc = ($current_language === 'ar') ? $product['product_description_ar'] : $product['product_description_fr'];
$main_product_price = (float)$product['product_price'];
$main_product_sale_price = (float)$product['product_sale_price'];
$main_is_on_sale = (bool)$product['product_is_on_sale'] && $main_product_sale_price > 0 && $main_product_sale_price < $main_product_price;
$main_display_price = $main_is_on_sale ? $main_product_sale_price : $main_product_price;

// Related products
$related_products = get_products_by_category((int)$product['product_category_id'], 1, 6);

// Check if item is in session cart
$cart = $_SESSION[CART_SESSION_KEY] ?? [];
$main_in_cart = isset($cart[$main_product_id]);
$main_current_qty = $main_in_cart ? $cart[$main_product_id] : 0;

$page_title = $main_product_name . ' - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header Override for Details (Optional, keeping main header) -->
<div class="container py-3 d-flex align-items-center gap-3">
    <div onclick="window.history.back()" style="cursor:pointer" class="icon-trigger">
        <i data-lucide="arrow-left"></i>
    </div>
    <div class="fw-bold" style="font-size: 1.1rem;"><?php echo $lang['product_details'] ?? 'Détails'; ?></div>
</div>

<!-- Clickable Image Section -->
<div class="hri-product-img-container" onclick="openLightbox()">
    <img id="mainImg" src="<?php echo get_product_image_url($product['product_image']); ?>" alt="<?php echo htmlspecialchars($main_product_name); ?>">
</div>

<!-- Lightbox -->
<div id="lightbox" class="hri-lightbox" onclick="closeLightbox()">
    <div class="hri-lightbox__close"><i data-lucide="x" size="32"></i></div>
    <img id="lightboxImg" src="" alt="Zoom">
</div>

<!-- Product Info -->
<div class="container py-4">
    <div class="mb-2">
        <?php if ($main_is_on_sale): ?>
            <span class="badge bg-danger rounded-pill px-3">-<?php echo round((($main_product_price - $main_product_sale_price) / $main_product_price) * 100); ?>%</span>
        <?php endif; ?>
        <span class="badge bg-light text-dark border rounded-pill px-3 ms-1"><?php echo translate('unit_' . $product['product_unit']); ?></span>
    </div>

    <h1 style="font-size: 22px; margin: 0;"><?php echo htmlspecialchars($main_product_name); ?></h1>
    
    <div class="hri-product-card__price mt-3 mb-4" style="font-size: 28px;">
        <span class="current text-dark"><?php echo format_price($main_display_price); ?></span>
        <?php if ($main_is_on_sale): ?>
            <span class="original fs-6 ms-2"><?php echo format_price($main_product_price); ?></span>
        <?php endif; ?>
    </div>

    <div class="product-description mb-5">
        <h6 class="fw-bold text-uppercase small text-muted mb-3"><?php echo $current_language === 'ar' ? 'الوصف' : 'Description'; ?></h6>
        <div style="color: #555; line-height: 1.7; font-size: 15px;">
            <?php echo !empty($main_product_desc) ? nl2br(htmlspecialchars($main_product_desc)) : ( $current_language === 'ar' ? 'لا يوجد وصف متاح.' : 'Aucune description disponible.' ); ?>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5 pt-4 border-top">
        <h4 class="fw-bold mb-4"><?php echo $lang['related_products'] ?? 'Produits similaires'; ?></h4>
        <div class="row g-2 g-md-3 flex-nowrap overflow-auto pb-3 no-scrollbar">
            <?php foreach ($related_products as $product_data): ?>
                <?php if ($product_data['product_id'] == $main_product_id) continue; ?>
                <div class="col-6 col-md-4 col-lg-2 flex-shrink-0">
                    <?php include __DIR__ . '/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Sticky Action Bar -->
<div class="hri-detail-sticky-bar">
    <a href="tel:<?php echo clean_phone_number(STORE_PHONE_DISPLAY); ?>" class="hri-btn-call">
        <i data-lucide="phone"></i>
    </a>

    <div class="hri-detail-action-wrapper <?php echo $main_in_cart ? 'is-active' : ''; ?>" id="actionWrap">
        <button class="hri-btn-acheter" onclick="initAcheterDetail(<?php echo $main_product_id; ?>)">
            <?php echo $current_language === 'ar' ? 'شراء الآن' : 'Acheter'; ?>
        </button>
        <div class="hri-detail-qty-controls">
            <button class="hri-detail-qty-btn" onclick="changeQtyDetail(<?php echo $main_product_id; ?>, -1)">−</button>
            <span id="qty-display" class="hri-detail-qty-num"><?php echo $main_current_qty; ?></span>
            <button class="hri-detail-qty-btn" onclick="changeQtyDetail(<?php echo $main_product_id; ?>, 1)">+</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

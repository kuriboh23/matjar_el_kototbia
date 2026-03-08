<?php
/**
 * FILE: pages/product_detail.php
 * PURPOSE: Detailed product view based on docs/Prompt.md.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$slug = isset($_GET['slug']) ? sanitize_input($_GET['slug']) : '';
$product = get_product_by_slug($slug);

if (!$product) {
    redirect(SITE_URL . '/pages/products.php');
}

$product_id = (int)$product['product_id'];
$product_name = get_product_name($product);
$product_price = (float)$product['product_price'];
$product_sale_price = (float)$product['product_sale_price'];
$is_on_sale = (bool)$product['product_is_on_sale'] && $product_sale_price > 0 && $product_sale_price < $product_price;
$display_price = $is_on_sale ? $product_sale_price : $product_price;

// Related products
$related_products = get_products_by_category((int)$product['product_category_id'], 1, 6);

$page_title = $product_name . ' - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small" style="font-size: 0.8rem;">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php" class="text-primary text-decoration-none"><?php echo $lang['home']; ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/pages/products.php" class="text-primary text-decoration-none"><?php echo $lang['products']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product_name); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left Column: Image Gallery (40%) -->
        <div class="col-lg-5">
            <div class="bg-white border rounded-3 p-3 text-center mb-3">
                <img id="mainImage" src="<?php echo get_product_image_url($product['product_image']); ?>" class="img-fluid" style="max-height: 450px; object-fit: contain;" alt="">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2 mb-4">
                <img src="<?php echo get_product_image_url($product['product_image']); ?>" class="img-thumbnail" style="width: 80px; height: 80px; cursor: pointer;" onclick="document.getElementById('mainImage').src=this.src">
                <!-- Additional thumbnails if available -->
            </div>

            <div class="border-top pt-3">
                <h6 class="fw-bold small mb-3">PARTAGEZ CE PRODUIT</h6>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <!-- Right Column: Product Info (60%) -->
        <div class="col-lg-7">
            <div class="bg-white border rounded-3 p-4">
                <!-- Badges -->
                <div class="d-flex gap-2 mb-3">
                    <span class="hri-badge-official">Boutique Officielle</span>
                    <?php if ($is_on_sale): ?>
                        <span class="hri-badge-extra">-<?php echo round((($product_price - $product_sale_price) / $product_price) * 100); ?>% Additionnel</span>
                    <?php endif; ?>
                </div>

                <h1 class="h3 fw-bold mb-2"><?php echo htmlspecialchars($product_name); ?></h1>
                
                <?php if ($product['product_stock'] < 10): ?>
                    <p class="text-warning small fw-bold mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <?php echo $product['product_stock']; ?> articles seulement
                    </p>
                <?php endif; ?>

                <hr class="opacity-10 my-3">

                <!-- Price Block -->
                <div class="hri-detail-price mb-3">
                    <span class="hri-price-big"><?php echo format_price($display_price); ?></span>
                    <?php if ($is_on_sale): ?>
                        <span class="hri-price-was"><?php echo format_price($product_price); ?></span>
                        <span class="hri-badge-pct">-<?php echo round((($product_price - $product_sale_price) / $product_price) * 100); ?>%</span>
                    <?php endif; ?>
                </div>

                <p class="text-muted small mb-4">
                    + livraison à partir de <strong>10.00 Dhs</strong> (livraison gratuite si supérieur à <strong>200.00 Dhs</strong>) vers <strong>Safi</strong>
                </p>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase">Options Disponibles</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="unit" id="unit1" checked>
                        <label class="btn btn-outline-secondary py-2" for="unit1"><?php echo translate('unit_' . $product['product_unit']); ?></label>
                    </div>
                </div>

                <!-- Primary CTA -->
                <button class="hri-btn-buy btn w-100 mb-4" onclick="addCardToCart(<?php echo $product_id; ?>)">
                    <i class="bi bi-cart3 me-2"></i> J'achète
                </button>

                <!-- PROMOTIONS section -->
                <div class="bg-light rounded p-3">
                    <h6 class="fw-bold small text-uppercase mb-3">PROMOTIONS</h6>
                    <div class="d-flex align-items-center gap-3 mb-2 small">
                        <i class="bi bi-send-fill text-success"></i>
                        <span>Livraison gratuite sur commande ≥ 200 Dhs</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 small">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span>Contactez-nous via WhatsApp: <?php echo STORE_WHATSAPP_NUMBER; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Produits similaires</h4>
        <div class="row g-2 g-md-3">
            <?php foreach ($related_products as $product_data): ?>
                <?php if ($product_data['product_id'] == $product_id) continue; ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <?php include __DIR__ . '/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

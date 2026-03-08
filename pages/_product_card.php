<?php
/**
 * FILE: pages/_product_card.php
 * PURPOSE: Reusable product card component (partial).
 *          Expects $product_data to be set before including.
 *          Used on homepage, products page, category page, search results.
 * NOTE: Filename starts with _ to indicate partial (not a standalone page).
 */

// Calculate effective price
$effective_price = get_product_effective_price($product_data);
$is_on_sale = $product_data['product_is_on_sale'] && !empty($product_data['product_sale_price']);
?>
<div class="hri-product-card card h-100 border-0 shadow-sm">
    <!-- Product Image -->
    <a href="<?= SITE_URL ?>/pages/product_detail.php?slug=<?= htmlspecialchars($product_data['product_slug']) ?>">
        <div class="position-relative overflow-hidden">
            <img src="<?= get_product_image_url($product_data['product_image']) ?>"
                 alt="<?= htmlspecialchars(get_product_name($product_data)) ?>"
                 class="card-img-top hri-product-card__image"
                 loading="lazy"
                 style="aspect-ratio:1/1;object-fit:cover;">
            <?php if ($is_on_sale): ?>
                <span class="hri-product-card__badge hri-product-card__badge--sale position-absolute top-0 start-0 m-2 badge bg-danger">
                    <?= translate('on_sale') ?>
                </span>
            <?php endif; ?>
        </div>
    </a>

    <!-- Product Info -->
    <div class="card-body p-2 d-flex flex-column">
        <h6 class="hri-product-card__title card-title mb-1 small fw-semibold">
            <a href="<?= SITE_URL ?>/pages/product_detail.php?slug=<?= htmlspecialchars($product_data['product_slug']) ?>"
               class="text-decoration-none text-dark">
                <?= htmlspecialchars(get_product_name($product_data)) ?>
            </a>
        </h6>

        <!-- Price -->
        <div class="hri-product-card__price mb-2">
            <?php if ($is_on_sale): ?>
                <span class="text-danger fw-bold"><?= format_price($effective_price) ?></span>
                <small class="text-muted text-decoration-line-through ms-1"><?= format_price($product_data['product_price']) ?></small>
            <?php else: ?>
                <span class="fw-bold" style="color:var(--color-primary);"><?= format_price($effective_price) ?></span>
            <?php endif; ?>
            <small class="text-muted"> / <?= translate('unit_' . $product_data['product_unit']) ?></small>
        </div>

        <!-- Add to Cart Button -->
        <button class="btn btn-sm btn-success w-100 mt-auto hri-product-card__btn-cart"
                onclick="addToCart(<?= $product_data['product_id'] ?>, 1)"
                data-product-id="<?= $product_data['product_id'] ?>">
            <i class="bi bi-cart-plus"></i> <?= translate('add_to_cart') ?>
        </button>
    </div>
</div>

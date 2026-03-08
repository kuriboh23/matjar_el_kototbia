<?php
/**
 * FILE: pages/_product_card.php
 * PURPOSE: Product card with PRD naming and New Design aesthetic.
 */

$p = $product_data;
$product_id = (int)$p['product_id'];
$product_name_fr = $p['product_name_fr'];
$product_name_ar = $p['product_name_ar'];
$product_image = $p['product_image'];
$product_price = (float)$p['product_price'];
$product_sale_price = (float)$p['product_sale_price'];
$is_on_sale = (bool)$p['product_is_on_sale'] && $product_sale_price > 0 && $product_sale_price < $product_price;
$display_price = $is_on_sale ? $product_sale_price : $product_price;

// Check if item is in session cart
$cart = $_SESSION[CART_SESSION_KEY] ?? [];
$in_cart = isset($cart[$product_id]);
$current_qty = $in_cart ? $cart[$product_id] : 0;
?>

<div class="hri-product-card <?php echo $in_cart ? 'is-active' : ''; ?>" data-product-id="<?php echo $product_id; ?>">
    <!-- Badges -->
    <?php if ($is_on_sale): ?>
        <span class="hri-product-card__badge hri-product-card__badge--sale">-<?php echo round((($product_price - $product_sale_price) / $product_price) * 100); ?>%</span>
    <?php elseif ($p['product_is_featured']): ?>
        <span class="hri-product-card__badge hri-product-card__badge--featured"><?php echo $current_language === 'ar' ? 'مميز' : 'Vedette'; ?></span>
    <?php endif; ?>

    <!-- Image -->
    <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="hri-product-card__image text-decoration-none">
        <img src="<?php echo get_product_image_url($product_image); ?>" alt="<?php echo htmlspecialchars($product_name_fr); ?>" loading="lazy" onerror="this.src='https://placehold.co/200x200/f5f5f5/9e9e9e?text=No+Image'">
    </a>

    <!-- Card Content -->
    <div class="hri-product-card__content">
        <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="hri-product-card__title">
            <?php echo htmlspecialchars($current_language === 'ar' ? $product_name_ar : $product_name_fr); ?>
        </a>
        
        <div class="hri-product-card__price">
            <span class="current"><?php echo format_price($display_price); ?></span>
            <?php if ($is_on_sale): ?>
                <span class="original"><?php echo format_price($product_price); ?></span>
            <?php endif; ?>
        </div>

        <!-- Action Area -->
        <div class="hri-product-card__actions">
            <button class="hri-product-card__btn-cart" onclick="addCardToCart(<?php echo $product_id; ?>)">
                <i data-lucide="shopping-cart" size="16"></i>
                <span><?php echo $current_language === 'ar' ? 'أضف' : 'Ajouter'; ?></span>
            </button>
            <div class="hri-product-card__qty-box">
                <button class="qty-btn minus" onclick="updateCardQty(<?php echo $product_id; ?>, -1)">−</button>
                <span class="qty-num"><?php echo $current_qty; ?></span>
                <button class="qty-btn plus" onclick="updateCardQty(<?php echo $product_id; ?>, 1)">+</button>
            </div>
        </div>
    </div>
</div>

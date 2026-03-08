<?php
/**
 * FILE: pages/_product_card.php
 * PURPOSE: Reusable product card component based on docs/Prompt.md.
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

// Check if item is in session cart to show qty controls instead of add button
$cart = $_SESSION[CART_SESSION_KEY] ?? [];
$in_cart = isset($cart[$product_id]);
$current_qty = $in_cart ? $cart[$product_id] : 0;
?>

<div class="hri-product-card shadow-sm" data-product-id="<?php echo $product_id; ?>">
    <!-- Badge overlays -->
    <?php if ($p['product_is_featured']): ?>
        <span class="hri-badge-promo"><?php echo $current_language === 'ar' ? 'عرض خاص' : 'Offre Ramadan'; ?></span>
    <?php endif; ?>

    <?php if ($is_on_sale): ?>
        <span class="hri-badge-discount">-<?php echo round((($product_price - $product_sale_price) / $product_price) * 100); ?>%</span>
    <?php endif; ?>

    <!-- Product image -->
    <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="text-decoration-none">
        <img src="<?php echo get_product_image_url($product_image); ?>" alt="<?php echo htmlspecialchars($product_name_fr); ?>" loading="lazy" onerror="this.src='https://placehold.co/200x200/f5f5f5/9e9e9e?text=No+Image'">
    </a>

    <!-- Wishlist heart icon -->
    <button class="hri-wishlist-btn"><i class="bi bi-heart"></i></button>

    <!-- Card body -->
    <div class="hri-card-body">
        <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="hri-card-name">
            <?php echo htmlspecialchars($current_language === 'ar' ? $product_name_ar : $product_name_fr); ?>
        </a>
        
        <div class="hri-price-group">
            <span class="hri-price-current"><?php echo format_price($display_price); ?></span>
            <?php if ($is_on_sale): ?>
                <span class="hri-price-original"><?php echo format_price($product_price); ?></span>
            <?php endif; ?>
        </div>

        <!-- If item already in cart: show –/qty/+ controls -->
        <div class="hri-cart-control" style="<?php echo $in_cart ? 'display:flex;' : 'display:none;'; ?>">
            <button class="hri-qty-btn hri-qty-minus" onclick="updateCardQty(<?php echo $product_id; ?>, -1)">−</button>
            <span class="hri-qty-value"><?php echo $current_qty; ?></span>
            <button class="hri-qty-btn hri-qty-plus" onclick="updateCardQty(<?php echo $product_id; ?>, 1)">+</button>
        </div>
        
        <!-- Else: show "Ajouter au panier" orange button -->
        <button class="hri-btn-add-cart btn w-100" style="<?php echo $in_cart ? 'display:none;' : 'display:block;'; ?>" onclick="addCardToCart(<?php echo $product_id; ?>)">
            <?php echo $current_language === 'ar' ? 'أضف إلى السلة' : 'Ajouter au panier'; ?>
        </button>
    </div>
</div>

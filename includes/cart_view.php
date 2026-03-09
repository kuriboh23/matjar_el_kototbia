<?php
/**
 * FILE: includes/cart_view.php
 * PURPOSE: Reusable cart content for initial load and AJAX refresh.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $lang;

$cart_items = get_cart_items();
$cart_count = get_cart_item_count();
$subtotal = calculate_cart_subtotal($cart_items);

// Calculate Free Delivery Progress
$free_delivery_threshold = (float)FREE_DELIVERY_THRESHOLD;
$progress_pct = $free_delivery_threshold > 0 ? min(100, ($subtotal / $free_delivery_threshold) * 100) : 100;
$missing_for_free = max(0, $free_delivery_threshold - $subtotal);
?>

<header class="hri-header px-3 border-bottom d-flex align-items-center bg-white sticky-top">
    <div onclick="window.history.back()" style="cursor:pointer" class="icon-trigger">
        <i data-lucide="chevron-left"></i>
    </div>
    <div class="flex-grow-1 text-center fw-bold h5 mb-0">
        <?php echo $lang['your_cart']; ?> (<?php echo $cart_count; ?>)
    </div>
    <?php if ($cart_count > 0): ?>
        <div onclick="clearCartConfirm()" style="cursor:pointer" class="text-danger icon-trigger">
            <i data-lucide="trash-2"></i>
        </div>
    <?php else: ?>
        <div style="width: 40px;"></div>
    <?php endif; ?>
</header>

<?php if ($cart_count > 0): ?>
    <!-- Delivery Progress Card -->
    <div class="hri-cart-delivery-card shadow-sm border">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small fw-bold text-muted"><?php echo $lang['delivery_info'] ?? 'Livraison Safi'; ?></span>
            <span class="small fw-bold text-success"><?php echo round($progress_pct); ?>%</span>
        </div>
        <div class="hri-cart-progress-track">
            <div class="hri-cart-progress-fill" style="width: <?php echo $progress_pct; ?>%"></div>
        </div>
        <?php if ($missing_for_free > 0): ?>
            <div class="small text-muted">
                <?php 
                if ($current_language === 'ar') {
                    echo 'أضف <b>' . format_price($missing_for_free) . '</b> للحصول على توصيل مجاني!';
                } else {
                    echo 'Ajoutez <b>' . format_price($missing_for_free) . '</b> pour la livraison gratuite !';
                }
                ?>
            </div>
        <?php else: ?>
            <div class="small text-success fw-bold">
                <i data-lucide="check-circle" size="14"></i> <?php echo $lang['free_delivery'] ?? 'Livraison gratuite activée !'; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cart Items List -->
    <div class="pb-5">
        <?php foreach ($cart_items as $item): 
            $p = $item['data'];
            $p_name = get_product_name($p);
            $eff_price = get_product_effective_price($p);
        ?>
            <div class="hri-cart-item shadow-sm" data-product-id="<?php echo $item['id']; ?>">
                <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="hri-cart-item__thumb">
                    <img src="<?php echo get_product_image_url($p['product_image']); ?>" alt="<?php echo htmlspecialchars($p_name); ?>">
                </a>
                <div class="hri-cart-item__details">
                    <a href="<?php echo SITE_URL; ?>/pages/product_detail.php?slug=<?php echo $p['product_slug']; ?>" class="hri-cart-item__name">
                        <?php echo htmlspecialchars($p_name); ?>
                    </a>
                    <div class="hri-cart-item__price"><?php echo format_price($eff_price); ?></div>
                </div>
                <div class="hri-cart-qty-picker">
                    <button class="hri-cart-qty-btn" onclick="updateCartItemQty(<?php echo $item['id']; ?>, -1)">−</button>
                    <span class="fw-bold small px-1"><?php echo $item['qty']; ?></span>
                    <button class="hri-cart-qty-btn" onclick="updateCartItemQty(<?php echo $item['id']; ?>, 1)">+</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Sticky Footer -->
    <footer class="hri-cart-footer">
        <div>
            <div class="small text-muted"><?php echo $lang['total']; ?></div>
            <div class="h4 fw-bold mb-0"><?php echo format_price($subtotal); ?></div>
        </div>
        <?php if ($subtotal >= MINIMUM_ORDER_AMOUNT): ?>
            <a href="<?php echo SITE_URL; ?>/pages/checkout.php" class="hri-cart-btn-order" onclick="startProcessing('send')">
                <?php echo $lang['proceed_to_checkout']; ?>
            </a>
        <?php else: ?>
            <div class="text-end">
                <button class="hri-cart-btn-order opacity-50" disabled>
                    <?php echo $lang['proceed_to_checkout']; ?>
                </button>
                <div class="x-small text-danger mt-1" style="font-size: 10px;">
                    Min: <?php echo format_price(MINIMUM_ORDER_AMOUNT); ?>
                </div>
            </div>
        <?php endif; ?>
    </footer>

<?php else: ?>
    <div class="hri-cart-empty text-center mt-5">
        <i data-lucide="shopping-cart" size="80"></i>
        <h4 class="fw-bold"><?php echo $lang['cart_empty']; ?></h4>
        <p class="text-muted small"><?php echo $current_language === 'ar' ? 'سلة مشترياتك فارغة حاليا.' : 'Votre panier est vide pour le moment.'; ?></p>
        <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-primary px-4 rounded-pill mt-3">
            <?php echo $lang['continue_shopping']; ?>
        </a>
    </div>
<?php endif; ?>

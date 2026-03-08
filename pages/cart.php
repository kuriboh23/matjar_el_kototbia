<?php
/**
 * FILE: pages/cart.php
 * PURPOSE: Full cart view with Jumia-inspired design (docs/Prompt.md).
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$cart_items = get_cart_items();
$cart_count = get_cart_item_count();
$subtotal = calculate_cart_subtotal($cart_items);
$delivery_fee = calculate_delivery_fee($subtotal);
$total = calculate_cart_total($subtotal, $delivery_fee);

$page_title = $lang['cart'] . ' (' . $cart_count . ') - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <div class="row g-4">
        
        <!-- Left Column: Cart Items (70%) -->
        <div class="col-lg-8">
            <div class="bg-white border rounded shadow-sm">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h1 class="h6 fw-bold mb-0 text-uppercase">Panier (<?php echo $cart_count; ?>)</h1>
                </div>

                <?php if (empty($cart_items)): ?>
                    <div class="p-5 text-center">
                        <i class="bi bi-cart-x text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold"><?php echo $lang['cart_empty']; ?></h5>
                        <p class="text-muted small">Vous n'avez pas encore d'articles dans votre panier.</p>
                        <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn hri-btn-orange text-white fw-bold px-4 mt-3">CONTINUER MES ACHATS</a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($cart_items as $item): 
                            $p = $item['data'];
                            $p_name = get_product_name($p);
                            $p_price = (float)$p['product_price'];
                            $p_sale = (float)$p['product_sale_price'];
                            $is_sale = (bool)$p['product_is_on_sale'] && $p_sale > 0 && $p_sale < $p_price;
                            $eff_price = $is_sale ? $p_sale : $p_price;
                        ?>
                            <div class="list-group-item p-3">
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <img src="<?php echo get_product_image_url($p['product_image']); ?>" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: contain;" alt="">
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($p_name); ?></h6>
                                                <p class="text-muted small mb-1">Unité: <?php echo translate('unit_' . $p['product_unit']); ?></p>
                                                <?php if ($p['product_stock'] < 10): ?>
                                                    <span class="badge bg-light text-warning small p-1 mb-2 border border-warning">Stock limité</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold h5 mb-1"><?php echo format_price($eff_price); ?></div>
                                                <?php if ($is_sale): ?>
                                                    <div class="text-muted small text-decoration-line-through"><?php echo format_price($p_price); ?></div>
                                                    <span class="badge bg-light text-orange small border border-orange">-<?php echo round((($p_price - $p_sale)/$p_price)*100); ?>%</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <button class="btn btn-link text-orange p-0 small text-decoration-none fw-bold" onclick="updateCardQty(<?php echo $item['id']; ?>, -<?php echo $item['qty']; ?>)">
                                                <i class="bi bi-trash me-1"></i> SUPPRIMER
                                            </button>
                                            
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="hri-qty-btn" style="background: #e0e0e0; color: #333;" onclick="updateCardQty(<?php echo $item['id']; ?>, -1)">-</button>
                                                <span class="fw-bold px-2"><?php echo $item['qty']; ?></span>
                                                <button class="hri-qty-btn" onclick="updateCardQty(<?php echo $item['id']; ?>, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Summary Card (30%) -->
        <div class="col-lg-4">
            <div class="bg-white border rounded shadow-sm p-3 sticky-top" style="top: 100px; z-index: 10;">
                <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3" style="font-size: 0.85rem;">RÉSUMÉ DU PANIER</h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Total articles (<?php echo $cart_count; ?>)</span>
                    <span class="fw-bold"><?php echo format_price($subtotal); ?></span>
                </div>
                
                <?php if ($delivery_fee > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Livraison</span>
                        <span><?php echo format_price($delivery_fee); ?></span>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Livraison</span>
                        <span class="text-success fw-bold">Gratuite</span>
                    </div>
                <?php endif; ?>

                <hr class="my-3">

                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold">Sous-total</span>
                    <span class="h5 fw-bold text-dark"><?php echo format_price($total); ?></span>
                </div>

                <?php if ($subtotal < 200): ?>
                    <div class="alert alert-light border small py-2 mb-3">
                        <i class="bi bi-info-circle me-1"></i> Il manque <strong><?php echo format_price(200 - $subtotal); ?></strong> pour la livraison gratuite
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <?php if ($subtotal < MINIMUM_ORDER_AMOUNT): ?>
                        <div class="alert alert-warning small py-2 mb-0">
                            Minimum de commande: <?php echo format_price(MINIMUM_ORDER_AMOUNT); ?>
                        </div>
                        <button class="btn hri-btn-orange text-white fw-bold py-3 shadow-sm disabled" disabled>
                            COMMANDER (<?php echo format_price($total); ?>)
                        </button>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/pages/checkout.php" class="btn hri-btn-orange text-white fw-bold py-3 shadow-sm">
                            COMMANDER (<?php echo format_price($total); ?>)
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
/**
 * FILE: pages/cart.php
 * PURPOSE: Rebuilt Cart page with New Design and Micro-loader integration.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$cart_count = get_cart_item_count();
$page_title = $lang['cart'] . ' (' . $cart_count . ') - ' . $lang['site_name'];

require_once __DIR__ . '/../includes/header.php';
?>

<div id="hri-cart-page-content">
    <?php include __DIR__ . '/../includes/cart_view.php'; ?>
</div>

<div class="bottom-spacer" style="height: 120px;"></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

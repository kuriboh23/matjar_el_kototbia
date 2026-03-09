<?php
/**
 * FILE: pages/order_history.php
 * PURPOSE: List of customer past orders with New Design.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check
require_once __DIR__ . '/../includes/auth_check.php';

global $lang, $current_language;
$customer_id = get_current_customer_id();
$customer = get_customer_by_id($customer_id);
$all_orders = get_orders_by_customer($customer_id);

// Status Filter Logic
$current_filter = $_GET['status'] ?? 'all';
$filtered_orders = [];

foreach ($all_orders as $order) {
    if ($current_filter === 'all') {
        $filtered_orders[] = $order;
    } elseif ($current_filter === 'in_progress') {
        if (in_array($order['order_status'], ['pending', 'confirmed', 'preparing', 'out_for_delivery'])) {
            $filtered_orders[] = $order;
        }
    } elseif ($current_filter === 'delivered') {
        if ($order['order_status'] === 'delivered') {
            $filtered_orders[] = $order;
        }
    } elseif ($current_filter === 'cancelled') {
        if ($order['order_status'] === 'cancelled') {
            $filtered_orders[] = $order;
        }
    }
}

// Page title
$page_title = translate('order_history') . ' - ' . $lang['site_name'];

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div id="hri-orders-page">
    <header class="hri-profile-hero" style="border-radius: 0; padding-top: 20px; padding-bottom: 20px; margin-bottom: 0;">
        <h1 class="header-title" style="margin: 0; font-weight: 900; font-size: 22px;"><?= translate('order_history') ?></h1>
    </header>

    <div class="container py-4 mb-5 pb-5">
        <!-- Filter Tabs -->
        <div class="hri-tabs-container px-2">
            <a href="?status=all" class="hri-tab-item text-decoration-none <?= $current_filter === 'all' ? 'is-active' : '' ?>">
                <?= translate('order_all') ?>
            </a>
            <a href="?status=in_progress" class="hri-tab-item text-decoration-none <?= $current_filter === 'in_progress' ? 'is-active' : '' ?>">
                <?= translate('order_in_progress') ?>
            </a>
            <a href="?status=delivered" class="hri-tab-item text-decoration-none <?= $current_filter === 'delivered' ? 'is-active' : '' ?>">
                <?= translate('order_delivered') ?>
            </a>
            <a href="?status=cancelled" class="hri-tab-item text-decoration-none <?= $current_filter === 'cancelled' ? 'is-active' : '' ?>">
                <?= translate('order_cancelled') ?>
            </a>
        </div>

        <?php if (empty($filtered_orders)): ?>
            <div class="text-center py-5">
                <div class="mb-3 text-muted" style="opacity: 0.3;">
                    <i data-lucide="package-x" size="64"></i>
                </div>
                <p class="text-muted fw-bold"><?= translate('no_orders') ?></p>
                <a href="<?= SITE_URL ?>/index.php" class="btn hri-btn-orange text-white fw-bold px-4 rounded-4 mt-2" style="background-color: var(--princeton-orange);">
                    <?= translate('continue_shopping') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="px-2">
                <?php foreach ($filtered_orders as $order): 
                    $order_items = get_order_items($order['order_id']);
                    $first_item = $order_items[0] ?? null;
                    $other_count = count($order_items) - 1;
                    
                    // Status Badge Class
                    $status_class = 'hri-status-pending';
                    if ($order['order_status'] === 'delivered') $status_class = 'hri-status-delivered';
                    if ($order['order_status'] === 'cancelled') $status_class = 'hri-status-cancelled';
                    if ($order['order_status'] === 'out_for_delivery') $status_class = 'hri-status-shipped';
                ?>
                    <div class="hri-order-card">
                        <div class="hri-order-header">
                            <span class="hri-order-id"><?= translate('order_number') ?> #<?= $order['order_number'] ?></span>
                            <span class="hri-order-date"><?= date('d/m/Y', strtotime($order['order_created_at'])) ?></span>
                        </div>
                        <div class="hri-order-body">
                            <div class="hri-item-stack">
                                <div class="hri-item-thumb">
                                    <i data-lucide="package" size="20" color="#ccc"></i>
                                </div>
                                <?php if ($other_count > 0): ?>
                                    <span class="hri-stack-count">+<?= $other_count ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="hri-order-info">
                                <span class="hri-order-total"><?= format_price($order['order_total']) ?></span>
                                <span class="hri-order-items-summary">
                                    <?php if ($first_item): ?>
                                        <?= htmlspecialchars($current_language === 'ar' ? $first_item['order_item_name_ar'] : $first_item['order_item_name_fr']) ?>
                                        <?php if ($other_count > 0): ?>
                                            + <?= $other_count ?> <?= $current_language === 'ar' ? 'منتجات أخرى' : 'autres articles' ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <span class="hri-status-badge <?= $status_class ?>">
                                <?= translate('status_' . $order['order_status']) ?>
                            </span>
                        </div>
                        <a href="<?= SITE_URL ?>/pages/order_detail.php?number=<?= $order['order_number'] ?>" class="hri-order-footer">
                            <span class="hri-details-link">
                                <?= translate('view_details') ?> 
                                <i data-lucide="<?= $current_language === 'ar' ? 'chevron-left' : 'chevron-right' ?>" size="14"></i>
                            </span>
                            <div class="hri-brand-footer d-none d-md-flex">
                                MATJAR <span style="color:var(--princeton-orange)">⚡ <?= translate('matjar_express') ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

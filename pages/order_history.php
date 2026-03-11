<?php
/**
 * FILE: pages/order_history.php
 * PURPOSE: List of customer past orders with Premium Template Design.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check
require_once __DIR__ . '/../includes/auth_check.php';

global $lang, $current_language;
$customer_id = get_current_customer_id();
$all_orders = get_orders_by_customer($customer_id);

// Status Filter Logic
$current_filter = $_GET['status'] ?? 'all';
$filtered_orders = [];

foreach ($all_orders as $order) {
    if ($current_filter === 'all') {
        $filtered_orders[] = $order;
    } elseif ($current_filter === 'in_progress') {
        if (in_array($order['order_status'], [ORDER_STATUS_PENDING, ORDER_STATUS_CONFIRMED, ORDER_STATUS_PREPARING, ORDER_STATUS_OUT_FOR_DELIVERY])) {
            $filtered_orders[] = $order;
        }
    } elseif ($current_filter === 'delivered') {
        if ($order['order_status'] === ORDER_STATUS_DELIVERED) {
            $filtered_orders[] = $order;
        }
    } elseif ($current_filter === 'cancelled') {
        if ($order['order_status'] === ORDER_STATUS_CANCELLED) {
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
    <header class="hri-profile-hero" style="border-radius: 0; padding-top: 25px; padding-bottom: 25px; margin-bottom: 0;">
        <h1 class="header-title" style="margin: 0; font-weight: 900; font-size: 24px; letter-spacing: -0.5px;">
            <?= translate('order_history') ?>
        </h1>
    </header>

    <div class="container py-4 mb-5 pb-5">
        <!-- Filter Tabs (Scroller) -->
        <div class="hri-tabs-container px-2 mb-4">
            <a href="?status=all" class="hri-tab-item text-decoration-none <?= $current_filter === 'all' ? 'is-active' : '' ?>">
                <?= translate('order_all') ?? 'Tout' ?>
            </a>
            <a href="?status=in_progress" class="hri-tab-item text-decoration-none <?= $current_filter === 'in_progress' ? 'is-active' : '' ?>">
                <?= translate('order_in_progress') ?? 'En cours' ?>
            </a>
            <a href="?status=delivered" class="hri-tab-item text-decoration-none <?= $current_filter === 'delivered' ? 'is-active' : '' ?>">
                <?= translate('order_delivered') ?? 'Livrées' ?>
            </a>
            <a href="?status=cancelled" class="hri-tab-item text-decoration-none <?= $current_filter === 'cancelled' ? 'is-active' : '' ?>">
                <?= translate('order_cancelled') ?? 'Annulées' ?>
            </a>
        </div>

        <?php if (empty($filtered_orders)): ?>
            <div class="text-center py-5">
                <div class="mb-3 text-muted" style="opacity: 0.2;">
                    <i data-lucide="package-x" size="80"></i>
                </div>
                <h3 class="fw-bold h5"><?= translate('no_orders') ?></h3>
                <p class="text-muted small px-4"><?= $current_language === 'ar' ? 'لم تقم بإجراء أي طلبات بعد.' : "Vous n'avez pas encore passé de commande." ?></p>
                <a href="<?= SITE_URL ?>/index.php" class="btn hri-btn-orange text-white fw-bold px-4 rounded-pill mt-3">
                    <?= translate('continue_shopping') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="px-1">
                <?php foreach ($filtered_orders as $order): 
                    $order_items = get_order_items((int)$order['order_id']);
                    $item_count = count($order_items);
                    $first_item = $order_items[0] ?? null;
                    $other_count = $item_count - 1;
                    
                    // Status Badge Mapping
                    $status_class = 'hri-status-pending';
                    if ($order['order_status'] === ORDER_STATUS_DELIVERED) $status_class = 'hri-status-delivered';
                    if ($order['order_status'] === ORDER_STATUS_CANCELLED) $status_class = 'hri-status-cancelled';
                    if ($order['order_status'] === ORDER_STATUS_OUT_FOR_DELIVERY) $status_class = 'hri-status-shipped';
                ?>
                    <div class="hri-order-card">
                        <div class="hri-order-header">
                            <div class="d-flex flex-column">
                                <span class="hri-order-id">N° #<?= $order['order_number'] ?></span>
                                <span class="hri-order-date"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></span>
                            </div>
                            <span class="hri-status-badge <?= $status_class ?>">
                                <?= translate('status_' . $order['order_status']) ?>
                            </span>
                        </div>
                        
                        <div class="hri-order-body">
                            <div class="hri-item-stack">
                                <div class="hri-item-thumb">
                                    <i data-lucide="<?= $order['order_status'] === ORDER_STATUS_PENDING ? 'shopping-bag' : 'package' ?>" size="22" color="var(--princeton-orange)"></i>
                                </div>
                                <?php if ($other_count > 0): ?>
                                    <span class="hri-stack-count">+<?= $other_count ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="hri-order-info">
                                <span class="hri-order-total"><?= format_price((float)$order['order_total']) ?></span>
                                <span class="hri-order-items-summary text-truncate d-block" style="max-width: 180px;">
                                    <?php if ($first_item): ?>
                                        <?= htmlspecialchars($current_language === 'ar' ? $first_item['order_item_name_ar'] : $first_item['order_item_name_fr']) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <a href="<?= SITE_URL ?>/pages/order_detail.php?number=<?= $order['order_number'] ?>" class="hri-order-footer text-decoration-none">
                            <div class="brand-pill hri-brand-footer">
                                MATJAR <span style="color:var(--princeton-orange)">⚡ EXPRESS</span>
                            </div>
                            <div class="hri-details-link">
                                <?= translate('view_details') ?? 'Détails' ?> 
                                <i data-lucide="<?= $is_rtl ? 'arrow-left' : 'arrow-right' ?>" size="14"></i>
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

<?php
/**
 * FILE: pages/order_detail.php
 * PURPOSE: Single order details for customer view with Premium Template Design.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check
require_once __DIR__ . '/../includes/auth_check.php';

global $lang, $current_language, $is_rtl;
$customer_id = get_current_customer_id();
$order_number = $_GET['number'] ?? '';

if (empty($order_number)) {
    redirect(SITE_URL . '/pages/order_history.php');
}

$order = get_order_by_number($order_number);

// Ensure order exists and belongs to current customer
if (!$order || (int)$order['order_customer_id'] !== $customer_id) {
    redirect(SITE_URL . '/pages/order_history.php');
}

$order_items = get_order_items((int)$order['order_id']);

// Page title
$page_title = translate('order_number') . ' #' . $order['order_number'] . ' - ' . $lang['site_name'];

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<header class="hri-detail-header">
    <a href="<?= SITE_URL ?>/pages/order_history.php" class="hri-back-btn">
        <i data-lucide="<?= $is_rtl ? 'chevron-right' : 'chevron-left' ?>"></i>
    </a>
    <div class="text-center">
        <span class="d-block text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;"><?= translate('order_details') ?? 'Détails de la commande' ?></span>
        <h1 class="h6 fw-bold mb-0">#<?= $order['order_number'] ?></h1>
    </div>
    <div style="width: 40px;"></div>
</header>

<div class="container py-4 mb-5 pb-5" style="max-width: 800px;">
    
    <!-- Status Hero Section -->
    <div class="hri-status-hero">
        <div class="hri-status-icon">
            <?php 
                $icon = 'package';
                if ($order['order_status'] === ORDER_STATUS_DELIVERED) $icon = 'package-check';
                if ($order['order_status'] === ORDER_STATUS_CANCELLED) $icon = 'package-x';
                if ($order['order_status'] === ORDER_STATUS_OUT_FOR_DELIVERY) $icon = 'truck';
            ?>
            <i data-lucide="<?= $icon ?>" size="32"></i>
        </div>
        <div class="hri-status-label"><?= translate('status_' . $order['order_status']) ?></div>
        <div class="hri-status-time">
            <?= $current_language === 'ar' ? 'تم تقديم الطلب في ' : 'Commandé le ' ?> 
            <?= date('d/m/Y à H:i', strtotime($order['order_created_at'])) ?>
        </div>

        <div class="mt-4 d-flex gap-2 justify-content-center">
            <a href="<?= SITE_URL ?>/pages/order_success.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                <i data-lucide="file-text" size="14" class="me-1"></i>
                <?php if ($order['order_status'] !== ORDER_STATUS_PENDING): ?>
                     <?= translate('show_receipt') ?>
            <?php endif; ?>
            </a>
            
            <?php if ($order['order_status'] === ORDER_STATUS_PENDING): ?>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" 
                        onclick="confirmDeleteOrder(<?= $order['order_id'] ?>)">
                    <i data-lucide="trash-2" size="14" class="me-1"></i> <?= translate('delete_order') ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function confirmDeleteOrder(orderId) {
        if (confirm("<?= translate('confirm_delete_order') ?>")) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= SITE_URL ?>/pages/order_delete.php';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'order_id';
            idInput.value = orderId;
            form.appendChild(idInput);
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= CSRF_TOKEN_NAME ?>';
            csrfInput.value = '<?= generate_csrf_token() ?>';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>

    <!-- Items List Card -->
    <div class="hri-summary-card mb-4">
        <div class="hri-summary-header">
            <?= translate('ordered_products') ?? 'Articles Commandés' ?>
        </div>
        <div class="summary-body">
            <?php foreach ($order_items as $item): ?>
                <div class="hri-product-item">
                    <div class="hri-product-img-qty">
                        <?= (float)$item['order_item_quantity'] ?>x
                    </div>
                    <div class="hri-product-info">
                        <span class="hri-product-name">
                            <?= htmlspecialchars($current_language === 'ar' ? $item['order_item_name_ar'] : $item['order_item_name_fr']) ?>
                        </span>
                        <span class="hri-product-meta">
                            <?= translate('unit') ?? 'Unité' ?>: <?= $item['order_item_unit'] ?>
                        </span>
                    </div>
                    <div class="hri-product-price">
                        <?= format_price((float)$item['order_item_subtotal']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Delivery Info Card -->
    <div class="hri-summary-card mb-4">
        <div class="hri-summary-header">
            <?= translate('delivery_information') ?? 'Informations de Livraison' ?>
        </div>
        <div class="hri-info-content">
            <div class="hri-info-row">
                <div class="hri-info-icon"><i data-lucide="user" size="18"></i></div>
                <div class="hri-info-text">
                    <b><?= translate('full_name') ?></b>
                    <p><?= htmlspecialchars($order['order_customer_name']) ?></p>
                </div>
            </div>
            <div class="hri-info-row">
                <div class="hri-info-icon"><i data-lucide="phone" size="18"></i></div>
                <div class="hri-info-text">
                    <b><?= translate('phone_number') ?></b>
                    <p><?= htmlspecialchars($order['order_customer_phone']) ?></p>
                </div>
            </div>
            <div class="hri-info-row">
                <div class="hri-info-icon"><i data-lucide="map-pin" size="18"></i></div>
                <div class="hri-info-text">
                    <b><?= translate('delivery_address') ?></b>
                    <p>
                        <?= htmlspecialchars($order['order_customer_address']) ?><br>
                        <?= htmlspecialchars($order['order_customer_neighborhood'] ?? '') ?>, <?= htmlspecialchars($order['order_customer_city']) ?>
                    </p>
                </div>
            </div>
            <?php if (!empty($order['order_notes'])): ?>
                <div class="hri-info-row">
                    <div class="hri-info-icon"><i data-lucide="message-square" size="18"></i></div>
                    <div class="hri-info-text">
                        <b><?= translate('order_notes') ?></b>
                        <p class="text-muted" style="font-style: italic;"><?= nl2br(htmlspecialchars($order['order_notes'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Summary Card -->
    <div class="hri-summary-card mb-4">
        <div class="hri-summary-header">
            <?= translate('payment_summary') ?? 'Résumé du Paiement' ?>
        </div>
        <div class="hri-summary-box">
            <div class="hri-summary-line">
                <span class="text-muted"><?= translate('subtotal') ?></span>
                <span><?= format_price((float)$order['order_subtotal']) ?></span>
            </div>
            <div class="hri-summary-line">
                <span class="text-muted"><?= translate('delivery_fee') ?></span>
                <span style="color: <?= (float)$order['order_delivery_fee'] > 0 ? '#1d1d1d' : ('#10b981') ?>;">
                    <?= (float)$order['order_delivery_fee'] > 0 ? format_price((float)$order['order_delivery_fee']) : (translate('free_delivery') ?? 'Gratuit') ?>
                </span>
            </div>
            <div class="hri-summary-total">
                <span class="fw-bold fs-6">TOTAL</span>
                <span class="hri-total-amount">
                    <?= format_price((float)$order['order_total']) ?>
                </span>
            </div>
        </div>
        <div class="hri-express-badge-dark">
            MATJAR <span style="color:var(--princeton-orange)">⚡ EXPRESS</span>
        </div>
    </div>

    <!-- WhatsApp Support Link -->
    <a href="https://wa.me/<?= STORE_WHATSAPP_NUMBER ?>?text=<?= urlencode('Bonjour, j\'ai une question concernant ma commande #' . $order['order_number']) ?>" target="_blank" class="text-decoration-none">
        <div class="awesome-card shadow-sm p-3 mb-5 d-flex align-items-center justify-content-center gap-3 text-white" style="background: #25D366; border-radius: 20px; border: none;">
            <i data-lucide="message-circle" size="24"></i>
            <span class="fw-bold" style="font-size: 14px;"><?= translate('contact_support') ?></span>
        </div>
    </a>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>


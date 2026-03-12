<?php
/**
 * FILE: pages/order_success.php
 * PURPOSE: Show order success page and receipt preview. Customer waits for manager confirmation.
 */

require_once __DIR__ . '/../config/config.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = get_order_by_id($order_id);

if (!$order) {
    redirect(SITE_URL);
}

$order_items = get_order_items($order_id);
$settings = get_all_settings();

$page_title = translate('order_success');
require_once __DIR__ . '/../includes/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    .success-container {
        max-width: 600px; margin: 0 auto; padding: 40px 16px 120px; text-align: center;
    }
    .success-icon-wrapper {
        width: 80px; height: 80px; background: #f0fdf4; color: #166534;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    
    /* Receipt Styles */
    #receipt-to-capture {
        background: white; border: 1px solid var(--border-color); border-radius: 20px;
        padding: 30px; text-align: <?= $is_rtl ? 'right' : 'left' ?>; margin-top: 30px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        font-family: <?= $is_rtl ? "'Cairo', sans-serif" : "'Plus Jakarta Sans', sans-serif" ?>;
        direction: <?= $is_rtl ? 'rtl' : 'ltr' ?>;
    }
    .receipt-header {
        border-bottom: 2px solid var(--carbon-black); padding-bottom: 15px; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: flex-start;
        flex-direction: <?= $is_rtl ? 'row-reverse' : 'row' ?>;
    }
    .receipt-logo { font-weight: 900; font-size: 18px; }
    .receipt-logo span { color: var(--brand-color); }
    .receipt-title { font-weight: 800; font-size: 14px; text-align: <?= $is_rtl ? 'left' : 'right' ?>; }
    
    .receipt-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .receipt-info-label { font-size: 9px; text-transform: uppercase; font-weight: 800; color: var(--brand-color); margin-bottom: 4px; }
    .receipt-info-value { font-size: 12px; font-weight: 700; line-height: 1.4; }
    
    .receipt-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .receipt-table th { text-align: <?= $is_rtl ? 'right' : 'left' ?>; font-size: 10px; text-transform: uppercase; padding: 8px 0; border-bottom: 1px solid #eee; }
    .receipt-table td { padding: 10px 0; font-size: 12px; font-weight: 600; border-bottom: 1px solid #f9f9f9; }
    .receipt-qty { color: var(--brand-color); font-weight: 800; padding-inline-end: 10px; }
    
    .receipt-totals { margin-inline-start: auto; width: 200px; }
    .receipt-total-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px; }
    .receipt-grand-total { border-top: 2px solid var(--carbon-black); margin-top: 5px; padding-top: 10px; font-weight: 900; font-size: 16px; }
    
    .receipt-footer { margin-top: 20px; text-align: center; font-size: 10px; color: #717171; font-weight: 600; border-top: 1px dashed #eee; padding-top: 15px; }

    .loading-overlay {
        position: fixed; inset: 0; background: rgba(255,255,255,0.9);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        z-index: 2000; font-weight: 800; gap: 15px;
    }
    .btn-home {
        display: inline-flex; align-items: center; gap: 8px; background: var(--carbon-black);
        color: white; padding: 12px 30px; border-radius: 12px; font-weight: 700;
        text-decoration: none; margin-top: 20px; transition: all 0.2s;
    }
    .btn-home:hover { transform: translateY(-2px); opacity: 0.9; }
</style>

<div id="loading-receipt" class="loading-overlay">
    <div class="spinner-border text-primary" role="status"></div>
    <span><?= translate('generating_receipt') ?></span>
</div>

<div class="success-container">
    <div class="success-icon-wrapper">
        <i data-lucide="check-circle-2" size="40"></i>
    </div>
    <h1 style="font-weight: 900; font-size: 24px;"><?= translate('order_received') ?></h1>
    <p style="color: #717171; font-weight: 600; margin-top: 10px; line-height: 1.6;">
        <?= translate('order_wait_manager_msg') ?>
    </p>

    <!-- Hidden container for capture -->
    <div style="overflow: hidden; height: 0; width: 0; position: absolute;">
        <div id="receipt-to-capture" style="width: 500px;">
            <div class="receipt-header">
                <div class="receipt-logo">MATJAR<span>.</span>KOTOBIA</div>
                <div class="receipt-title">
                    <div><?= translate('receipt_title') ?></div>
                    <div style="font-size: 11px; opacity: 0.6;">#<?= $order['order_number'] ?></div>
                    <div style="font-size: 11px; opacity: 0.6;"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></div>
                </div>
            </div>

            <div class="receipt-info-grid">
                <div>
                    <div class="receipt-info-label"><?= translate('client') ?></div>
                    <div class="receipt-info-value"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                    <div class="receipt-info-value"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
                </div>
                <div>
                    <div class="receipt-info-label"><?= translate('delivery') ?></div>
                    <div class="receipt-info-value"><?= htmlspecialchars($order['order_customer_address']) ?></div>
                    <div class="receipt-info-value" style="font-size: 10px; color: #717171;"><?= htmlspecialchars($order['order_customer_city']) ?></div>
                </div>
            </div>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th><?= translate('quantity') ?></th>
                        <th><?= translate('designation') ?></th>
                        <th style="text-align: <?= $is_rtl ? 'left' : 'right' ?>;"><?= translate('total') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td class="receipt-qty"><?= (float)$item['order_item_quantity'] ?>x</td>
                            <td>
                                <?= htmlspecialchars($current_language == 'ar' ? $item['order_item_name_ar'] : $item['order_item_name_fr']) ?>
                            </td>
                            <td style="text-align: <?= $is_rtl ? 'left' : 'right' ?>;"><?= format_price($item['order_item_subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="receipt-totals">
                <div class="receipt-total-row">
                    <span><?= translate('subtotal') ?></span>
                    <span><?= format_price($order['order_subtotal']) ?></span>
                </div>
                <div class="receipt-total-row">
                    <span><?= translate('delivery_fee') ?></span>
                    <span><?= format_price($order['order_delivery_fee']) ?></span>
                </div>
                <div class="receipt-total-row receipt-grand-total">
                    <span><?= translate('total') ?></span>
                    <span style="color: var(--princeton-orange);"><?= format_price($order['order_total']) ?></span>
                </div>
            </div>

            <div class="receipt-footer">
                <p><?= translate('thanks_confidence') ?></p>
                <p>Matjar El Kotobia • Safi, Maroc</p>
            </div>
        </div>
    </div>

    <!-- Visual Preview -->
    <div id="receipt-preview" style="margin-top: 30px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #eee; background: white;">
        <!-- Canvas/Image will be injected here -->
    </div>

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>" class="btn-home">
            <i data-lucide="home" size="18"></i>
            <?= translate('home') ?>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const container = document.getElementById('receipt-to-capture');
        const preview = document.getElementById('receipt-preview');
        const loading = document.getElementById('loading-receipt');

        try {
            // Wait for fonts/images to load
            await new Promise(resolve => setTimeout(resolve, 800));

            const canvas = await html2canvas(container, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false,
                width: 500
            });

            const img = new Image();
            img.src = canvas.toDataURL('image/png');
            img.style.width = '100%';
            preview.appendChild(img);

            loading.style.display = 'none';

        } catch (error) {
            console.error('Error generating receipt image:', error);
            loading.innerHTML = '<span><?= translate("generating_receipt_error") ?></span>';
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


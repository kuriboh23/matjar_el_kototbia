<?php
/**
 * FILE: pages/checkout.php
 * PURPOSE: Checkout form with New Design, dynamic data from DB, and WhatsApp redirection.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

// 1. Fetch Dynamic Settings from DB
$settings = get_all_settings();
$min_order           = (float)($settings['minimum_order_amount'] ?? MINIMUM_ORDER_AMOUNT);
$delivery_fee_val    = (float)($settings['delivery_fee'] ?? DELIVERY_FEE);
$free_delivery_limit = (float)($settings['free_delivery_threshold'] ?? FREE_DELIVERY_THRESHOLD);
$store_whatsapp      = $settings['store_whatsapp'] ?? STORE_WHATSAPP_NUMBER;
$store_city          = $settings['store_city'] ?? DEFAULT_CITY;

// 2. Cart Data
$cart_items = get_cart_items();
if (empty($cart_items)) {
    redirect(SITE_URL . '/pages/cart.php');
}

$subtotal = calculate_cart_subtotal($cart_items);

// Redirect if minimum amount not met
if ($subtotal < $min_order) {
    set_flash_message('error', translate('minimum_order_msg') . ' ' . format_price($min_order));
    redirect(SITE_URL . '/pages/cart.php');
}

// Dynamic Delivery Calculation
$delivery_fee = ($subtotal >= $free_delivery_limit) ? 0 : $delivery_fee_val;
$total = $subtotal + $delivery_fee;

// 3. User Data (Pre-fill if logged in)
$customer_id = get_current_customer_id();
$customer_data = null;
if ($customer_id) {
    $customer_data = get_customer_by_id($customer_id);
}

$errors = [];

// 4. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    // CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    // Data Sanitization
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $phone     = sanitize_input($_POST['phone'] ?? '');
    $address   = sanitize_input($_POST['address'] ?? '');
    $notes     = sanitize_input($_POST['notes'] ?? '');

    // Validation
    if (empty($full_name)) $errors[] = translate('error_required_field') . ' (' . translate('full_name') . ')';
    if (!validate_phone_morocco($phone)) $errors[] = translate('error_invalid_phone');
    if (empty($address)) $errors[] = translate('error_required_field') . ' (' . translate('delivery_address') . ')';

    if (empty($errors)) {
        // Prepare Order Header
        $order_header = [
            'order_number'                => generate_order_number(),
            'order_customer_id'           => $customer_id,
            'order_customer_name'         => $full_name,
            'order_customer_phone'        => clean_phone_number($phone),
            'order_customer_address'      => $address,
            'order_customer_neighborhood' => null, // Simplified for this UI
            'order_customer_city'         => $store_city,
            'order_notes'                 => $notes,
            'order_subtotal'              => $subtotal,
            'order_delivery_fee'          => $delivery_fee,
            'order_total'                 => $total,
            'order_item_count'            => get_cart_item_count(),
            'order_language'              => $current_language
        ];

        // Prepare Order Items
        $line_items = [];
        foreach ($cart_items as $item) {
            $p = $item['data'];
            $line_items[] = [
                'product_id'      => $p['product_id'],
                'product_name_fr' => $p['product_name_fr'],
                'product_name_ar' => $p['product_name_ar'],
                'unit_price'      => get_product_effective_price($p),
                'quantity'        => $item['qty'],
                'unit'            => $p['product_unit'],
                'subtotal'        => get_product_effective_price($p) * $item['qty']
            ];
        }

        // Save to Database
        $saved_order_id = create_order($order_header, $line_items);

        if ($saved_order_id > 0) {
            clear_cart();
            // Redirect to success page with image generation
            redirect(SITE_URL . '/pages/order_success.php?id=' . $saved_order_id);
        } else {
            $errors[] = translate('error_general');
        }
    }
}

$page_title = translate('checkout_validation') . ' - ' . ($current_language === 'ar' ? $settings['store_name_ar'] : $settings['store_name_fr']);
require_once __DIR__ . '/../includes/header.php';
?>

<style>
    /* Premium Checkout Styles */
    .hri-checkout-container {
        max-width: 1100px; margin: 0 auto; padding: 20px 16px 140px;
        display: grid; grid-template-columns: 1fr; gap: 20px;
    }
    @media (min-width: 992px) {
        .hri-checkout-container { grid-template-columns: 1fr 380px; padding-top: 40px; }
    }
    .awesome-card {
        background: white; border-radius: 24px; border: 1px solid var(--border-color);
        overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 20px;
    }
    .card-header-premium {
        background: #fafafa; padding: 15px 20px; display: flex; align-items: center;
        gap: 10px; border-bottom: 1px solid var(--border-color);
    }
    .step-pill {
        background: var(--carbon-black); color: white; width: 22px; height: 22px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800;
    }
    .card-title-premium { font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-group-premium { padding: 16px 20px; border-bottom: 1px solid #f9f9f9; }
    .input-group-premium:last-child { border-bottom: none; }
    .label-text-premium { display: block; font-size: 10px; font-weight: 800; color: #717171; text-transform: uppercase; margin-bottom: 4px; }
    .clean-input {
        width: 100%; border: none; padding: 4px 0; font-size: 16px; font-weight: 600;
        font-family: inherit; color: var(--carbon-black); outline: none; background: transparent;
    }
    .summary-row { display: flex; justify-content: space-between; padding: 12px 20px; font-size: 14px; }
    .item-qty-tag { color: var(--princeton-orange); font-weight: 800; margin-inline-end: 8px; }
    .total-box-premium { background: var(--carbon-black); color: white; padding: 20px; margin-top: 10px; }
    .express-badge-checkout {
        display: flex; align-items: center; gap: 6px; font-weight: 900; font-style: italic;
        font-size: 12px; padding: 15px 20px; background: #fff9f2; border-top: 1px solid #ffe8d1;
    }
    .bottom-action-bar-checkout {
        position: fixed; bottom: 0; left: 0; width: 100%; background: white;
        padding: 16px 20px; box-shadow: 0 -10px 30px rgba(0,0,0,0.08); z-index: 1000;
        box-sizing: border-box; display: flex; justify-content: center;
    }
    .btn-confirm-checkout {
        background: var(--princeton-orange); color: white; border: none; width: 100%;
        max-width: 500px; padding: 18px; border-radius: 20px; font-weight: 800;
        font-size: 16px; cursor: pointer; display: flex; align-items: center;
        justify-content: center; gap: 10px; box-shadow: 0 8px 25px rgba(255,130,0,0.3);
    }
    .login-suggestion-alert {
        background: var(--alice-blue); border-radius: 16px; padding: 15px 20px;
        margin-bottom: 20px; border: 1px solid #d0e8ff; display: flex; align-items: center; gap: 12px;
    }
    [dir="rtl"] .summary-row, [dir="rtl"] .card-header-premium { text-align: right; flex-direction: row-reverse; }
    [dir="rtl"] .input-group-premium { text-align: right; }
</style>

<div class="hri-checkout-container">
    <main>
        <?php if (!$customer_id): ?>
            <div class="login-suggestion-alert">
                <i data-lucide="user-circle" style="color: var(--princeton-orange);"></i>
                <div class="small fw-bold">
                    <?= translate('login_suggestion') ?> 
                    <a href="<?= SITE_URL ?>/pages/login.php?redirect=checkout" class="text-primary"><?= translate('login_now') ?></a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4">
                <ul class="mb-0 small fw-bold">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="checkoutFormPremium">
            <?= csrf_input_field() ?>
            <input type="hidden" name="submit_order" value="1">

            <div class="awesome-card">
                <div class="card-header-premium">
                    <div class="step-pill">1</div>
                    <span class="card-title-premium"><?= translate('delivery_info') ?></span>
                </div>
                <div class="input-group-premium">
                    <span class="label-text-premium"><?= translate('full_name') ?></span>
                    <input type="text" name="full_name" class="clean-input" placeholder="<?= translate('full_name') ?>" required 
                           value="<?= htmlspecialchars($_POST['full_name'] ?? ($customer_data['customer_full_name'] ?? '')) ?>">
                </div>
                <div class="input-group-premium">
                    <span class="label-text-premium"><?= translate('whatsapp_phone') ?></span>
                    <input type="tel" name="phone" class="clean-input" placeholder="06XXXXXXXX" required 
                           value="<?= htmlspecialchars($_POST['phone'] ?? ($customer_data['customer_phone'] ?? '')) ?>">
                </div>
                <div class="input-group-premium">
                    <span class="label-text-premium"><?= translate('exact_address') ?></span>
                    <input type="text" name="address" class="clean-input" placeholder="<?= translate('exact_address_placeholder') ?>" required 
                           value="<?= htmlspecialchars($_POST['address'] ?? ($customer_data['customer_address'] ?? '')) ?>">
                </div>
                <div class="input-group-premium" style="background: #fcfcfc;">
                    <span class="label-text-premium"><?= translate('city') ?></span>
                    <input type="text" class="clean-input" value="<?= $store_city ?>" readonly style="color: #717171;">
                </div>
            </div>

            <div class="awesome-card">
                <div class="card-header-premium">
                    <div class="step-pill">2</div>
                    <span class="card-title-premium"><?= translate('additional_notes') ?></span>
                </div>
                <div class="input-group-premium">
                    <textarea name="notes" class="clean-input" rows="2" placeholder="<?= translate('additional_notes_placeholder') ?>" style="resize: none;"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </form>
    </main>

    <aside>
        <div class="awesome-card">
            <div class="card-header-premium">
                <span class="card-title-premium"><?= translate('order_summary') ?></span>
            </div>
            <div style="padding: 10px 0; max-height: 250px; overflow-y: auto;">
                <?php foreach ($cart_items as $item): ?>
                    <div class="summary-row">
                        <span class="text-truncate me-2" style="max-width: 70%;">
                            <span class="item-qty-tag"><?= $item['qty'] ?>x</span> 
                            <?= get_product_name($item['data']) ?>
                        </span>
                        <span class="fw-bold"><?= format_price(get_product_effective_price($item['data']) * $item['qty']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="border-top: 1px solid #f9f9f9; padding-top: 10px;">
                <div class="summary-row" style="color: #717171; font-size: 13px;">
                    <span><?= translate('subtotal') ?></span>
                    <span><?= format_price($subtotal) ?></span>
                </div>
                <div class="summary-row" style="<?= $delivery_fee == 0 ? 'color: #00a650; font-weight: 700;' : 'color: #717171; font-size: 13px;' ?>">
                    <span><?= translate('delivery_fee') ?></span>
                    <span><?= $delivery_fee > 0 ? format_price($delivery_fee) : translate('free_delivery') ?></span>
                </div>
            </div>

            <div class="total-box-premium">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 800; font-size: 16px;"><?= translate('total') ?></span>
                    <span style="font-weight: 900; font-size: 22px; color: var(--princeton-orange);"><?= format_price($total) ?></span>
                </div>
            </div>

            <div class="express-badge-checkout">
                MATJAR <span style="color:var(--princeton-orange)">⚡ <?= translate('matjar_express') ?></span>
                <span style="<?= $is_rtl ? 'margin-right: auto;' : 'margin-left: auto;' ?> font-weight: 400; font-style: normal; color: #717171; font-size: 10px;">
                    <?= translate('payment_cash') ?>
                </span>
            </div>
        </div>
        
        <p style="text-align: center; font-size: 11px; color: #717171; padding: 0 20px;">
            <i data-lucide="shield-check" size="12" style="vertical-align: middle;"></i>
            <?= translate('whatsapp_redirect_msg') ?>
        </p>
    </aside>
</div>

<div class="bottom-action-bar-checkout">
    <button type="submit" form="checkoutFormPremium" name="submit_order" class="btn-confirm-checkout">
        <i data-lucide="message-circle" size="20"></i>
        <?= translate('confirm_order') ?>
    </button>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
/**
 * FILE: pages/checkout.php
 * PURPOSE: Checkout form, order processing, and WhatsApp redirection.
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

// Redirect if cart is empty
$cart_items = get_cart_items();
if (empty($cart_items)) {
    redirect(SITE_URL . '/pages/cart.php');
}

$subtotal = calculate_cart_subtotal($cart_items);
$delivery_fee = calculate_delivery_fee($subtotal);
$total = calculate_cart_total($subtotal, $delivery_fee);

// Redirect if minimum amount not met
if ($subtotal < MINIMUM_ORDER_AMOUNT) {
    redirect(SITE_URL . '/pages/cart.php');
}

$errors = [];
$success = false;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    // 1. CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    // 2. Data Sanitization
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $phone     = sanitize_input($_POST['phone'] ?? '');
    $address   = sanitize_input($_POST['address'] ?? '');
    $quartier  = sanitize_input($_POST['neighborhood'] ?? '');
    $notes     = sanitize_input($_POST['notes'] ?? '');

    // 3. Validation
    if (empty($full_name)) $errors[] = $lang['error_required_field'] . ' (' . $lang['full_name'] . ')';
    if (!validate_phone_morocco($phone)) $errors[] = $lang['error_invalid_phone'];
    if (empty($address)) $errors[] = $lang['error_required_field'] . ' (' . $lang['delivery_address'] . ')';

    if (empty($errors)) {
        // 4. Prepare Order Header
        $order_header = [
            'order_number'                => generate_order_number(),
            'order_customer_id'           => get_current_customer_id(),
            'order_customer_name'         => $full_name,
            'order_customer_phone'        => clean_phone_number($phone),
            'order_customer_address'      => $address,
            'order_customer_neighborhood' => $quartier,
            'order_customer_city'         => DEFAULT_CITY,
            'order_notes'                 => $notes,
            'order_subtotal'              => $subtotal,
            'order_delivery_fee'          => $delivery_fee,
            'order_total'                 => $total,
            'order_item_count'            => get_cart_item_count(),
            'order_language'              => $current_language
        ];

        // 5. Prepare Order Items
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

        // 6. Save to Database
        $order_id = create_order($order_header, $line_items);

        if ($order_id > 0) {
            // 7. Clear Cart
            clear_cart();

            // 8. Build WhatsApp Message & URL
            $whatsapp_msg = build_whatsapp_message($order_header, $line_items);
            $whatsapp_url = build_whatsapp_url(STORE_WHATSAPP_NUMBER, $whatsapp_msg);

            // 9. Redirect to WhatsApp
            redirect($whatsapp_url);
        } else {
            $errors[] = $lang['error_general'];
        }
    }
}

$page_title = $lang['checkout'] . ' - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <div class="row g-4">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 4px;">
                <h1 class="h4 fw-bold mb-4"><?php echo $lang['your_information']; ?></h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" id="checkoutForm">
                    <?php echo csrf_input_field(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?php echo $lang['full_name']; ?> *</label>
                            <input type="text" name="full_name" class="form-control" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?php echo $lang['phone_number']; ?> *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="06XXXXXXXX" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?php echo $lang['delivery_address']; ?> *</label>
                            <input type="text" name="address" class="form-control" placeholder="Rue, N°, Appartement..." required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?php echo $lang['neighborhood']; ?></label>
                            <input type="text" name="neighborhood" class="form-control" value="<?php echo htmlspecialchars($_POST['neighborhood'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?php echo $lang['city']; ?></label>
                            <input type="text" class="form-control bg-light" value="<?php echo DEFAULT_CITY; ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?php echo $lang['order_notes']; ?></label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="<?php echo $lang['order_notes_hint']; ?>"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center text-success mb-3">
                            <i class="bi bi-shield-check me-2"></i>
                            <span class="small fw-bold"><?php echo $lang['payment_on_delivery']; ?></span>
                        </div>
                        <button type="submit" name="submit_order" class="btn btn-primary hri-btn-orange w-100 py-3 fw-bold shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i>
                            <?php echo $lang['send_via_whatsapp']; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 4px;">
                <h2 class="h6 text-uppercase fw-bold mb-3 pb-2 border-bottom"><?php echo $lang['order_summary']; ?></h2>
                
                <div class="overflow-auto mb-3" style="max-height: 300px;">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-truncate me-2" style="max-width: 70%;">
                                <?php echo $item['qty']; ?>x <?php echo get_product_name($item['data']); ?>
                            </span>
                            <span class="fw-bold"><?php echo format_price(get_product_effective_price($item['data']) * $item['qty']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo $lang['subtotal']; ?></span>
                        <span class="fw-bold"><?php echo format_price($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo $lang['delivery_fee']; ?></span>
                        <span><?php echo $delivery_fee > 0 ? format_price($delivery_fee) : $lang['free_delivery']; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                        <span class="h5 fw-bold"><?php echo $lang['total']; ?></span>
                        <span class="h5 fw-bold text-primary"><?php echo format_price($total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

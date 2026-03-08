<?php
/**
 * FILE: ajax/save_order.php
 * PURPOSE: AJAX: Receives POST order data, saves to DB, returns JSON with order number and WhatsApp URL.
 */

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Load configuration (DB, functions, session)
require_once __DIR__ . '/../config/config.php';

// Default response structure
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    // 1. Redirect if cart is empty
    $cart_items = get_cart_items();
    if (empty($cart_items)) {
        throw new Exception('Cart is empty');
    }

    $subtotal = calculate_cart_subtotal($cart_items);
    $delivery_fee = calculate_delivery_fee($subtotal);
    $total = calculate_cart_total($subtotal, $delivery_fee);

    // 2. Data Sanitization
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $phone     = sanitize_input($_POST['phone'] ?? '');
    $address   = sanitize_input($_POST['address'] ?? '');
    $quartier  = sanitize_input($_POST['neighborhood'] ?? '');
    $notes     = sanitize_input($_POST['notes'] ?? '');

    // 3. Validation
    if (empty($full_name)) throw new Exception('Full name is required');
    if (!validate_phone_morocco($phone)) throw new Exception('Invalid phone number');
    if (empty($address)) throw new Exception('Address is required');

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

        $response['success'] = true;
        $response['message'] = 'Order saved';
        $response['data'] = [
            'order_number' => $order_header['order_number'],
            'whatsapp_url' => $whatsapp_url
        ];
    } else {
        throw new Exception('Failed to save order');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('[HRI_AJAX_ERROR] save_order: ' . $e->getMessage());
}

echo json_encode($response);
exit;

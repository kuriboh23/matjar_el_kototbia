<?php
/**
 * FILE: ajax/cart_add.php
 * PURPOSE: AJAX: Receives POST product_id + quantity, adds to session cart, returns JSON.
 */

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Load configuration (DB, functions, session)
require_once __DIR__ . '/../config/config.php';

// Default response structure
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    // 1. Get and Sanitize Input
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity'])   ? (float)$_POST['quantity'] : 1;

    if ($product_id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // 2. Check if product exists and is active
    $product = get_product_by_id($product_id);
    if (!$product || !$product['product_is_active']) {
        throw new Exception('Product not found or inactive');
    }

    // 3. Add to Cart (Session Function)
    add_to_cart($product_id, $quantity);

    // 4. Return success
    $response['success'] = true;
    $response['message'] = translate('added_to_cart') ?? 'Ajouté au panier';
    $response['data'] = [
        'count' => get_cart_item_count()
    ];

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    error_log('[HRI_AJAX_ERROR] cart_add: ' . $e->getMessage());
}

echo json_encode($response);
exit;

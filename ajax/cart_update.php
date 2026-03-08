<?php
/**
 * FILE: ajax/cart_update.php
 * PURPOSE: AJAX: Update product quantity in session cart.
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';

$response = ['success' => false, 'message' => '', 'data' => null];

try {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity'])   ? (float)$_POST['quantity'] : 1;

    if ($product_id <= 0) throw new Exception('Invalid product');

    update_cart_quantity($product_id, $quantity);

    $response['success'] = true;
    $response['data'] = [
        'count' => get_cart_item_count()
    ];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;

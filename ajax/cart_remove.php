<?php
/**
 * FILE: ajax/cart_remove.php
 * PURPOSE: AJAX: Remove product from session cart.
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';

$response = ['success' => false, 'message' => '', 'data' => null];

try {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($product_id <= 0) throw new Exception('Invalid product');

    remove_from_cart($product_id);

    $response['success'] = true;
    $response['data'] = [
        'count' => get_cart_item_count()
    ];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;

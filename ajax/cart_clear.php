<?php
/**
 * FILE: ajax/cart_clear.php
 * PURPOSE: Clear all items from the cart via AJAX.
 */

require_once __DIR__ . '/../config/config.php';

$response = ['success' => false, 'message' => ''];

try {
    clear_cart();
    $response['success'] = true;
    $response['data'] = ['count' => 0];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;

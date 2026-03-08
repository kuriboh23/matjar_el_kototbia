<?php
/**
 * FILE: ajax/cart_count.php
 * PURPOSE: AJAX: Returns the total count of items in the session cart as JSON.
 */

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Load configuration (DB, functions, session)
require_once __DIR__ . '/../config/config.php';

// Default response structure
$response = ['success' => true, 'message' => 'OK', 'data' => ['count' => 0]];

try {
    $response['data']['count'] = get_cart_item_count();
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    error_log('[HRI_AJAX_ERROR] cart_count: ' . $e->getMessage());
}

echo json_encode($response);
exit;

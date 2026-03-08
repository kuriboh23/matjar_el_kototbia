<?php
/**
 * FILE: ajax/cart_update.php
 * PURPOSE: AJAX: Receives POST product_id + quantity, updates session cart, returns JSON.
 */

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Load configuration (DB, functions, session)
require_once __DIR__ . '/../config/config.php';

// Default response structure
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    // TODO: Implement cart_update logic

    $response['success'] = true;
    $response['message'] = 'OK';
} catch (Exception $e) {
    $response['message'] = translate('error_general');
    error_log('[HRI_AJAX_ERROR] cart_update: ' . $e->getMessage());
}

echo json_encode($response);
exit;

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
    // TODO: Implement save_order logic

    $response['success'] = true;
    $response['message'] = 'OK';
} catch (Exception $e) {
    $response['message'] = translate('error_general');
    error_log('[HRI_AJAX_ERROR] save_order: ' . $e->getMessage());
}

echo json_encode($response);
exit;

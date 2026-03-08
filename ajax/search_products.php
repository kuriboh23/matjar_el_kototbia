<?php
/**
 * FILE: ajax/search_products.php
 * PURPOSE: AJAX: Receives GET ?q=term, returns JSON array of matching products.
 */

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Load configuration (DB, functions, session)
require_once __DIR__ . '/../config/config.php';

// Default response structure
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    // TODO: Implement search_products logic

    $response['success'] = true;
    $response['message'] = 'OK';
} catch (Exception $e) {
    $response['message'] = translate('error_general');
    error_log('[HRI_AJAX_ERROR] search_products: ' . $e->getMessage());
}

echo json_encode($response);
exit;

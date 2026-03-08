<?php
/**
 * FILE: ajax/search_products.php
 * PURPOSE: AJAX: Live search for products (returns JSON).
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';

global $current_language;

$response = ['success' => false, 'message' => '', 'data' => []];

try {
    $q = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';

    if (strlen($q) < 2) {
        throw new Exception('Query too short');
    }

    $results = search_products($q);
    $data = [];

    foreach ($results as $p) {
        $data[] = [
            'product_id'   => $p['product_id'],
            'product_name' => get_product_name($p),
            'product_slug' => $p['product_slug'],
            'image_url'    => get_product_image_url($p['product_image']),
            'price_display'=> format_price(get_product_effective_price($p))
        ];
    }

    $response['success'] = true;
    $response['data'] = $data;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;

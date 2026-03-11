<?php
/**
 * FILE: ajax/admin_check_new_orders.php
 * PURPOSE: Check for any orders newer than the last checked ID.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

header('Content-Type: application/json');

$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

if ($last_id <= 0) {
    // If no last_id provided, just return the latest order ID as a starting point
    $latest = fetch_one("SELECT MAX(order_id) as max_id FROM hri_order");
    echo json_encode(['new_orders' => [], 'latest_id' => (int)($latest['max_id'] ?? 0)]);
    exit;
}

// Check for orders newer than last_id
$new_orders = fetch_all(
    "SELECT order_id, order_number, order_customer_name, order_total FROM hri_order WHERE order_id > :last_id ORDER BY order_id ASC",
    [':last_id' => $last_id]
);

$latest_id = count($new_orders) > 0 ? end($new_orders)['order_id'] : $last_id;

echo json_encode([
    'new_orders' => $new_orders,
    'latest_id' => (int)$latest_id
]);

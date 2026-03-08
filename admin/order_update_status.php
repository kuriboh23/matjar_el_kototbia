<?php
/**
 * FILE: admin/order_update_status.php
 * PURPOSE: Handle status update POST request.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['new_status'];

    if ($order_id > 0 && !empty($new_status)) {
        update_order_status($order_id, $new_status);
    }
}

header('Location: order_detail.php?id=' . $order_id);
exit;

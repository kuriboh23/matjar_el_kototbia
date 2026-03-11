<?php
/**
 * FILE: pages/order_delete.php
 * PURPOSE: Handle order deletion by customer.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    $order_id = (int)$_POST['order_id'];
    $customer_id = get_current_customer_id();

    // Fetch order to verify ownership and status
    $order = get_order_by_id($order_id);

    if ($order && (int)$order['order_customer_id'] === $customer_id && $order['order_status'] === ORDER_STATUS_PENDING) {
        if (delete_order($order_id)) {
            set_flash_message('success', translate('order_cancelled') ?? 'Commande supprimée.');
        } else {
            set_flash_message('error', translate('error_general'));
        }
    } else {
        set_flash_message('error', 'Action non autorisée ou commande déjà traitée.');
    }
}

redirect(SITE_URL . '/pages/order_history.php');

<?php
/**
 * FILE: admin/order_update_status.php
 * PURPOSE: Handle status update POST request and notify customer via WhatsApp.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['new_status'];
    $livreur_id = isset($_POST['livreur_id']) ? (int)$_POST['livreur_id'] : null;

    if ($order_id > 0 && !empty($new_status)) {
        update_order_status($order_id, $new_status);
        
        // Update livreur if provided
        global $db_connection;
        $stmt = $db_connection->prepare("UPDATE hri_order SET order_livreur_id = :livreur_id WHERE order_id = :order_id");
        $stmt->bindValue(':livreur_id', $livreur_id ? $livreur_id : null, $livreur_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Get order data to prepare notification
        $order = get_order_by_id($order_id);
        if ($order) {
            $customer_phone = clean_phone_number($order['order_customer_phone']);
            if (strpos($customer_phone, '0') === 0 && strlen($customer_phone) === 10) {
                $customer_phone = '212' . substr($customer_phone, 1);
            }
            
            // Choose language based on order language or default to FR
            $lang_code = $order['order_language'] ?? 'fr';
            include __DIR__ . "/../lang/{$lang_code}.php";
            
            $status_label = $lang["status_{$new_status}"] ?? $new_status;
            $msg = sprintf($lang['status_update_msg'], $order['order_customer_name'], $order['order_number'], $status_label);
            $wa_url = build_whatsapp_url($customer_phone, $msg);
            
            // Redirect to WhatsApp if the user wants to notify (we'll handle this with a flash message or similar)
            // But since this is a direct POST from form, it's better to redirect back with a "Notify" button or auto-open
            // Let's use a session variable to trigger the WhatsApp open on the next page load of order_detail.php
            $_SESSION['notify_customer_wa_url'] = $wa_url;
        }
    }
}

header('Location: order_detail.php?id=' . $order_id);
exit;

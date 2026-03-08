<?php
/**
 * FILE: admin/product_toggle.php
 * PURPOSE: Toggle product active status (visible/hidden).
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    $product = get_product_by_id($product_id);
    if ($product) {
        $new_status = $product['product_is_active'] ? 0 : 1;
        execute_query("UPDATE hri_product SET product_is_active = :status WHERE product_id = :id", [
            ':status' => $new_status,
            ':id' => $product_id
        ]);
    }
}

header('Location: products.php');
exit;

<?php
/**
 * FILE: admin/product_delete.php
 * PURPOSE: Delete a product from catalog.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    if (delete_product($product_id)) {
        // Success
    }
}

header('Location: products.php');
exit;

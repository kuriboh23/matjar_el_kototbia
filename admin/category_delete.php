<?php
/**
 * FILE: admin/category_delete.php
 * PURPOSE: Delete a category.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        execute_query("DELETE FROM hri_category WHERE category_id = :id", [':id' => $id]);
    } catch (PDOException $e) {
        // Log or handle error if products are linked (restrict)
    }
}

header('Location: categories.php');
exit;

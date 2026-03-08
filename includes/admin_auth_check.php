<?php
/**
 * FILE: includes/admin_auth_check.php
 * PURPOSE: Admin authentication middleware.
 *          Include at the top of every admin page (except login).
 *          Redirects to admin login if not authenticated.
 */

if (empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

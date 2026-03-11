<?php
/**
 * FILE: admin/logout.php
 * PURPOSE: Destroys admin session and redirects to admin login page.
 */
require_once __DIR__ . '/../config/config.php';

// Unset admin-specific session variables
unset($_SESSION[ADMIN_SESSION_KEY]);
unset($_SESSION['hri_admin_name']);

// Optional: Destroy the whole session if needed, but better to keep customer session if any
// session_destroy();

redirect(SITE_URL . '/admin/login.php');
exit;

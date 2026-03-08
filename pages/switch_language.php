<?php
/**
 * FILE: pages/switch_language.php
 * PURPOSE: Handles language switching. Sets cookie/session, redirects back.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

$lang_code = $_GET['lang'] ?? DEFAULT_LANGUAGE;

// Validate language code
if (!in_array($lang_code, SUPPORTED_LANGUAGES)) {
    $lang_code = DEFAULT_LANGUAGE;
}

// 1. Set Session
$_SESSION['hri_current_language'] = $lang_code;

// 2. Set Cookie
setcookie(
    LANG_COOKIE_NAME,
    $lang_code,
    time() + LANG_COOKIE_EXPIRY,
    '/',
    '',
    false, // Change to true if using HTTPS
    true   // HttpOnly
);

// 3. Update DB if logged in
if (is_customer_logged_in()) {
    $customer_id = get_current_customer_id();
    execute_query(
        "UPDATE hri_customer SET customer_preferred_lang = :lang WHERE customer_id = :id",
        [':lang' => $lang_code, ':id' => $customer_id]
    );
}

// Redirect back to previous page or home
$referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL . '/index.php';

// Remove existing lang param from referer to avoid loops if needed, 
// but usually it's fine as the handler priority takes over.
header("Location: $referer");
exit;

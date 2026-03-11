<?php
/**
 * FILE: config/config.php
 * PURPOSE: Master configuration loader. Include this ONCE at the top of
 *          every page to bootstrap the entire application.
 * LOADS: constants → database → functions → language → session
 */

/* ----------------------------------------------------------------
 * ENVIRONMENT SETTINGS
 * Set to 'development' locally, 'production' on live server.
 * ---------------------------------------------------------------- */
define('ENVIRONMENT', 'development'); // Change to 'production' when live

/* ----------------------------------------------------------------
 * ERROR REPORTING (based on environment)
 * ---------------------------------------------------------------- */
if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__) . '/logs/error.log');
}

/* ----------------------------------------------------------------
 * LOAD CORE FILES IN ORDER
 * ---------------------------------------------------------------- */

// 1. Global constants (site name, URLs, business rules)
require_once __DIR__ . '/constants.php';

// 2. Database connection (creates $db_connection)
require_once __DIR__ . '/database.php';

// 3. Helper functions (sanitize, validate, format, etc.)
require_once dirname(__DIR__) . '/includes/functions.php';

/* ----------------------------------------------------------------
 * INITIALIZE DYNAMIC CONSTANTS (DB Overrides)
 * ---------------------------------------------------------------- */

// at the top of config/constants.php (before $hri_defaults):

try {
    $db_settings = get_all_settings();
    
    // Mapping DB keys to Constant names
    $hri_mapping = [
        'store_name_fr'           => 'SITE_NAME_FR',
        'store_name_ar'           => 'SITE_NAME_AR',
        'store_tagline_fr'        => 'SITE_TAGLINE_FR',
        'store_tagline_ar'        => 'SITE_TAGLINE_AR',
        'store_city'              => 'DEFAULT_CITY',
        'store_whatsapp'          => 'STORE_WHATSAPP_NUMBER',
        'store_whatsapp_number'   => 'STORE_WHATSAPP_NUMBER',
        'store_phone'             => 'STORE_PHONE_DISPLAY',
        'store_phone_display'     => 'STORE_PHONE_DISPLAY',
        'minimum_order_amount'    => 'MINIMUM_ORDER_AMOUNT',
        'min_order_amount'        => 'MINIMUM_ORDER_AMOUNT',
        'delivery_fee'            => 'DELIVERY_FEE',
        'free_delivery_threshold' => 'FREE_DELIVERY_THRESHOLD',
        'default_language'        => 'DEFAULT_LANGUAGE'
    ];

    foreach ($hri_mapping as $db_key => $const_name) {
        if (!defined($const_name)) {
            $val = $db_settings[$db_key] ?? $hri_defaults[$const_name] ?? null;
            if ($val !== null) {
                // Type conversion for numbers
                if (in_array($const_name, ['MINIMUM_ORDER_AMOUNT', 'DELIVERY_FEE', 'FREE_DELIVERY_THRESHOLD'])) {
                    $val = (float)$val;
                }
                define($const_name, $val);
            }
        }
    }
} catch (Exception $e) {
    if (!defined('STORE_PHONE_DISPLAY')) {
    define('STORE_PHONE_DISPLAY', '');
}
if (!defined('STORE_WHATSAPP_NUMBER')) {
    define('STORE_WHATSAPP_NUMBER', '');
}
if (!defined('DEFAULT_CITY')) {
    define('DEFAULT_CITY', 'Safi');
}
if (!defined('DEFAULT_LANGUAGE')) {
    define('DEFAULT_LANGUAGE', 'fr');
}

    // Fallback to defaults if DB fails
    error_log("Settings init error: " . $e->getMessage());
}

// Define any remaining defaults that weren't in mapping or DB
foreach ($hri_defaults as $const_name => $default_val) {
    if (!defined($const_name)) {
        define($const_name, $default_val);
    }
}

// 4. Cart functions (add, remove, calculate)
require_once dirname(__DIR__) . '/includes/cart_functions.php';

// 5. Order functions (create, WhatsApp builder)
require_once dirname(__DIR__) . '/includes/order_functions.php';

// 6. Flash messages (session-based notifications)
require_once dirname(__DIR__) . '/includes/flash_messages.php';

/* ----------------------------------------------------------------
 * START SESSION (if not already started)
 * ---------------------------------------------------------------- */
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

/* ----------------------------------------------------------------
 * LOAD LANGUAGE (after session is started)
 * ---------------------------------------------------------------- */
require_once dirname(__DIR__) . '/includes/language_handler.php';

/* ----------------------------------------------------------------
 * SET DEFAULT TIMEZONE
 * ---------------------------------------------------------------- */
date_default_timezone_set('Africa/Casablanca');

/* ----------------------------------------------------------------
 * GENERATE CSRF TOKEN (if none exists in session)
 * ---------------------------------------------------------------- */
if (empty($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

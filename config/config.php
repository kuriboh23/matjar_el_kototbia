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

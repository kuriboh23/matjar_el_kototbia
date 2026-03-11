<?php
/**
 * FILE: config/constants.php
 * PURPOSE: Core constants for Matjar El Kotobia.
 * WARNING: These constant names are PERMANENT. Never rename them.
 */

/* ============================================================
 * CORE SYSTEM SETTINGS (Hardcoded)
 * ============================================================ */
define('SITE_URL', 'http://localhost/matjar_el_kotobia');   // Change for production
define('SITE_ROOT', dirname(__DIR__));                    // Absolute path to project root

define('DEFAULT_COUNTRY', 'Morocco');
define('DEFAULT_CURRENCY', 'DH');
define('DEFAULT_CURRENCY_CODE', 'MAD');
define('WHATSAPP_API_URL', 'https://wa.me/');

define('SESSION_NAME', 'hri_session');
define('CUSTOMER_SESSION_KEY', 'hri_customer_id');
define('ADMIN_SESSION_KEY', 'hri_admin_id');
define('CART_SESSION_KEY', 'hri_cart');
define('REMEMBER_ME_COOKIE', 'hri_remember');
define('CSRF_TOKEN_NAME', 'hri_csrf_token');
define('SESSION_LIFETIME', 1800); 

define('UPLOAD_DIR', SITE_ROOT . '/assets/uploads/products/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/products/');
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('PRODUCT_IMAGE_WIDTH', 600);
define('PRODUCT_IMAGE_HEIGHT', 600);
define('PRODUCT_THUMB_WIDTH', 300);
define('PRODUCT_THUMB_HEIGHT', 300);
define('DEFAULT_PRODUCT_IMAGE', 'default_product.jpg');

define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_CONFIRMED', 'confirmed');
define('ORDER_STATUS_PREPARING', 'preparing');
define('ORDER_STATUS_OUT_FOR_DELIVERY', 'out_for_delivery');
define('ORDER_STATUS_DELIVERED', 'delivered');
define('ORDER_STATUS_CANCELLED', 'cancelled');

define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE_ADMIN', 20);
define('CUSTOMERS_PER_PAGE_ADMIN', 20);
define('ORDER_NUMBER_PREFIX', 'MK');

/* ============================================================
 * SITE SETTING DEFAULTS (Used if DB is empty)
 * ============================================================ */
$hri_defaults = [
    'SITE_NAME_FR'           => 'Matjar El Kotobia',
    'SITE_NAME_AR'           => 'سوبرماركت هري',
    'SITE_TAGLINE_FR'        => 'Vos courses quotidiennes en ligne',
    'SITE_TAGLINE_AR'        => 'تسوقك اليومي أونلاين',
    'DEFAULT_CITY'           => 'Safi',
    'STORE_WHATSAPP_NUMBER'  => '212600000000',
    'STORE_PHONE_DISPLAY'    => '06 00 00 00 08',
    'MINIMUM_ORDER_AMOUNT'   => 50.00,
    'DELIVERY_FEE'           => 10.00,
    'FREE_DELIVERY_THRESHOLD'=> 200.00,
    'DEFAULT_LANGUAGE'       => 'fr',
    'SUPPORTED_LANGUAGES'    => ['fr', 'ar'],
    'LANG_COOKIE_NAME'       => 'hri_language',
    'LANG_COOKIE_EXPIRY'     => 365 * 24 * 60 * 60
];


<?php
/**
 * FILE: config/constants.php
 * PURPOSE: Global constants used throughout Matjar El Kotobia.
 * WARNING: These constant names are PERMANENT. Never rename them.
 *          Any developer or AI assistant must use these exact names.
 * LAST UPDATED: 2025-01-01
 */

/* ============================================================
 * SITE INFORMATION
 * ============================================================ */
define('SITE_NAME_FR', 'Matjar El Kotobia');
define('SITE_NAME_AR', 'سوبرماركت هري');
define('SITE_TAGLINE_FR', 'Vos courses quotidiennes en ligne');
define('SITE_TAGLINE_AR', 'تسوقك اليومي أونلاين');
define('SITE_URL', 'http://localhost/matjar_el_kotobia');   // Change for production
define('SITE_ROOT', dirname(__DIR__));                    // Absolute path to project root

/* ============================================================
 * DEFAULT LOCATION (Safi, Morocco — local delivery only)
 * ============================================================ */
define('DEFAULT_CITY', 'Safi');
define('DEFAULT_COUNTRY', 'Morocco');
define('DEFAULT_CURRENCY', 'DH');
define('DEFAULT_CURRENCY_CODE', 'MAD');

/* ============================================================
 * STORE CONTACT (WhatsApp)
 * ============================================================ */
define('STORE_WHATSAPP_NUMBER', '212600000000');   // Full international format
define('STORE_PHONE_DISPLAY', '06 00 00 00 08');   // Display format
define('WHATSAPP_API_URL', 'https://wa.me/');       // WhatsApp deep link base

/* ============================================================
 * BUSINESS RULES
 * ============================================================ */
define('MINIMUM_ORDER_AMOUNT', 50.00);     // Minimum order in DH
define('DELIVERY_FEE', 10.00);             // Flat delivery fee in DH
define('FREE_DELIVERY_THRESHOLD', 200.00); // Free delivery above this DH amount

/* ============================================================
 * LANGUAGE SETTINGS
 * ============================================================ */
define('DEFAULT_LANGUAGE', 'fr');
define('SUPPORTED_LANGUAGES', ['fr', 'ar']);
define('LANG_COOKIE_NAME', 'hri_language');
define('LANG_COOKIE_EXPIRY', 365 * 24 * 60 * 60); // 1 year in seconds

/* ============================================================
 * SESSION & AUTHENTICATION
 * ============================================================ */
define('SESSION_NAME', 'hri_session');
define('CUSTOMER_SESSION_KEY', 'hri_customer_id');
define('ADMIN_SESSION_KEY', 'hri_admin_id');
define('CART_SESSION_KEY', 'hri_cart');
define('REMEMBER_ME_COOKIE', 'hri_remember');
define('CSRF_TOKEN_NAME', 'hri_csrf_token');
define('SESSION_LIFETIME', 1800); // 30 minutes in seconds

/* ============================================================
 * FILE UPLOAD SETTINGS
 * ============================================================ */
define('UPLOAD_DIR', SITE_ROOT . '/assets/uploads/products/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/products/');
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024);  // 2 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('PRODUCT_IMAGE_WIDTH', 600);
define('PRODUCT_IMAGE_HEIGHT', 600);
define('PRODUCT_THUMB_WIDTH', 300);
define('PRODUCT_THUMB_HEIGHT', 300);
define('DEFAULT_PRODUCT_IMAGE', 'default_product.jpg');

/* ============================================================
 * ORDER STATUS CONSTANTS
 * ============================================================ */
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_CONFIRMED', 'confirmed');
define('ORDER_STATUS_PREPARING', 'preparing');
define('ORDER_STATUS_OUT_FOR_DELIVERY', 'out_for_delivery');
define('ORDER_STATUS_DELIVERED', 'delivered');
define('ORDER_STATUS_CANCELLED', 'cancelled');

/* ============================================================
 * PAGINATION
 * ============================================================ */
define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE_ADMIN', 20);
define('CUSTOMERS_PER_PAGE_ADMIN', 20);

/* ============================================================
 * ORDER NUMBER PREFIX
 * ============================================================ */
define('ORDER_NUMBER_PREFIX', 'HRI');

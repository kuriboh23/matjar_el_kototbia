# GEMINI.md - Project Context & Instructions

## Project Overview
**Matjar El Kotobia** (`matjar_el_kotobia`) is a mobile-first, bilingual (French/Arabic) e-commerce website for a local supermarket in Safi, Morocco. 

### Core Workflow
- **Browsing:** Customers browse products by category or search.
- **Cart:** Items are managed in a persistent cart (Session/DB).
- **Checkout:** Instead of an online payment gateway, the site generates a formatted WhatsApp message containing the order details and redirects the user to the store owner's WhatsApp number.
- **Payment:** Cash on Delivery (COD).
- **Admin:** A full dashboard to manage products, categories, orders, and settings.

## Tech Stack
- **Backend:** PHP 8.1+ (Procedural with organized includes).
- **Database:** MySQL 8.0+ / MariaDB (Charset: `utf8mb4` for Arabic support).
- **Frontend:** Bootstrap 5.3, Custom CSS (Vanilla), Vanilla JavaScript.
- **Icons:** Bootstrap Icons.
- **Fonts:** Poppins (FR) and Cairo (AR).

## Directory Structure Highlights
- `/admin/`: Admin panel logic and pages.
- `/ajax/`: Backend endpoints for live search and cart operations.
- `/assets/`: CSS, JS, and image assets.
- `/config/`: Core configuration (`config.php`, `constants.php`, `database.php`).
- `/includes/`: Reusable components and helper functions.
- `/lang/`: Translation files (`fr.php`, `ar.php`).
- `/pages/`: Customer-facing page content.
- `/sql/`: Database schema and seed data.

## Development Conventions

### Naming Standards (Mandatory)
- **PHP Variables/Functions:** `snake_case` (e.g., `$product_data`, `get_all_products()`).
- **JavaScript Variables/Functions:** `camelCase` (e.g., `hriCartItems`, `addToCart()`).
- **CSS Classes:** `kebab-case` (e.g., `.hri-product-card`).
- **Constants:** `UPPER_SNAKE_CASE` (e.g., `SITE_URL`).
- **Database Tables:** Prefix with `hri_` (e.g., `hri_product`).
- **Database Columns:** Prefix with entity name (e.g., `product_id`, `category_name_fr`).

### Multi-language Support
- Use the `translate('key')` function for all UI text.
- Arabic uses `dir="rtl"` and the `rtl.css` stylesheet.
- Product names and descriptions have `_fr` and `_ar` versions in the database.

### Database Interaction
- Use PDO for all database queries.
- Connection is available via the global `$db_connection` variable (loaded in `config/config.php`).

## Key Commands & Setup
- **Server:** Runs on Apache (XAMPP/Laragon). Entry point: `index.php`.
- **Database:** Import `sql/matjar_el_kotobia.sql`.
- **Configuration:** Update `config/database.php` for credentials and `config/constants.php` for site-wide settings.

## WhatsApp Integration Logic
The checkout process:
1. Saves the order to `hri_order` and `hri_order_item`.
2. Formats a string using `build_whatsapp_message()` in `includes/order_functions.php`.
3. Encodes the message and redirects to `https://wa.me/{STORE_NUMBER}?text={ENCODED_MESSAGE}`.

## Security Mandates
- **Sanitization:** Always use `sanitize_input()` or PDO prepared statements.
- **CSRF:** All POST requests must include a CSRF token (`$_SESSION[CSRF_TOKEN_NAME]`).
- **Auth:** Check `is_customer_logged_in()` or `is_admin_logged_in()` for protected routes.

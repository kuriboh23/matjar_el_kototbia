# Matjar El Kotobia PRD

> **Last Updated:** 2026-03-11  
> _Added order printing & sharing, AJAX cart/count endpoints, and admin UX enhancements._

---

## Table of Contents

1. Overview
2. Features
3. User Stories
4. Tech Requirements
5. Database Design
6. Variable Naming Convention & Code Standards
7. Page Flows
8. Wireframes (Text-Based)
9. Security
10. Deployment Considerations
11. Risks
12. Revision History

---

## 1. Overview

### 1.1 Project Name

Matjar El Kotobia (`matjar_el_kotobia`)

### 1.2 Tagline

> _"سوبرماركت هري — تسوقك اليومي أونلاين"_  
> _"Matjar El Kotobia — Vos courses quotidiennes en ligne"_

### 1.3 Purpose

Build a **mobile-first, bilingual (French/Arabic) grocery e-commerce website** for a local Moroccan supermarket located in **Safi, Morocco**. The platform allows customers to browse products, add items to a cart, and **complete checkout via WhatsApp** — sending a beautifully formatted order message directly to the store owner's phone number. There is **no online payment gateway**; payment is handled upon delivery (Cash on Delivery). The **admin panel** provides intuitive management of products, orders, and customers, including the ability to **print/share order receipts** for delivery and bookkeeping.

### 1.4 Business Model

- **Local delivery only** (City of Safi initially)
- **Cash on Delivery (COD)** — no online payment
- **WhatsApp-based order submission** replaces traditional checkout
- Store owner receives orders on WhatsApp and manages fulfillment manually

### 1.5 Target Audience

- Local residents of Safi, Morocco
- Primarily mobile users (Android/iOS browsers)
- French and Arabic speaking customers
- Age range: 20–55

### 1.6 Key Differentiators

|Aspect|Traditional E-commerce|HRI Supermarket|
|---|---|---|
|Checkout|Online payment|WhatsApp message|
|Scope|National/International|Local (Safi)|
|Communication|Email/SMS|WhatsApp|
|Complexity|High|Simple & fast|
|Trust|Requires payment trust|Familiar WhatsApp flow|

### 1.7 Color Palette & Branding

text

```
PRIMARY COLORS:
┌─────────────────────────────────────────────────┐
│  Jumia Orange     : #F68B1E  (Brand Primary)    │
│  Dark UI          : #313133  (Headers/nav)      │
│  Light Gray BG    : #F5F5F5  (Backgrounds)      │
│                                                   │
│  SECONDARY COLORS:                                │
│  Accent Orange    : #F68B1E  (Buttons/CTA)       │
│  Accent Red       : #DF3B3B  (Sale/badges)       │
│                                                   │
│  NEUTRALS:                                        │
│  White            : #FFFFFF  (Background)         │
│  Light Gray       : #F5F5F5  (Cards/sections)    │
│  Medium Gray      : #75757A  (Secondary text)    │
│  Dark Gray        : #282828  (Primary text)      │
│  Near Black       : #000000  (Headers)           │
│                                                   │
│  WHATSAPP:                                        │
│  WhatsApp Green   : #25D366  (Checkout button)   │
└─────────────────────────────────────────────────┘

CSS VARIABLE NAMES (permanent):
--color-primary         : #F68B1E;
--color-primary-dark    : #313133;
--color-primary-light   : #F5F5F5;
--color-accent-orange   : #F68B1E;
--color-accent-red      : #DF3B3B;
--color-white           : #FFFFFF;
--color-gray-light      : #F5F5F5;
--color-gray-medium     : #75757A;
--color-text-dark       : #282828;
--color-text-darker     : #000000;
--color-whatsapp        : #25D366;
```

### 1.8 Typography

text

```
FRENCH TEXT:
  Font Family : 'Poppins', sans-serif
  Weights     : 300 (light), 400 (regular), 600 (semibold), 700 (bold)

ARABIC TEXT:
  Font Family : 'Cairo', sans-serif
  Weights     : 400 (regular), 600 (semibold), 700 (bold)

CSS VARIABLE NAMES (permanent):
--font-family-fr    : 'Poppins', sans-serif;
--font-family-ar    : 'Cairo', sans-serif;
--font-size-xs      : 0.75rem;   /* 12px */
--font-size-sm      : 0.875rem;  /* 14px */
--font-size-base    : 1rem;      /* 16px */
--font-size-md      : 1.125rem;  /* 18px */
--font-size-lg      : 1.5rem;    /* 24px */
--font-size-xl      : 2rem;      /* 32px */
--font-size-xxl     : 2.5rem;    /* 40px */
```

---

## 2. Features

### 2.1 Feature Matrix by Role

text

```
┌──────────────────────────────────────┬──────────┬─────────┐
│           FEATURE                    │ CUSTOMER │  ADMIN  │
├──────────────────────────────────────┼──────────┼─────────┤
│ Browse products                      │    ✅    │   ✅    │
│ Search products                      │    ✅    │   ✅    │
│ Filter by category                   │    ✅    │   ✅    │
│ View product details                 │    ✅    │   ✅    │
│ Add/remove cart items                │    ✅    │   ❌    │
│ Adjust item quantities               │    ✅    │   ❌    │
│ Checkout via WhatsApp                │    ✅    │   ❌    │
│ Register / Login                     │    ✅    │   ✅    │
│ Save/edit personal info              │    ✅    │   ❌    │
│ View order history                   │    ✅    │   ✅    │
│ Switch language (FR/AR)              │    ✅    │   ✅    │
│ Admin Dashboard                      │    ❌    │   ✅    │
│ Manage products (CRUD)               │    ❌    │   ✅    │
│ Manage categories (CRUD)             │    ❌    │   ✅    │
│ Manage orders (view/update status)   │    ❌    │   ✅    │
│ Manage customers (view)              │    ❌    │   ✅    │
│ Update store settings                │    ❌    │   ✅    │
│ Upload product images                │    ❌    │   ✅    │
│ Set featured / on-sale products      │    ❌    │   ✅    │
│ View basic analytics                 │    ❌    │   ✅    │
│ Manage delivery personnel            │    ❌    │   ✅    │
│ Assign delivery person to orders     │    ❌    │   ✅    │
└──────────────────────────────────────┴──────────┴─────────┘
```

### 2.2 Customer-Facing Features (Detailed)

#### F-C01: Product Browsing

- Grid layout of product cards (2 columns mobile, 3 tablet, 4 desktop)
- Each card shows: image, name (bilingual), price, unit (kg/piece/pack), add-to-cart button
- Sale badge overlay on discounted items
- Lazy loading for images

#### F-C02: Category Navigation

- Horizontal scrollable category bar on mobile (with icons)
- Sidebar filter on desktop
- Categories examples: Fruits & Légumes, Viandes, Produits Laitiers, Épicerie, Boissons, Hygiène, Bébé, Ménage

#### F-C03: Product Search

- Search bar in header (always visible)
- Real-time search suggestions (AJAX)
- Search works across both French and Arabic product names

#### F-C04: Shopping Cart

- Persistent cart (saved in `localStorage` for guests, database for logged-in users)
- Slide-in cart panel on mobile
- Quantity adjustment (+/-)
- Item removal
- Running total calculation
- Minimum order amount display (configurable by admin)
- Cart badge counter on header icon
- All cart operations (add/update/remove/clear/count) handled via AJAX for seamless UX

#### F-C05: WhatsApp Checkout

- Pre-checkout form collects:
    - `customer_full_name` (required)
    - `customer_phone` (required, Moroccan format)
    - `customer_address` (required)
    - `customer_neighborhood` (`quartier`, optional dropdown)
    - `customer_city` (default: "Safi", read-only for V1)
    - `customer_notes` (optional, delivery instructions)
- "Save my info for next time" checkbox → stores in DB (if logged in) or `localStorage`
- On submit:
    1. Order saved to database with status `pending`
    2. Formatted WhatsApp message generated
    3. User redirected to `wa.me/<STORE_PHONE>?text=<encoded_message>`

**WhatsApp Message Template:**

text

```
🛒 *طلب جديد — Nouvelle Commande*
━━━━━━━━━━━━━━━━━━━

👤 *الاسم — Nom:* Mohammed Alaoui
📞 *الهاتف — Tél:* 0612345678
📍 *العنوان — Adresse:* Rue 123, Hay Salam
🏙️ *المدينة — Ville:* Safi

━━━━━━━━━━━━━━━━━━━
📦 *المنتجات — Produits:*

1️⃣ Tomates / طماطم
   🔢 Qté: 2 kg
   💰 Prix: 14.00 DH

2️⃣ Lait Centrale / حليب سنطرال
   🔢 Qté: 3 unités
   💰 Prix: 21.00 DH

3️⃣ Huile d'olive / زيت الزيتون
   🔢 Qté: 1 litre
   💰 Prix: 45.00 DH

━━━━━━━━━━━━━━━━━━━
💰 *الإجمالي — Total:* 80.00 DH
🚚 *التوصيل — Livraison:* 10.00 DH
💵 *المجموع — Montant Total:* 90.00 DH

📝 *ملاحظات — Notes:* Livraison après 18h svp

━━━━━━━━━━━━━━━━━━━
✅ الدفع عند الاستلام — Paiement à la livraison
🕐 وقت الطلب — Heure: 2025-01-01 14:30
```

#### F-C06: User Registration & Login

- Registration: name, phone, email (optional), password, address
- Login via phone + password
- "Remember me" functionality
- Password recovery via simple admin contact (V1, no email system)

#### F-C07: User Profile

- Edit personal information
- View saved addresses
- View order history with statuses

#### F-C08: Language Switching

- Toggle button FR/AR in header
- Language preference saved in cookie/session/localStorage
- Full RTL support for Arabic
- Default language: French

#### F-C09: Responsive Design

- **Mobile-first approach** (primary target)
- Breakpoints:
    - Mobile: `< 576px`
    - Small tablet: `576px – 767px`
    - Tablet: `768px – 991px`
    - Desktop: `992px – 1199px`
    - Large desktop: `≥ 1200px`
- Bottom navigation bar on mobile (Home, Categories, Cart, Account)
- Hamburger menu

### 2.3 Admin Panel Features (Detailed)

#### F-A01: Admin Dashboard

- Today's orders count and total revenue
- Pending orders alert
- Low stock alerts
- Quick stats: total products, total customers, total orders

#### F-A02: Product Management

- Add/Edit/Delete/Toggle-active products
- Fields: name_fr, name_ar, description_fr, description_ar, price, sale_price, unit, stock_quantity, category, image, is_featured, is_active
- Bulk actions (activate/deactivate)
- Image upload with compression

#### F-A03: Category Management

- Add/Edit/Delete/Reorder categories
- Fields: name_fr, name_ar, icon, display_order, is_active
- Parent-child category support (optional V2)

#### F-A04: Order Management

- View all orders with filters (date, status)
- Order statuses: `pending` → `confirmed` → `preparing` → `out_for_delivery` → `delivered` / `cancelled`
- View order details
- Update order status
- Re-send WhatsApp message

#### F-A05: Customer Management

- View registered customers
- View customer order history
- No customer editing/deletion in V1

#### F-A08: Delivery Personnel Management

- Add/Edit/Deactivate delivery personnel ("livreurs")
- Store name, phone number and active status
- Assign a livreur to an order from the order detail screen
- Display assigned livreur info on printed receipts and in WhatsApp messages

#### F-A06: Store Settings

- Store name, phone (WhatsApp number), address
- Minimum order amount
- Delivery fee
- Operating hours
- Delivery zones within Safi
- Maintenance mode toggle

#### F-A07: Admin Authentication

- Separate admin login page
- Admin credentials managed in database
- Session-based authentication

---

## 3. User Stories

### 3.1 Customer User Stories

text

```
┌────────┬────────────────────────────────────────────────────────────┬──────────┐
│   ID   │                     USER STORY                            │ PRIORITY │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C01 │ As a customer, I want to browse products by category      │  HIGH    │
│        │ so that I can find what I need quickly.                    │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C02 │ As a customer, I want to search for products by name      │  HIGH    │
│        │ (in French or Arabic) to find specific items.             │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C03 │ As a customer, I want to add products to my cart and      │  HIGH    │
│        │ adjust quantities so I can build my grocery list.         │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C04 │ As a customer, I want to view my cart total at any time   │  HIGH    │
│        │ so I can manage my budget.                                │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C05 │ As a customer, I want to checkout via WhatsApp so that    │  HIGH    │
│        │ I can place my order easily without online payment.       │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C06 │ As a customer, I want to enter my name, phone, and       │  HIGH    │
│        │ address at checkout and save it for future orders.        │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C07 │ As a customer, I want to see the website in French or    │  HIGH    │
│        │ Arabic based on my preference.                            │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C08 │ As a customer, I want to use the website easily on my    │  HIGH    │
│        │ mobile phone since that's my primary device.              │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C09 │ As a customer, I want to register an account so my       │  MEDIUM  │
│        │ info and order history are saved.                         │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C10 │ As a customer, I want to see product prices clearly      │  HIGH    │
│        │ in Moroccan Dirhams (DH).                                │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C11 │ As a customer, I want to see which products are on sale  │  MEDIUM  │
│        │ so I can get the best deals.                              │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C12 │ As a customer, I want to view my past orders so I can    │  LOW     │
│        │ reorder easily.                                           │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C13 │ As a guest customer, I want to place an order without    │  HIGH    │
│        │ creating an account.                                      │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-C14 │ As a customer, I want to see the delivery fee and        │  HIGH    │
│        │ minimum order amount before checkout.                     │          │
└────────┴────────────────────────────────────────────────────────────┴──────────┘
```

### 3.2 Admin User Stories

text

```
┌────────┬────────────────────────────────────────────────────────────┬──────────┐
│   ID   │                     USER STORY                            │ PRIORITY │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A01 │ As an admin, I want to add/edit/delete products so I     │  HIGH    │
│        │ can keep the catalog up to date.                          │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A02 │ As an admin, I want to manage categories to organize     │  HIGH    │
│        │ products logically.                                       │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A03 │ As an admin, I want to view and manage incoming orders   │  HIGH    │
│        │ so I can fulfill them.                                    │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A04 │ As an admin, I want to update order statuses so          │  HIGH    │
│        │ customers are informed of their order progress.           │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A05 │ As an admin, I want to set the WhatsApp number, delivery │  HIGH    │
│        │ fee, and minimum order amount in settings.                │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A06 │ As an admin, I want to see a dashboard with today's      │  MEDIUM  │
│        │ orders and revenue at a glance.                           │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A07 │ As an admin, I want to mark products as featured or      │  MEDIUM  │
│        │ on sale to promote them on the homepage.                  │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A08 │ As an admin, I want to view registered customers and     │  LOW     │
│        │ their order history.                                      │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A09 │ As an admin, I want to upload product images easily.     │  HIGH    │
│        │                                                           │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A10 │ As an admin, I want to log in securely with my own       │  HIGH    │
│        │ credentials separate from customer accounts.              │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A11 │ As an admin, I want to print or share order receipts     │  MEDIUM  │
│        │ for record‑keeping and delivery slips.                    │          │
├────────┼────────────────────────────────────────────────────────────┼──────────┤
│ US-A12 │ As an admin, I want to manage delivery personnel and     │  MEDIUM  │
│        │ assign them to orders.                                   │          │
└────────┴────────────────────────────────────────────────────────────┴──────────┘
```

---

## 4. Tech Requirements


---

## 12. Revision History

* **2026-03-11** – Added admin order printing/sharing, AJAX cart/count
  operations, included update to purpose description and user stories.
* **2026-03-11** – Added delivery personnel (`hri_livreur`) and guest info
  (`hri_guest_info`) support; updated feature list, user stories, workflow, and
  database design.

---

### 4.1 Tech Stack

text

```
┌─────────────────────────────────────────────────────────────────┐
│                      TECH STACK OVERVIEW                        │
├──────────────┬──────────────────────────────────────────────────┤
│ Backend      │ PHP 8.1+ (Procedural with organized includes)   │
│ Database     │ MySQL 8.0+ / MariaDB 10.6+                     │
│ Frontend     │ Bootstrap 5.3 + Custom CSS + Vanilla JavaScript │
│ Icons        │ Bootstrap Icons / Font Awesome 6                │
│ Fonts        │ Google Fonts (Poppins + Cairo)                  │
│ Server       │ Apache (with mod_rewrite) on shared hosting     │
│ Dev Server   │ XAMPP / Laragon (local development)             │
│ Version Ctrl │ Git + GitHub                                    │
│ Image Optim  │ PHP GD Library for resize/compress              │
│ Messaging    │ WhatsApp API (wa.me deep links)                 │
└──────────────┴──────────────────────────────────────────────────┘
```

### 4.2 Directory Structure

text

```
matjar_el_kotobia/
│
│── .htaccess                          # Root security & URL rewriting
│── index.php                          # Homepage (entry point)
│── robots.txt                         # SEO crawler instructions
│
├── config/
│   ├── .htaccess                      # Block direct access to config
│   ├── config.php                     # Master config loader
│   ├── constants.php                  # All global constants
│   └── database.php                   # PDO database connection
│
├── includes/
│   ├── .htaccess                      # Block direct access
│   ├── header.php                     # HTML <head> + navbar (customer)
│   ├── footer.php                     # Footer + closing scripts
│   ├── functions.php                  # ALL reusable helper functions
│   ├── auth_check.php                 # Customer auth middleware
│   ├── admin_auth_check.php           # Admin auth middleware
│   ├── cart_functions.php             # Cart-specific helpers
│   ├── order_functions.php            # Order + WhatsApp helpers
│   ├── language_handler.php           # Language detection/switching
│   └── flash_messages.php             # Session-based flash messages
│
├── lang/
│   ├── .htaccess                      # Block direct access
│   ├── fr.php                         # French translations
│   └── ar.php                         # Arabic translations
│
├── assets/
│   ├── css/
│   │   ├── style.css                  # Main custom stylesheet
│   │   ├── rtl.css                    # Arabic RTL layout overrides
│   │   ├── responsive.css             # Additional responsive tweaks
│   │   └── admin.css                  # Admin panel styles
│   ├── js/
│   │   ├── main.js                    # Global frontend JavaScript
│   │   ├── cart.js                    # Cart add/remove/update logic
│   │   ├── checkout.js                # WhatsApp message builder
│   │   ├── search.js                  # AJAX live search
│   │   └── admin.js                   # Admin panel interactions
│   ├── images/
│   │   ├── logo/
│   │   │   └── .gitkeep              # Logo files go here
│   │   ├── banners/
│   │   │   └── .gitkeep              # Homepage banners
│   │   ├── categories/
│   │   │   └── .gitkeep              # Category icons/images
│   │   └── ui/
│   │       └── .gitkeep              # Misc UI graphics
│   └── uploads/
│       └── products/
│           ├── .htaccess              # Block PHP execution in uploads
│           └── default_product.jpg    # Placeholder product image
│
├── pages/
│   ├── products.php                   # Browse all products (paginated)
│   ├── product_detail.php             # Single product view
│   ├── category.php                   # Products filtered by category
│   ├── cart.php                       # Full cart page
│   ├── checkout.php                   # Checkout form + WhatsApp send
│   ├── login.php                      # Customer login form
│   ├── register.php                   # Customer registration form
│   ├── profile.php                    # Customer profile edit
│   ├── order_history.php              # Customer past orders
│   ├── order_detail.php               # Single order detail (customer)
│   ├── search_results.php             # Search results page
│   ├── about.php                      # About the store
│   ├── contact.php                    # Contact / store info
│   ├── switch_language.php            # Language switch handler
│   └── logout.php                     # Customer logout handler
│
├── ajax/
│   ├── search_products.php            # AJAX: live product search
│   ├── cart_add.php                   # AJAX: add item to cart
│   ├── cart_update.php                # AJAX: update item quantity
│   ├── cart_remove.php                # AJAX: remove item from cart
│   ├── cart_count.php                 # AJAX: get cart badge count
│   └── save_order.php                 # AJAX: persist order to DB
│
├── admin/
│   ├── .htaccess                      # Admin-specific rewrite rules
│   ├── index.php                      # Admin dashboard
│   ├── login.php                      # Admin login form
│   ├── logout.php                     # Admin logout handler
│   ├── products.php                   # Product list (admin)
│   ├── product_add.php                # Add new product form
│   ├── product_edit.php               # Edit existing product
│   ├── product_delete.php             # Delete product handler
│   ├── product_toggle.php             # Toggle product active status
│   ├── categories.php                 # Category list (admin)
│   ├── category_add.php               # Add new category
│   ├── category_edit.php              # Edit existing category
│   ├── category_delete.php            # Delete category handler
│   ├── orders.php                     # Order list (admin)
│   ├── order_detail.php               # Single order detail (admin)
│   ├── order_update_status.php        # Change order status handler
│   ├── customers.php                  # Customer list (admin)
│   ├── customer_detail.php            # Single customer info (admin)
│   ├── settings.php                   # Store settings form
│   └── includes/
│       ├── admin_header.php           # Admin HTML head + sidebar
│       └── admin_footer.php           # Admin footer + scripts
│
├── sql/
│   ├── .htaccess                      # Block direct access
│   ├── matjar_el_kotobia.sql          # Full schema + seed data
│   └── sample_products.sql            # Optional demo product data
│
├── errors/
│   ├── 404.php                        # Custom 404 Not Found page
│   └── 500.php                        # Custom 500 Server Error page
│
└── docs/
    ├── PRD.md                         # This Product Requirements Doc
    └── SETUP.md                       # Developer setup instructions
```

### 4.3 Configuration Constants

PHP

```
<?php
/**
 * FILE: config/constants.php
 * PURPOSE: Global constants used throughout the HRI Supermarket application.
 * NOTE: These constant names are PERMANENT. Do not rename them.
 *       Any AI or developer working on this project must use these exact names.
 */

/* ============================================================
 * SITE INFORMATION
 * ============================================================ */
define('SITE_NAME_FR', 'Matjar El Kotobia');
define('SITE_NAME_AR', 'سوبرماركت هري');
define('SITE_TAGLINE_FR', 'Vos courses quotidiennes en ligne');
define('SITE_TAGLINE_AR', 'تسوقك اليومي أونلاين');
define('SITE_URL', 'http://localhost/matjar_el_kotobia'); // Change for production
define('SITE_ROOT', __DIR__ . '/..');

/* ============================================================
 * DEFAULT LOCATION (Local to Safi, Morocco)
 * ============================================================ */
define('DEFAULT_CITY', 'Safi');
define('DEFAULT_COUNTRY', 'Morocco');
define('DEFAULT_CURRENCY', 'DH');
define('DEFAULT_CURRENCY_CODE', 'MAD');

/* ============================================================
 * STORE CONTACT (WhatsApp)
 * ============================================================ */
define('STORE_WHATSAPP_NUMBER', '212600000000'); // Store owner's number
define('STORE_PHONE_DISPLAY', '06 00 00 00 00');
define('WHATSAPP_API_URL', 'https://wa.me/');

/* ============================================================
 * BUSINESS RULES
 * ============================================================ */
define('MINIMUM_ORDER_AMOUNT', 50.00);    // in DH
define('DELIVERY_FEE', 15.00);            // in DH
define('FREE_DELIVERY_THRESHOLD', 200.00); // Free delivery above this

/* ============================================================
 * LANGUAGE SETTINGS
 * ============================================================ */
define('DEFAULT_LANGUAGE', 'fr');
define('SUPPORTED_LANGUAGES', ['fr', 'ar']);
define('LANG_COOKIE_NAME', 'hri_language');
define('LANG_COOKIE_EXPIRY', 365 * 24 * 60 * 60); // 1 year

/* ============================================================
 * SESSION & COOKIE SETTINGS
 * ============================================================ */
define('SESSION_NAME', 'hri_session');
define('CUSTOMER_SESSION_KEY', 'hri_customer_id');
define('ADMIN_SESSION_KEY', 'hri_admin_id');
define('CART_SESSION_KEY', 'hri_cart');
define('REMEMBER_ME_COOKIE', 'hri_remember');
define('CSRF_TOKEN_NAME', 'hri_csrf_token');

/* ============================================================
 * FILE UPLOAD SETTINGS
 * ============================================================ */
define('UPLOAD_DIR', SITE_ROOT . '/assets/uploads/products/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/products/');
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('PRODUCT_IMAGE_WIDTH', 600);  // Resize width in pixels
define('PRODUCT_IMAGE_HEIGHT', 600); // Resize height in pixels
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
?>
```

### 4.4 Language File Structure

PHP

```
<?php
/**
 * FILE: lang/fr.php
 * PURPOSE: French translation strings for HRI Supermarket.
 * VARIABLE: $lang (global translation array)
 * NOTE: All keys are PERMANENT. Add new keys, never rename existing ones.
 */

$lang = [
    /* ---- General ---- */
    'site_name'             => 'Matjar El Kotobia',
    'site_tagline'          => 'Vos courses quotidiennes en ligne',
    'home'                  => 'Accueil',
    'products'              => 'Produits',
    'categories'            => 'Catégories',
    'cart'                  => 'Panier',
    'my_account'            => 'Mon Compte',
    'login'                 => 'Connexion',
    'register'              => 'Inscription',
    'logout'                => 'Déconnexion',
    'search_placeholder'    => 'Rechercher un produit...',
    'language'              => 'Langue',
    'french'                => 'Français',
    'arabic'                => 'العربية',

    /* ---- Product ---- */
    'price'                 => 'Prix',
    'unit_kg'               => 'kg',
    'unit_piece'            => 'pièce',
    'unit_pack'             => 'paquet',
    'unit_liter'            => 'litre',
    'add_to_cart'           => 'Ajouter au panier',
    'added_to_cart'         => 'Ajouté au panier',
    'out_of_stock'          => 'Rupture de stock',
    'on_sale'               => 'En promo',
    'featured'              => 'En vedette',
    'product_details'       => 'Détails du produit',
    'related_products'      => 'Produits similaires',
    'no_products_found'     => 'Aucun produit trouvé.',
    'all_products'          => 'Tous les produits',

    /* ---- Cart ---- */
    'your_cart'             => 'Votre Panier',
    'cart_empty'            => 'Votre panier est vide.',
    'continue_shopping'     => 'Continuer vos achats',
    'quantity'              => 'Quantité',
    'subtotal'              => 'Sous-total',
    'delivery_fee'          => 'Frais de livraison',
    'free_delivery'         => 'Livraison gratuite',
    'total'                 => 'Total',
    'remove'                => 'Supprimer',
    'clear_cart'            => 'Vider le panier',
    'proceed_to_checkout'   => 'Passer à la commande',
    'minimum_order_msg'     => 'Le montant minimum de commande est de',

    /* ---- Checkout ---- */
    'checkout'              => 'Commander',
    'your_information'      => 'Vos Informations',
    'full_name'             => 'Nom complet',
    'phone_number'          => 'Numéro de téléphone',
    'delivery_address'      => 'Adresse de livraison',
    'neighborhood'          => 'Quartier',
    'city'                  => 'Ville',
    'order_notes'           => 'Notes de commande',
    'order_notes_hint'      => 'Instructions spéciales pour la livraison...',
    'save_info'             => 'Sauvegarder mes informations',
    'send_via_whatsapp'     => 'Envoyer via WhatsApp',
    'order_summary'         => 'Résumé de la commande',
    'payment_on_delivery'   => 'Paiement à la livraison',

    /* ---- Auth ---- */
    'email'                 => 'Email',
    'password'              => 'Mot de passe',
    'confirm_password'      => 'Confirmer le mot de passe',
    'remember_me'           => 'Se souvenir de moi',
    'forgot_password'       => 'Mot de passe oublié ?',
    'no_account'            => 'Pas encore de compte ?',
    'have_account'          => 'Déjà un compte ?',
    'register_now'          => 'Inscrivez-vous',
    'login_now'             => 'Connectez-vous',

    /* ---- Profile ---- */
    'profile'               => 'Profil',
    'edit_profile'          => 'Modifier le profil',
    'order_history'         => 'Historique des commandes',
    'save_changes'          => 'Enregistrer',
    'profile_updated'       => 'Profil mis à jour avec succès.',

    /* ---- Order ---- */
    'order_number'          => 'N° de commande',
    'order_date'            => 'Date de commande',
    'order_status'          => 'Statut',
    'order_total'           => 'Total',
    'status_pending'        => 'En attente',
    'status_confirmed'      => 'Confirmée',
    'status_preparing'      => 'En préparation',
    'status_out_delivery'   => 'En livraison',
    'status_delivered'      => 'Livrée',
    'status_cancelled'      => 'Annulée',

    /* ---- Footer ---- */
    'about_us'              => 'À propos',
    'contact_us'            => 'Contactez-nous',
    'follow_us'             => 'Suivez-nous',
    'all_rights_reserved'   => 'Tous droits réservés',
    'delivery_info'         => 'Informations livraison',
    'store_hours'           => 'Horaires du magasin',

    /* ---- Errors ---- */
    'error_required_field'  => 'Ce champ est obligatoire.',
    'error_invalid_phone'   => 'Numéro de téléphone invalide.',
    'error_invalid_email'   => 'Email invalide.',
    'error_password_short'  => 'Le mot de passe doit contenir au moins 6 caractères.',
    'error_password_match'  => 'Les mots de passe ne correspondent pas.',
    'error_login_failed'    => 'Téléphone ou mot de passe incorrect.',
    'error_phone_exists'    => 'Ce numéro de téléphone est déjà utilisé.',
    'error_general'         => 'Une erreur est survenue. Veuillez réessayer.',
];
?>
```

PHP

```
<?php
/**
 * FILE: lang/ar.php
 * PURPOSE: Arabic translation strings for HRI Supermarket.
 * VARIABLE: $lang (global translation array)
 * NOTE: All keys are PERMANENT. Same keys as fr.php. Arabic values only.
 */

$lang = [
    /* ---- عام ---- */
    'site_name'             => 'سوبرماركت هري',
    'site_tagline'          => 'تسوقك اليومي أونلاين',
    'home'                  => 'الرئيسية',
    'products'              => 'المنتجات',
    'categories'            => 'الأقسام',
    'cart'                  => 'السلة',
    'my_account'            => 'حسابي',
    'login'                 => 'تسجيل الدخول',
    'register'              => 'إنشاء حساب',
    'logout'                => 'تسجيل الخروج',
    'search_placeholder'    => 'ابحث عن منتج...',
    'language'              => 'اللغة',
    'french'                => 'Français',
    'arabic'                => 'العربية',

    /* ---- المنتج ---- */
    'price'                 => 'السعر',
    'unit_kg'               => 'كلغ',
    'unit_piece'            => 'قطعة',
    'unit_pack'             => 'علبة',
    'unit_liter'            => 'لتر',
    'add_to_cart'           => 'أضف إلى السلة',
    'added_to_cart'         => 'تمت الإضافة',
    'out_of_stock'          => 'نفذ المخزون',
    'on_sale'               => 'عرض خاص',
    'featured'              => 'منتج مميز',
    'product_details'       => 'تفاصيل المنتج',
    'related_products'      => 'منتجات مشابهة',
    'no_products_found'     => 'لم يتم العثور على منتجات.',
    'all_products'          => 'جميع المنتجات',

    /* ---- السلة ---- */
    'your_cart'             => 'سلة مشترياتك',
    'cart_empty'            => 'سلتك فارغة.',
    'continue_shopping'     => 'متابعة التسوق',
    'quantity'              => 'الكمية',
    'subtotal'              => 'المجموع الفرعي',
    'delivery_fee'          => 'رسوم التوصيل',
    'free_delivery'         => 'توصيل مجاني',
    'total'                 => 'الإجمالي',
    'remove'                => 'حذف',
    'clear_cart'            => 'إفراغ السلة',
    'proceed_to_checkout'   => 'إتمام الطلب',
    'minimum_order_msg'     => 'الحد الأدنى للطلب هو',

    /* ---- الدفع ---- */
    'checkout'              => 'إتمام الطلب',
    'your_information'      => 'معلوماتك',
    'full_name'             => 'الاسم الكامل',
    'phone_number'          => 'رقم الهاتف',
    'delivery_address'      => 'عنوان التوصيل',
    'neighborhood'          => 'الحي',
    'city'                  => 'المدينة',
    'order_notes'           => 'ملاحظات الطلب',
    'order_notes_hint'      => 'تعليمات خاصة للتوصيل...',
    'save_info'             => 'حفظ معلوماتي',
    'send_via_whatsapp'     => 'إرسال عبر واتساب',
    'order_summary'         => 'ملخص الطلب',
    'payment_on_delivery'   => 'الدفع عند الاستلام',

    /* ---- المصادقة ---- */
    'email'                 => 'البريد الإلكتروني',
    'password'              => 'كلمة المرور',
    'confirm_password'      => 'تأكيد كلمة المرور',
    'remember_me'           => 'تذكرني',
    'forgot_password'       => 'نسيت كلمة المرور؟',
    'no_account'            => 'ليس لديك حساب؟',
    'have_account'          => 'لديك حساب بالفعل؟',
    'register_now'          => 'سجل الآن',
    'login_now'             => 'سجل دخولك',

    /* ---- الملف الشخصي ---- */
    'profile'               => 'الملف الشخصي',
    'edit_profile'          => 'تعديل الملف',
    'order_history'         => 'سجل الطلبات',
    'save_changes'          => 'حفظ',
    'profile_updated'       => 'تم تحديث الملف الشخصي بنجاح.',

    /* ---- الطلب ---- */
    'order_number'          => 'رقم الطلب',
    'order_date'            => 'تاريخ الطلب',
    'order_status'          => 'الحالة',
    'order_total'           => 'الإجمالي',
    'status_pending'        => 'قيد الانتظار',
    'status_confirmed'      => 'مؤكد',
    'status_preparing'      => 'قيد التحضير',
    'status_out_delivery'   => 'في الطريق',
    'status_delivered'      => 'تم التوصيل',
    'status_cancelled'      => 'ملغي',

    /* ---- Footer ---- */
    'about_us'              => 'من نحن',
    'contact_us'            => 'اتصل بنا',
    'follow_us'             => 'تابعنا',
    'all_rights_reserved'   => 'جميع الحقوق محفوظة',
    'delivery_info'         => 'معلومات التوصيل',
    'store_hours'           => 'أوقات العمل',

    /* ---- أخطاء ---- */
    'error_required_field'  => 'هذا الحقل مطلوب.',
    'error_invalid_phone'   => 'رقم الهاتف غير صالح.',
    'error_invalid_email'   => 'البريد الإلكتروني غير صالح.',
    'error_password_short'  => 'يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.',
    'error_password_match'  => 'كلمات المرور غير متطابقة.',
    'error_login_failed'    => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
    'error_phone_exists'    => 'هذا الرقم مستخدم بالفعل.',
    'error_general'         => 'حدث خطأ. يرجى المحاولة مرة أخرى.',
];
?>
```

---

## 5. Database Design

### 5.1 Entity Relationship Overview

text

```
┌─────────────┐     ┌──────────────┐     ┌─────────────────┐
│   hri_admin  │     │ hri_customer │────<│   hri_order     │
└─────────────┘     └──────────────┘     │                 │
                                          │  order_id (PK)  │
┌──────────────┐     ┌──────────────┐     │  customer_id    │
│ hri_category │────<│ hri_product  │     │  order_number   │
│              │     │              │     │  ...            │
│ category_id  │     │ product_id   │     └────────┬────────┘
│ name_fr      │     │ name_fr      │              │
│ name_ar      │     │ name_ar      │              │has many
│ ...          │     │ category_id  │              │
└──────────────┘     │ price        │     ┌────────▼────────┐
                     │ ...          │────<│ hri_order_item  │
                     └──────────────┘     │                 │
                                          │ order_item_id   │
                     ┌──────────────┐     │ order_id        │
                     │hri_settings  │     │ product_id      │
                     │              │     │ quantity         │
                     │ setting_key  │     │ unit_price       │
                     │ setting_value│     └─────────────────┘
                     └──────────────┘
```

### 5.2 Full Database Schema (SQL)

SQL

```
-- ================================================================
-- FILE: sql/matjar_el_kotobia.sql
-- PURPOSE: Complete database schema for HRI Supermarket
-- DATABASE NAME: matjar_el_kotobia_db
-- CHARSET: utf8mb4 (supports Arabic characters)
-- PREFIX: hri_ (all tables use this prefix)
-- NOTE: Table and column names are PERMANENT. Do not rename.
-- ================================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `matjar_el_kotobia_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `matjar_el_kotobia_db`;

-- ================================================================
-- TABLE: hri_admin
-- PURPOSE: Store admin user accounts
-- ================================================================
CREATE TABLE `hri_admin` (
    `admin_id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_username`    VARCHAR(50)  NOT NULL UNIQUE
        COMMENT 'Admin login username',
    `admin_email`       VARCHAR(100) NOT NULL UNIQUE
        COMMENT 'Admin email address',
    `admin_password`    VARCHAR(255) NOT NULL
        COMMENT 'Bcrypt hashed password',
    `admin_full_name`   VARCHAR(100) NOT NULL
        COMMENT 'Admin display name',
    `admin_role`        ENUM('super_admin', 'manager') DEFAULT 'manager'
        COMMENT 'Admin role level',
    `admin_is_active`   TINYINT(1)   DEFAULT 1
        COMMENT '1=active, 0=disabled',
    `admin_last_login`  DATETIME     DEFAULT NULL
        COMMENT 'Last login timestamp',
    `admin_created_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `admin_updated_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_admin_username` (`admin_username`),
    INDEX `idx_admin_email` (`admin_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Admin user accounts for HRI Supermarket';


-- ================================================================
-- TABLE: hri_customer
-- PURPOSE: Store registered customer accounts
-- ================================================================
CREATE TABLE `hri_customer` (
    `customer_id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_full_name`        VARCHAR(100) NOT NULL
        COMMENT 'Customer full name',
    `customer_phone`            VARCHAR(20)  NOT NULL UNIQUE
        COMMENT 'Phone number (Moroccan format, e.g., 0612345678)',
    `customer_email`            VARCHAR(100) DEFAULT NULL
        COMMENT 'Optional email address',
    `customer_password`         VARCHAR(255) NOT NULL
        COMMENT 'Bcrypt hashed password',
    `customer_address`          VARCHAR(255) DEFAULT NULL
        COMMENT 'Full delivery address',
    `customer_neighborhood`     VARCHAR(100) DEFAULT NULL
        COMMENT 'Quartier / neighborhood name',
    `customer_city`             VARCHAR(50)  DEFAULT 'Safi'
        COMMENT 'City name, default Safi',
    `customer_notes`            TEXT         DEFAULT NULL
        COMMENT 'Default delivery notes/instructions',
    `customer_preferred_lang`   ENUM('fr', 'ar') DEFAULT 'fr'
        COMMENT 'Preferred language',
    `customer_is_active`        TINYINT(1)   DEFAULT 1
        COMMENT '1=active, 0=disabled',
    `customer_remember_token`   VARCHAR(255) DEFAULT NULL
        COMMENT 'Remember me token',
    `customer_last_login`       DATETIME     DEFAULT NULL,
    `customer_created_at`       DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `customer_updated_at`       DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_customer_phone` (`customer_phone`),
    INDEX `idx_customer_email` (`customer_email`),
    INDEX `idx_customer_city` (`customer_city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registered customer accounts';


-- ================================================================
-- TABLE: hri_category
-- PURPOSE: Product categories
-- ================================================================
CREATE TABLE `hri_category` (
    `category_id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_name_fr`      VARCHAR(100) NOT NULL
        COMMENT 'Category name in French',
    `category_name_ar`      VARCHAR(100) NOT NULL
        COMMENT 'Category name in Arabic',
    `category_slug`         VARCHAR(120) NOT NULL UNIQUE
        COMMENT 'URL-safe slug (e.g., fruits-legumes)',
    `category_icon`         VARCHAR(100) DEFAULT NULL
        COMMENT 'Icon class (e.g., bi-apple) or image filename',
    `category_image`        VARCHAR(255) DEFAULT NULL
        COMMENT 'Category image filename',
    `category_description_fr` TEXT       DEFAULT NULL,
    `category_description_ar` TEXT       DEFAULT NULL,
    `category_display_order`  INT        DEFAULT 0
        COMMENT 'Sort order for display',
    `category_is_active`    TINYINT(1)   DEFAULT 1
        COMMENT '1=visible, 0=hidden',
    `category_created_at`   DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `category_updated_at`   DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_category_slug` (`category_slug`),
    INDEX `idx_category_active_order` (`category_is_active`, `category_display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Product categories';


-- ================================================================
-- TABLE: hri_product
-- PURPOSE: Store products catalog
-- ================================================================
CREATE TABLE `hri_product` (
    `product_id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_category_id`   INT UNSIGNED NOT NULL
        COMMENT 'FK to hri_category',
    `product_name_fr`       VARCHAR(200) NOT NULL
        COMMENT 'Product name in French',
    `product_name_ar`       VARCHAR(200) NOT NULL
        COMMENT 'Product name in Arabic',
    `product_slug`          VARCHAR(220) NOT NULL UNIQUE
        COMMENT 'URL-safe slug',
    `product_description_fr` TEXT        DEFAULT NULL
        COMMENT 'Description in French',
    `product_description_ar` TEXT        DEFAULT NULL
        COMMENT 'Description in Arabic',
    `product_price`         DECIMAL(10,2) NOT NULL
        COMMENT 'Regular price in DH',
    `product_sale_price`    DECIMAL(10,2) DEFAULT NULL
        COMMENT 'Sale/discounted price in DH (NULL = no sale)',
    `product_unit`          ENUM('kg', 'piece', 'pack', 'liter', 'gram', 'unit') DEFAULT 'piece'
        COMMENT 'Unit of measure',
    `product_unit_step`     DECIMAL(5,2)  DEFAULT 1.00
        COMMENT 'Quantity increment step (e.g., 0.5 for half kg)',
    `product_stock_quantity` INT          DEFAULT 0
        COMMENT 'Available stock quantity',
    `product_image`         VARCHAR(255) DEFAULT 'default_product.jpg'
        COMMENT 'Main product image filename',
    `product_image_thumb`   VARCHAR(255) DEFAULT NULL
        COMMENT 'Thumbnail image filename',
    `product_is_featured`   TINYINT(1)   DEFAULT 0
        COMMENT '1=show on homepage featured section',
    `product_is_on_sale`    TINYINT(1)   DEFAULT 0
        COMMENT '1=show sale badge',
    `product_is_active`     TINYINT(1)   DEFAULT 1
        COMMENT '1=visible, 0=hidden',
    `product_view_count`    INT UNSIGNED DEFAULT 0
        COMMENT 'Number of views (basic analytics)',
    `product_sort_order`    INT          DEFAULT 0
        COMMENT 'Custom sort order within category',
    `product_created_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `product_updated_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (`product_category_id`)
        REFERENCES `hri_category`(`category_id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX `idx_product_category` (`product_category_id`),
    INDEX `idx_product_slug` (`product_slug`),
    INDEX `idx_product_active` (`product_is_active`),
    INDEX `idx_product_featured` (`product_is_featured`, `product_is_active`),
    INDEX `idx_product_sale` (`product_is_on_sale`, `product_is_active`),
    INDEX `idx_product_price` (`product_price`),
    FULLTEXT INDEX `idx_product_search` (`product_name_fr`, `product_name_ar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Product catalog';


-- ================================================================
-- TABLE: hri_order
-- PURPOSE: Store customer orders
-- ================================================================
CREATE TABLE `hri_order` (
    `order_id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number`          VARCHAR(30)  NOT NULL UNIQUE
        COMMENT 'Human-readable order number (e.g., HRI-20250101-001)',
    `order_customer_id`     INT UNSIGNED DEFAULT NULL
        COMMENT 'FK to hri_customer (NULL for guest orders)',
    `order_customer_name`   VARCHAR(100) NOT NULL
        COMMENT 'Customer name at time of order',
    `order_customer_phone`  VARCHAR(20)  NOT NULL
        COMMENT 'Customer phone at time of order',
    `order_customer_address` VARCHAR(255) NOT NULL
        COMMENT 'Delivery address at time of order',
    `order_customer_neighborhood` VARCHAR(100) DEFAULT NULL,
    `order_customer_city`   VARCHAR(50)  DEFAULT 'Safi',
    `order_notes`           TEXT         DEFAULT NULL
        COMMENT 'Customer delivery notes',
    `order_subtotal`        DECIMAL(10,2) NOT NULL
        COMMENT 'Sum of items before delivery fee',
    `order_delivery_fee`    DECIMAL(10,2) DEFAULT 0.00
        COMMENT 'Delivery fee charged',
    `order_total`           DECIMAL(10,2) NOT NULL
        COMMENT 'Grand total (subtotal + delivery)',
    `order_item_count`      INT UNSIGNED DEFAULT 0
        COMMENT 'Total number of items',
    `order_status`          ENUM('pending','confirmed','preparing',
                                 'out_for_delivery','delivered','cancelled')
                            DEFAULT 'pending'
        COMMENT 'Current order status',
    `order_payment_method`  VARCHAR(30)  DEFAULT 'cod'
        COMMENT 'Payment method (cod = cash on delivery)',
    `order_whatsapp_sent`   TINYINT(1)   DEFAULT 0
        COMMENT '1=WhatsApp message was opened/sent',
    `order_language`        ENUM('fr', 'ar') DEFAULT 'fr'
        COMMENT 'Language used for this order',
    `order_ip_address`      VARCHAR(45)  DEFAULT NULL
        COMMENT 'Customer IP for security logging',
    `order_created_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `order_updated_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `order_delivered_at`    DATETIME     DEFAULT NULL
        COMMENT 'When order was marked delivered',

    FOREIGN KEY (`order_customer_id`)
        REFERENCES `hri_customer`(`customer_id`)
        ON DELETE SET NULL ON UPDATE CASCADE,

    INDEX `idx_order_number` (`order_number`),
    INDEX `idx_order_customer` (`order_customer_id`),
    INDEX `idx_order_status` (`order_status`),
    INDEX `idx_order_date` (`order_created_at`),
    INDEX `idx_order_status_date` (`order_status`, `order_created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Customer orders';


-- ================================================================
-- TABLE: hri_order_item
-- PURPOSE: Individual items within an order
-- ================================================================
CREATE TABLE `hri_order_item` (
    `order_item_id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_item_order_id`   INT UNSIGNED NOT NULL
        COMMENT 'FK to hri_order',
    `order_item_product_id` INT UNSIGNED DEFAULT NULL
        COMMENT 'FK to hri_product (NULL if product deleted)',
    `order_item_name_fr`    VARCHAR(200) NOT NULL
        COMMENT 'Product name FR at time of order (snapshot)',
    `order_item_name_ar`    VARCHAR(200) NOT NULL
        COMMENT 'Product name AR at time of order (snapshot)',
    `order_item_unit_price` DECIMAL(10,2) NOT NULL
        COMMENT 'Price per unit at time of order',
    `order_item_quantity`   DECIMAL(10,2) NOT NULL DEFAULT 1
        COMMENT 'Quantity ordered',
    `order_item_unit`       VARCHAR(20)  NOT NULL DEFAULT 'piece'
        COMMENT 'Unit of measure at time of order',
    `order_item_subtotal`   DECIMAL(10,2) NOT NULL
        COMMENT 'unit_price * quantity',

    FOREIGN KEY (`order_item_order_id`)
        REFERENCES `hri_order`(`order_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`order_item_product_id`)
        REFERENCES `hri_product`(`product_id`)
        ON DELETE SET NULL ON UPDATE CASCADE,

    INDEX `idx_order_item_order` (`order_item_order_id`),
    INDEX `idx_order_item_product` (`order_item_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Individual items within an order';


-- ================================================================
-- TABLE: hri_settings
-- PURPOSE: Key-value store for site configuration
-- ================================================================
CREATE TABLE `hri_settings` (
    `setting_id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key`           VARCHAR(100) NOT NULL UNIQUE
        COMMENT 'Setting identifier (permanent key name)',
    `setting_value`         TEXT         DEFAULT NULL
        COMMENT 'Setting value',
    `setting_type`          ENUM('text','number','boolean','json') DEFAULT 'text'
        COMMENT 'Value type for validation',
    `setting_group`         VARCHAR(50)  DEFAULT 'general'
        COMMENT 'Settings group for admin UI organization',
    `setting_label_fr`      VARCHAR(150) DEFAULT NULL
        COMMENT 'Human-readable label in French',
    `setting_label_ar`      VARCHAR(150) DEFAULT NULL
        COMMENT 'Human-readable label in Arabic',
    `setting_updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_setting_key` (`setting_key`),
    INDEX `idx_setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Site-wide configuration settings';


-- ================================================================
-- TABLE: hri_guest_info
-- PURPOSE: Save guest checkout info for repeat purchases
-- ================================================================
CREATE TABLE `hri_guest_info` (
    `guest_id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `guest_phone`           VARCHAR(20)  NOT NULL UNIQUE
        COMMENT 'Phone used as guest identifier',
    `guest_full_name`       VARCHAR(100) DEFAULT NULL,
    `guest_address`         VARCHAR(255) DEFAULT NULL,
    `guest_neighborhood`    VARCHAR(100) DEFAULT NULL,
    `guest_city`            VARCHAR(50)  DEFAULT 'Safi',
    `guest_notes`           TEXT         DEFAULT NULL,
    `guest_created_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    `guest_updated_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_guest_phone` (`guest_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Saved info for guest (non-registered) customers';


-- ================================================================
-- SEED DATA: Default admin account
-- ================================================================
INSERT INTO `hri_admin` (
    `admin_username`, `admin_email`, `admin_password`,
    `admin_full_name`, `admin_role`
) VALUES (
    'admin',
    'admin@hrisupermarket.ma',
    '$2y$10$placeholder_hash_replace_on_setup',  -- MUST be replaced with real bcrypt hash
    'Administrateur HRI',
    'super_admin'
);


-- ================================================================
-- SEED DATA: Default store settings
-- ================================================================
INSERT INTO `hri_settings` (`setting_key`, `setting_value`, `setting_type`, `setting_group`, `setting_label_fr`, `setting_label_ar`) VALUES
('store_name_fr',           'HRI Supermarket',      'text',    'general',  'Nom du magasin (FR)',         'اسم المتجر (فرنسية)'),
('store_name_ar',           'سوبرماركت هري',        'text',    'general',  'Nom du magasin (AR)',         'اسم المتجر (عربية)'),
('store_phone',             '0600000000',            'text',    'contact',  'Téléphone du magasin',        'هاتف المتجر'),
('store_whatsapp',          '212600000000',          'text',    'contact',  'Numéro WhatsApp',             'رقم واتساب'),
('store_email',             'contact@hri.ma',        'text',    'contact',  'Email du magasin',            'بريد المتجر'),
('store_address',           'Safi, Morocco',         'text',    'contact',  'Adresse du magasin',          'عنوان المتجر'),
('store_city',              'Safi',                  'text',    'contact',  'Ville',                       'المدينة'),
('minimum_order_amount',    '50',                    'number',  'orders',   'Montant minimum commande',    'الحد الأدنى للطلب'),
('delivery_fee',            '10',                    'number',  'orders',   'Frais de livraison',          'رسوم التوصيل'),
('free_delivery_threshold', '200',                   'number',  'orders',   'Seuil livraison gratuite',    'حد التوصيل المجاني'),
('store_hours_fr',          'Lun-Sam: 8h-22h',      'text',    'general',  'Horaires (FR)',               'أوقات العمل (فرنسية)'),
('store_hours_ar',          'الإثنين-السبت: 8-22',  'text',    'general',  'Horaires (AR)',               'أوقات العمل (عربية)'),
('maintenance_mode',        '0',                     'boolean', 'general',  'Mode maintenance',            'وضع الصيانة'),
('default_language',        'fr',                    'text',    'general',  'Langue par défaut',           'اللغة الافتراضية');


-- ================================================================
-- SEED DATA: Sample categories
-- ================================================================
INSERT INTO `hri_category` (
    `category_name_fr`, `category_name_ar`, `category_slug`,
    `category_icon`, `category_display_order`
) VALUES
('Fruits & Légumes',    'فواكه وخضروات',     'fruits-legumes',      'bi-apple',      1),
('Viandes & Volailles', 'لحوم ودواجن',       'viandes-volailles',   'bi-egg-fried',  2),
('Poissons',            'أسماك',             'poissons',            'bi-water',      3),
('Produits Laitiers',   'منتجات الحليب',     'produits-laitiers',   'bi-cup-straw',  4),
('Boulangerie',         'مخبوزات',           'boulangerie',         'bi-basket',     5),
('Épicerie',            'بقالة',             'epicerie',            'bi-box',        6),
('Boissons',            'مشروبات',           'boissons',            'bi-droplet',    7),
('Surgelés',            'مجمدات',            'surgeles',            'bi-snow',       8),
('Hygiène & Beauté',    'نظافة وجمال',       'hygiene-beaute',      'bi-stars',      9),
('Bébé',                'مستلزمات الأطفال',   'bebe',                'bi-balloon',   10),
('Ménage',              'مستلزمات المنزل',    'menage',              'bi-house',     11),
('Conserves',           'معلبات',            'conserves',           'bi-archive',   12);
```

### 5.3 Database Variable Naming Convention Summary

text

```
TABLE NAMING:
  Prefix: hri_
  Format: hri_{entity_name}    (singular)
  Examples: hri_product, hri_order, hri_customer

COLUMN NAMING:
  Prefix: {entity_name}_
  Format: {entity}_{descriptor}
  Examples:
    product_id, product_name_fr, product_price
    order_id, order_number, order_status
    customer_id, customer_full_name, customer_phone

  Special suffixes:
    _fr        → French language field
    _ar        → Arabic language field
    _id        → Primary key or foreign key
    _is_*      → Boolean flag (e.g., product_is_active)
    _at        → Timestamp (e.g., order_created_at)
    _count     → Counter field

FOREIGN KEYS:
  Format: {child_entity}_{parent_entity}_id
  Examples:
    product_category_id      (product → category)
    order_customer_id        (order → customer)
    order_item_order_id      (order_item → order)
    order_item_product_id    (order_item → product)
```

---

## 6. Variable Naming Convention & Code Standards

### 6.1 Universal Naming Rules

text

```
╔══════════════════════════════════════════════════════════════════╗
║         PERMANENT NAMING CONVENTION — ALL AI MUST FOLLOW       ║
╠══════════════════════════════════════════════════════════════════╣
║                                                                  ║
║  PURPOSE: Ensure consistency across all developers and AI        ║
║  assistants working on this project. These names are FINAL.      ║
║                                                                  ║
║  GENERAL RULES:                                                  ║
║  1. Use snake_case for PHP variables, functions, DB columns      ║
║  2. Use camelCase for JavaScript variables and functions          ║
║  3. Use kebab-case for CSS classes and IDs                       ║
║  4. Use UPPER_SNAKE_CASE for PHP constants                       ║
║  5. Prefix all DB tables with 'hri_'                             ║
║  6. Prefix all CSS custom properties with '--hri-' or semantic   ║
║  7. Prefix all JS global variables with 'hri'                   ║
║  8. All names must be in English (values can be FR/AR)           ║
║  9. Always add comments explaining purpose                       ║
║                                                                  ║
╚══════════════════════════════════════════════════════════════════╝
```

### 6.2 PHP Naming

PHP

```
/* ================================================================
 * PHP VARIABLES (snake_case)
 * ================================================================ */

// Database connection
$db_connection          // PDO connection object
$db_host                // Database host
$db_name                // Database name
$db_user                // Database username
$db_password            // Database password

// User data
$customer_data          // Array of customer info from DB
$customer_id            // Current logged-in customer ID
$admin_data             // Array of admin info from DB
$admin_id               // Current logged-in admin ID

// Product data
$product_list           // Array of products
$product_data           // Single product array
$product_id             // Current product ID
$category_list          // Array of categories
$category_data          // Single category array

// Cart
$cart_items             // Array of cart items
$cart_total             // Cart total amount
$cart_item_count        // Number of items in cart
$cart_subtotal          // Cart subtotal before delivery

// Order
$order_data             // Single order array
$order_list             // Array of orders
$order_items            // Array of items in an order
$order_number           // Generated order number string
$order_whatsapp_message // Formatted WhatsApp message string

// Language
$current_language       // Current language code ('fr' or 'ar')
$lang                   // Translation array
$is_rtl                 // Boolean: is current language RTL

// Pagination
$current_page           // Current page number
$total_pages            // Total number of pages
$items_per_page         // Items per page count
$total_items            // Total items count

// Form data
$form_errors            // Array of validation errors
$form_success           // Success message string
$form_data              // Sanitized form input array

// Search
$search_query           // User's search input
$search_results         // Array of search results


/* ================================================================
 * PHP FUNCTIONS (snake_case, verb_noun format)
 * ================================================================ */

// Database helpers
function get_db_connection()        // Returns PDO connection
function execute_query($sql, $params) // Executes prepared statement

// Product functions
function get_all_products($page, $per_page)
function get_product_by_id($product_id)
function get_product_by_slug($product_slug)
function get_products_by_category($category_id, $page)
function get_featured_products($limit)
function get_sale_products($limit)
function search_products($search_query)
function get_product_effective_price($product_data) // returns sale or regular price

// Category functions
function get_all_categories()
function get_active_categories()
function get_category_by_id($category_id)
function get_category_by_slug($category_slug)

// Customer functions
function get_customer_by_id($customer_id)
function get_customer_by_phone($customer_phone)
function create_customer($customer_data)
function update_customer($customer_id, $customer_data)
function verify_customer_password($customer_phone, $password)

// Cart functions
function get_cart_items()
function add_to_cart($product_id, $quantity)
function update_cart_quantity($product_id, $quantity)
function remove_from_cart($product_id)
function clear_cart()
function calculate_cart_subtotal($cart_items)
function calculate_delivery_fee($subtotal)
function calculate_cart_total($subtotal, $delivery_fee)

// Order functions
function create_order($order_data, $order_items)
function generate_order_number()
function get_order_by_id($order_id)
function get_order_by_number($order_number)
function get_orders_by_customer($customer_id)
function update_order_status($order_id, $new_status)
function build_whatsapp_message($order_data, $order_items)
function build_whatsapp_url($phone_number, $message)

// Auth functions
function login_customer($customer_phone, $password)
function logout_customer()
function is_customer_logged_in()
function get_current_customer_id()
function login_admin($username, $password)
function logout_admin()
function is_admin_logged_in()
function get_current_admin_id()

// Language functions
function get_current_language()
function set_language($language_code)
function translate($key)           // Shortcut: returns $lang[$key]
function get_direction()           // Returns 'rtl' or 'ltr'

// Utility functions
function sanitize_input($input)
function validate_phone_morocco($phone)
function validate_email($email)
function generate_csrf_token()
function verify_csrf_token($token)
function format_price($amount)     // Returns "XX.XX DH"
function generate_slug($text)
function redirect($url)
function set_flash_message($type, $message)
function get_flash_message()
function resize_image($source, $destination, $width, $height)

// Settings functions
function get_setting($setting_key)
function update_setting($setting_key, $setting_value)
function get_all_settings()


/* ================================================================
 * PHP CONSTANTS (UPPER_SNAKE_CASE — see config/constants.php)
 * ================================================================ */
// All defined in Section 4.3 above
```

### 6.3 JavaScript Naming

JavaScript

```
/* ================================================================
 * JAVASCRIPT VARIABLES (camelCase, prefixed with 'hri' for globals)
 * ================================================================ */

// Global namespace object
const HriApp = {
    siteUrl: '',
    ajaxUrl: '',
    currentLanguage: 'fr',
    isRtl: false,
    currency: 'DH',
    whatsappNumber: '',
    minimumOrderAmount: 50,
    deliveryFee: 10,
    freeDeliveryThreshold: 200,
};

// Cart variables
let hriCartItems = [];          // Array of cart item objects
let hriCartCount = 0;           // Total items in cart
let hriCartSubtotal = 0;        // Cart subtotal
let hriCartTotal = 0;           // Cart total with delivery

// DOM element references (prefixed with 'el')
const elCartBadge = document.getElementById('cart-badge');
const elCartDrawer = document.getElementById('cart-drawer');
const elCartItemsList = document.getElementById('cart-items-list');
const elCartSubtotalDisplay = document.getElementById('cart-subtotal-display');
const elCartTotalDisplay = document.getElementById('cart-total-display');
const elSearchInput = document.getElementById('search-input');
const elSearchResults = document.getElementById('search-results');

/* ================================================================
 * JAVASCRIPT FUNCTIONS (camelCase, verb-first)
 * ================================================================ */

// Cart functions
function addToCart(productId, quantity) {}
function removeFromCart(productId) {}
function updateCartQuantity(productId, newQuantity) {}
function clearCart() {}
function renderCartItems() {}
function updateCartBadge() {}
function calculateCartTotals() {}
function saveCartToStorage() {}
function loadCartFromStorage() {}

// Checkout functions
function buildWhatsappMessage() {}
function openWhatsappCheckout() {}
function validateCheckoutForm() {}
function saveCustomerInfo() {}
function loadSavedCustomerInfo() {}
function formatOrderForWhatsapp(orderData) {}

// Search functions
function performSearch(query) {}
function renderSearchResults(results) {}
function debounceSearch(func, delay) {}

// UI functions
function showToast(message, type) {}
function toggleCartDrawer() {}
function switchLanguage(langCode) {}
function showLoadingSpinner() {}
function hideLoadingSpinner() {}

// Utility functions
function formatPrice(amount) {}
function sanitizeInput(input) {}
function makeAjaxRequest(url, method, data) {}
```

### 6.4 CSS Naming

CSS

```
/* ================================================================
 * CSS NAMING CONVENTION (BEM-inspired with 'hri-' prefix for custom)
 * ================================================================ */

/* Layout */
.hri-header {}
.hri-navbar {}
.hri-footer {}
.hri-main-content {}
.hri-sidebar {}
.hri-mobile-nav {}

/* Components */
.hri-product-card {}
.hri-product-card__image {}
.hri-product-card__title {}
.hri-product-card__price {}
.hri-product-card__price--sale {}
.hri-product-card__badge {}
.hri-product-card__badge--sale {}
.hri-product-card__badge--featured {}
.hri-product-card__btn-cart {}

.hri-category-bar {}
.hri-category-bar__item {}
.hri-category-bar__item--active {}
.hri-category-bar__icon {}
.hri-category-bar__label {}

.hri-cart-drawer {}
.hri-cart-drawer--open {}
.hri-cart-item {}
.hri-cart-item__image {}
.hri-cart-item__info {}
.hri-cart-item__qty {}
.hri-cart-item__price {}
.hri-cart-item__remove {}

.hri-checkout-form {}
.hri-checkout-summary {}
.hri-whatsapp-btn {}

.hri-search-box {}
.hri-search-results {}
.hri-search-result-item {}

.hri-hero-banner {}
.hri-section-title {}
.hri-lang-switcher {}
.hri-toast {}
.hri-toast--success {}
.hri-toast--error {}

/* State modifiers */
.is-active {}
.is-loading {}
.is-hidden {}
.is-rtl {}

/* Admin panel */
.hri-admin-sidebar {}
.hri-admin-header {}
.hri-admin-card {}
.hri-admin-table {}
.hri-admin-form {}
```

### 6.5 Code Commenting Standard

PHP

```
<?php
/**
 * FILE: pages/products.php
 * PURPOSE: Display paginated list of all active products with filters
 * AUTHOR: Matjar El Kotobia Dev Team
 * CREATED: 2026-03-08
 * LAST MODIFIED: 2026-03-08
 *
 * DEPENDENCIES:
 *   - config/database.php (DB connection)
 *   - includes/functions.php (helper functions)
 *   - lang/{language}.php (translations)
 *
 * URL PARAMS:
 *   - ?page=1          (pagination)
 *   - ?category=slug   (filter by category)
 *   - ?sort=price_asc  (sort order)
 */

// Include required files
require_once '../config/database.php';    // Database connection
require_once '../includes/functions.php'; // Helper functions

/* ----------------------------------------------------------------
 * SECTION: Get and validate URL parameters
 * ---------------------------------------------------------------- */
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// ... more code with inline comments explaining logic
?>
```

---

## 7. Page Flows

### 7.1 Customer Flow — Browse to WhatsApp Checkout

text

```
┌──────────────────────────────────────────────────────────────────────┐
│                    CUSTOMER PURCHASE FLOW                            │
└──────────────────────────────────────────────────────────────────────┘

  ┌─────────┐     ┌──────────┐     ┌───────────┐
  │  ENTRY  │────>│ HOMEPAGE │────>│ BROWSE    │
  │ POINTS  │     │          │     │ PRODUCTS  │
  └─────────┘     └──────────┘     └─────┬─────┘
    │                                     │
    │ Direct URL                          │ Click product
    │ Search                              ▼
    │ Category link              ┌───────────────┐
    │                            │ PRODUCT DETAIL │
    │                            │ (view info)    │
    │                            └───────┬───────┘
    │                                    │
    │                                    │ Click "Add to Cart"
    │                                    ▼
    │                            ┌───────────────┐
    ├───────────────────────────>│  CART VIEW    │<── Adjust qty
    │                            │  (review)     │<── Remove items
    │                            └───────┬───────┘
    │                                    │
    │                                    │ Click "Proceed to Checkout"
    │                                    │
    │                                    │ Cart >= Minimum?
    │                                    │
    │                               NO ──┤── YES
    │                                │   │
    │                                │   ▼
    │                                │  ┌───────────────────────┐
    │                   Show error   │  │   CHECKOUT FORM       │
    │                   message      │  │                       │
    │                                │  │  ┌─ Full Name *      │
    │                                │  │  ├─ Phone *          │
    │                                │  │  ├─ Address *        │
    │                                │  │  ├─ Neighborhood     │
    │                                │  │  ├─ City (Safi) 🔒  │
    │                                │  │  ├─ Notes           │
    │                                │  │  └─ Save Info ☐     │
    │                                │  │                       │
    │                                │  │  [Order Summary]      │
    │                                │  │  Item list + totals   │
    │                                │  │                       │
    │                                │  │  [📱 Send via         │
    │                                │  │   WhatsApp]           │
    │                                │  └───────────┬───────────┘
    │                                │              │
    │                                │              │ Click WhatsApp button
    │                                │              │
    │                                │              ▼
    │                                │     ┌────────────────────┐
    │                                │     │ 1. Save order to   │
    │                                │     │    database        │
    │                                │     │ 2. Save customer   │
    │                                │     │    info (if opted) │
    │                                │     │ 3. Generate        │
    │                                │     │    WhatsApp msg    │
    │                                │     │ 4. Clear cart      │
    │                                │     └────────┬───────────┘
    │                                │              │
    │                                │              ▼
    │                                │     ┌────────────────────┐
    │                                │     │ REDIRECT TO        │
    │                                │     │ wa.me/STORE_NUMBER │
    │                                │     │ ?text=ORDER_MSG    │
    │                                │     └────────┬───────────┘
    │                                │              │
    │                                │              ▼
    │                                │     ┌────────────────────┐
    │                                │     │ WhatsApp OPENS     │
    │                                │     │ with pre-filled    │
    │                                │     │ order message      │
    │                                │     │                    │
    │                                │     │ Customer taps      │
    │                                │     │ SEND ──────────>   │
    │                                │     └────────────────────┘
    │                                │              │
    │                                │              ▼
    │                                │     ┌────────────────────┐
    │                                │     │ STORE OWNER        │
    │                                │     │ receives order on  │
    │                                │     │ WhatsApp           │
    │                                │     └────────────────────┘
    │                                │
    │                                ▼
    │                     Back to cart with
    │                     error message
    │
    └─ (User can also browse → add more items → repeat)
```

### 7.2 Customer Authentication Flow

text

```
┌──────────────────────────────────────────────────────────────────┐
│                    AUTHENTICATION FLOW                            │
└──────────────────────────────────────────────────────────────────┘

                    REGISTRATION
                    ============
  ┌──────────┐     ┌──────────────────┐     ┌──────────────┐
  │ Click    │────>│ Registration     │────>│ Validate     │
  │ Register │     │ Form:            │     │ - Name       │
  └──────────┘     │ - Full Name *    │     │ - Phone uniq │
                   │ - Phone *        │     │ - Password   │
                   │ - Email          │     │   strength   │
                   │ - Password *     │     │ - CSRF token │
                   │ - Confirm Pass * │     └──────┬───────┘
                   │ - Address        │            │
                   │ - Neighborhood   │       VALID│INVALID
                   └──────────────────┘            │  │
                                                   │  └──> Show errors
                                                   ▼
                                          ┌──────────────┐
                                          │ Hash password│
                                          │ Insert to DB │
                                          │ Auto-login   │
                                          │ Redirect     │
                                          │ to homepage  │
                                          └──────────────┘

                    LOGIN
                    =====
  ┌──────────┐     ┌──────────────────┐     ┌──────────────┐
  │ Click    │────>│ Login Form:      │────>│ Validate     │
  │ Login    │     │ - Phone *        │     │ - Phone fmt  │
  └──────────┘     │ - Password *     │     │ - Password   │
                   │ - Remember me ☐ │     │   verify     │
                   └──────────────────┘     │ - CSRF token │
                                            └──────┬───────┘
                                              PASS │ FAIL
                                                   │  │
                                                   │  └──> "Phone or
                                                   │       password
                                                   ▼       incorrect"
                                          ┌──────────────┐
                                          │ Set session  │
                                          │ Set remember │
                                          │ cookie (opt) │
                                          │ Merge guest  │
                                          │ cart → user  │
                                          │ Redirect     │
                                          └──────────────┘


                    GUEST CHECKOUT
                    ==============
  ┌──────────────┐     ┌──────────────────────┐
  │ Not logged   │────>│ Checkout form still   │
  │ in? No       │     │ works. Info saved to  │
  │ problem!     │     │ localStorage + optionally │
  │              │     │ hri_guest_info table  │
  └──────────────┘     └──────────────────────┘
```

### 7.3 Admin Flow

text

```
┌──────────────────────────────────────────────────────────────────┐
│                    ADMIN MANAGEMENT FLOW                          │
└──────────────────────────────────────────────────────────────────┘

  ┌────────────┐     ┌─────────────┐     ┌──────────────────────┐
  │ /admin/    │────>│ Admin Login │────>│ ADMIN DASHBOARD      │
  │ login.php  │     │ Form        │     │                      │
  └────────────┘     └─────────────┘     │ ┌──────────────────┐ │
                                         │ │ Today's Orders: 5│ │
                                         │ │ Revenue: 750 DH  │ │
                                         │ │ Pending: 3       │ │
                                         │ │ Low Stock: 2     │ │
                                         │ └──────────────────┘ │
                                         └──────────┬───────────┘
                                                    │
                     ┌──────────────────────────────┼──────────────────┐
                     │                              │                  │
                     ▼                              ▼                  ▼
              ┌──────────────┐           ┌──────────────┐    ┌──────────────┐
              │ PRODUCTS     │           │ ORDERS       │    │ SETTINGS     │
              │              │           │              │    │              │
              │ • List all   │           │ • List all   │    │ • WhatsApp # │
              │ • Add new    │           │ • Filter     │    │ • Min order  │
              │ • Edit       │           │ • View detail│    │ • Delivery $ │
              │ • Delete     │           │ • Update     │    │ • Store info │
              │ • Toggle     │           │   status     │    │ • Hours      │
              │   active     │           │              │    │              │
              │ • Upload img │           │ STATUSES:    │    │              │
              └──────────────┘           │ pending →    │    └──────────────┘
                     │                   │ confirmed →  │
                     │                   │ preparing →  │
              ┌──────────────┐           │ out_deliv →  │    ┌──────────────┐
              │ CATEGORIES   │           │ delivered /  │    │ CUSTOMERS    │
              │              │           │ cancelled    │    │              │
              │ • List all   │           └──────────────┘    │ • List all   │
              │ • Add new    │                               │ • View detail│
              │ • Edit       │                               │ • Order hist │
              │ • Delete     │                               └──────────────┘
              │ • Reorder    │
              └──────────────┘
```

### 7.4 Language Switching Flow

text

```
┌──────────────────────────────────────────────────────────────────┐
│                    LANGUAGE SWITCHING FLOW                        │
└──────────────────────────────────────────────────────────────────┘

  User clicks language toggle (FR/AR) in header
                │
                ▼
  ┌─────────────────────────┐
  │ 1. Set cookie:          │
  │    hri_language = 'ar'  │
  │                         │
  │ 2. Update session:      │
  │    $_SESSION['lang']    │
  │                         │
  │ 3. If logged in:        │
  │    Update DB preference │
  │    customer_preferred_  │
  │    lang = 'ar'          │
  │                         │
  │ 4. Reload page with     │
  │    ?lang=ar parameter   │
  └────────────┬────────────┘
               │
               ▼
  ┌─────────────────────────┐
  │ Page reloads:           │
  │                         │
  │ • Load lang/ar.php      │
  │ • Set dir="rtl"         │
  │ • Load rtl.css          │
  │ • Switch font to Cairo  │
  │ • All text displays     │
  │   in Arabic             │
  │ • Layout mirrors (RTL)  │
  └─────────────────────────┘

  DETECTION PRIORITY:
  1. URL parameter (?lang=fr)
  2. Cookie (hri_language)
  3. Session variable
  4. User DB preference (if logged in)
  5. DEFAULT_LANGUAGE constant ('fr')
```

---

## 9. Security

### 9.1 Security Checklist

text

```
┌──────────────────────────────────────────────────────────────────┐
│                   SECURITY IMPLEMENTATION PLAN                    │
├────────┬─────────────────────────────────────────────────────────┤
│ THREAT │                    MITIGATION                           │
├────────┼─────────────────────────────────────────────────────────┤
│ SQL    │ • Use PDO with PREPARED STATEMENTS exclusively         │
│ Inject │ • Never concatenate user input into SQL queries        │
│        │ • Use parameterized queries with placeholders          │
│        │ • Validate/cast data types before queries              │
├────────┼─────────────────────────────────────────────────────────┤
│ XSS    │ • htmlspecialchars() on ALL output to HTML             │
│        │ • Use ENT_QUOTES | ENT_HTML5 flags                     │
│        │ • Content-Security-Policy headers                      │
│        │ • Sanitize user input on server side                   │
├────────┼─────────────────────────────────────────────────────────┤
│ CSRF   │ • Generate unique token per session                    │
│        │ • Include hidden token field in all forms              │
│        │ • Verify token on every POST request                   │
│        │ • Regenerate token after successful submission         │
├────────┼─────────────────────────────────────────────────────────┤
│ Pass-  │ • password_hash() with PASSWORD_BCRYPT                 │
│ word   │ • password_verify() for authentication                 │
│        │ • Minimum 6 characters enforcement                     │
│        │ • Never store or log plain-text passwords              │
├────────┼─────────────────────────────────────────────────────────┤
│ Session│ • session_regenerate_id() on login                     │
│ Hijack │ • Secure session cookie settings                       │
│        │ • HttpOnly, Secure (HTTPS), SameSite=Strict            │
│        │ • Session timeout after 30 min inactivity              │
├────────┼─────────────────────────────────────────────────────────┤
│ File   │ • Validate MIME type server-side                       │
│ Upload │ • Rename files (never use original filename)           │
│        │ • Store outside webroot or use .htaccess deny          │
│        │ • Limit file size (2MB max)                            │
│        │ • Only allow: JPEG, PNG, WebP                          │
│        │ • Check for PHP code in uploaded files                 │
├────────┼─────────────────────────────────────────────────────────┤
│ Admin  │ • Separate login page and session                      │
│ Access │ • Auth check on every admin page                       │
│        │ • Rate limit login attempts (basic)                    │
│        │ • Strong password requirement for admin                │
├────────┼─────────────────────────────────────────────────────────┤
│ Data   │ • Input validation (type, length, format)              │
│ Valid. │ • Phone format: Moroccan (0[5-7]XXXXXXXX)              │
│        │ • Email format validation                              │
│        │ • Numeric validation for prices/quantities             │
│        │ • Server-side validation (never trust client only)     │
├────────┼─────────────────────────────────────────────────────────┤
│ Config │ • .htaccess to block access to /config/ directory      │
│ Protect│ • .htaccess to block access to /sql/ directory         │
│        │ • Disable directory listing                            │
│        │ • Move sensitive files above webroot if possible       │
├────────┼─────────────────────────────────────────────────────────┤
│ HTTPS  │ • Enforce HTTPS in production                          │
│        │ • HSTS header                                          │
│        │ • Redirect HTTP → HTTPS                                │
└────────┴─────────────────────────────────────────────────────────┘
```

### 9.2 Security Code Examples

PHP

```
<?php
/**
 * FILE: config/database.php
 * PURPOSE: Secure database connection using PDO with prepared statements
 */

/* ----------------------------------------------------------------
 * Database credentials (move to environment variables in production)
 * ---------------------------------------------------------------- */
$db_host     = 'localhost';
$db_name     = 'matjar_el_kotobia_db';
$db_user     = 'root';            // Change in production
$db_password = '';                 // Change in production
$db_charset  = 'utf8mb4';

/* ----------------------------------------------------------------
 * Create PDO connection with security settings
 * ---------------------------------------------------------------- */
try {
    $db_dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";

    $db_options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,    // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Return associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                     // Use real prepared statements
        PDO::ATTR_PERSISTENT         => false,                     // No persistent connections
    ];

    $db_connection = new PDO($db_dsn, $db_user, $db_password, $db_options);

} catch (PDOException $e) {
    // Log error, do NOT expose details to user
    error_log('HRI DB Connection Error: ' . $e->getMessage());
    die('Service temporarily unavailable. Please try again later.');
}


/**
 * EXAMPLE: Secure query with prepared statement
 * NEVER DO: "SELECT * FROM hri_product WHERE product_id = " . $_GET['id']
 */
function get_product_by_id(int $product_id): ?array
{
    global $db_connection;

    // Prepared statement prevents SQL injection
    $sql = "SELECT * FROM hri_product
            WHERE product_id = :product_id
            AND product_is_active = 1";

    $stmt = $db_connection->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    $product_data = $stmt->fetch();

    return $product_data ?: null;
}


/**
 * EXAMPLE: CSRF Token generation and verification
 */
function generate_csrf_token(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function verify_csrf_token(string $token): bool
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// Usage in form:
// <input type="hidden" name="hri_csrf_token" value="<?= generate_csrf_token() ?>">

// Usage in handler:
// if (!verify_csrf_token($_POST['hri_csrf_token'])) { die('Invalid request'); }


/**
 * EXAMPLE: Password hashing
 */
function hash_customer_password(string $plain_password): string
{
    return password_hash($plain_password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verify_customer_password(string $plain_password, string $hashed_password): bool
{
    return password_verify($plain_password, $hashed_password);
}


/**
 * EXAMPLE: Input sanitization
 */
function sanitize_input(string $input): string
{
    $input = trim($input);               // Remove whitespace
    $input = stripslashes($input);       // Remove backslashes
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Prevent XSS
    return $input;
}

/**
 * EXAMPLE: Moroccan phone validation
 */
function validate_phone_morocco(string $phone): bool
{
    // Remove spaces and dashes
    $phone = preg_replace('/[\s\-]/', '', $phone);

    // Moroccan mobile: 06XXXXXXXX, 07XXXXXXXX, or +212...
    return (bool) preg_match('/^(0[5-7]\d{8}|(\+212|00212)[5-7]\d{8})$/', $phone);
}
?>
```

### 9.3 .htaccess Security

apache

```
# FILE: .htaccess (root directory)
# PURPOSE: Security headers and URL rewriting for HRI Supermarket

# ================================================================
# SECURITY HEADERS
# ================================================================
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "camera=(), microphone=(), geolocation=()"
</IfModule>

# ================================================================
# BLOCK ACCESS TO SENSITIVE DIRECTORIES
# ================================================================
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Block access to config directory
    RewriteRule ^config/ - [F,L]

    # Block access to sql directory
    RewriteRule ^sql/ - [F,L]

    # Block access to includes directory (direct access)
    RewriteRule ^includes/ - [F,L]

    # Block access to lang directory (direct access)
    RewriteRule ^lang/ - [F,L]
</IfModule>

# ================================================================
# DISABLE DIRECTORY LISTING
# ================================================================
Options -Indexes

# ================================================================
# BLOCK ACCESS TO .PHP FILES IN UPLOADS
# ================================================================
<Directory "assets/uploads">
    <FilesMatch "\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)$">
        Deny from all
    </FilesMatch>
</Directory>

# ================================================================
# PROTECT .HTACCESS FILE ITSELF
# ================================================================
<Files .htaccess>
    Order allow,deny
    Deny from all
</Files>

# ================================================================
# PROTECT SENSITIVE FILE TYPES
# ================================================================
<FilesMatch "\.(sql|log|ini|env|bak|old|tmp)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---
### 10 Deployment Checklist

text

```
PRE-DEPLOYMENT:
☐ Update config/database.php with production credentials
☐ Update config/constants.php:
   ☐ SITE_URL → production URL
   ☐ STORE_WHATSAPP_NUMBER → real number
☐ Change default admin password (CRITICAL)
☐ Set PHP error_reporting to 0 (hide errors from users)
☐ Set display_errors = Off in php.ini
☐ Enable error logging to file
☐ Test all forms and WhatsApp checkout flow
☐ Test both FR and AR languages
☐ Test on mobile devices (primary target)
☐ Optimize product images (compress)
☐ Verify .htaccess security rules work

DATABASE:
☐ Import matjar_el_kotobia.sql to production DB
☐ Create production DB user with minimal privileges:
   GRANT SELECT, INSERT, UPDATE, DELETE ON matjar_el_kotobia_db.* TO 'hri_user'@'localhost';
☐ Remove seed data if not needed
☐ Insert real admin account with strong password
☐ Insert real store settings

FILES:
☐ Upload all project files via FTP/SFTP
☐ Set correct file permissions:
   ├── Directories: 755
   ├── PHP files: 644
   ├── uploads/: 755 (writable by web server)
   └── config/: 750 (restricted)
☐ Verify assets/uploads/products/ directory exists and is writable

SSL/HTTPS:
☐ Install SSL certificate
☐ Configure HTTP → HTTPS redirect in .htaccess
☐ Update SITE_URL to https://
☐ Test all pages over HTTPS

POST-DEPLOYMENT:
☐ Test full purchase flow (browse → cart → WhatsApp)
☐ Test admin login and CRUD operations
☐ Test language switching
☐ Test on multiple mobile devices/browsers
☐ Verify WhatsApp message format
☐ Set up basic monitoring (uptime check)
☐ Create database backup schedule (daily recommended)
☐ Document admin credentials securely
```

### 10.4 Performance Optimization

text

```
IMAGE OPTIMIZATION:
├── Resize product images to max 600x600px on upload
├── Generate 300x300 thumbnails for product cards
├── Use WebP format where supported
├── Implement lazy loading (loading="lazy" attribute)
└── Consider using CSS aspect-ratio for image containers

CSS/JS:
├── Minify CSS and JS files for production
├── Combine CSS into one file where possible
├── Load Bootstrap from CDN (faster caching)
├── Defer non-critical JavaScript
└── Inline critical CSS for above-the-fold content

DATABASE:
├── Proper indexing (defined in schema)
├── Use pagination (never load all products)
├── Cache category list in session (changes rarely)
├── Optimize queries with EXPLAIN
└── Regular OPTIMIZE TABLE maintenance

CACHING:
├── Set browser caching headers for static assets
├── Cache-Control: max-age=604800 for images
├── Cache-Control: max-age=86400 for CSS/JS
└── ETag headers for cache validation
```

---

## 11. Risks

### 11.1 Risk Assessment Matrix

text

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                              RISK ASSESSMENT                                      │
├────────┬────────────────────────────┬────────┬──────────┬─────────────────────────┤
│   ID   │     RISK DESCRIPTION       │ IMPACT │LIKELIHOOD│      MITIGATION         │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-01   │ WhatsApp blocks/changes    │  HIGH  │   LOW    │ Monitor WhatsApp API    │
│        │ the wa.me deep link format │        │          │ policies. Keep message  │
│        │                            │        │          │ under URL length limit. │
│        │                            │        │          │ Save order in DB first  │
│        │                            │        │          │ as fallback.            │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-02   │ WhatsApp message too long  │  MED   │   MED    │ Limit cart to ~20 items │
│        │ for URL encoding (URL max  │        │          │ Test URL length limits. │
│        │ ~2000 chars in some        │        │          │ Truncate message if     │
│        │ browsers)                  │        │          │ needed. Use WhatsApp    │
│        │                            │        │          │ Business API for V2.    │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-03   │ Customer doesn't actually  │  HIGH  │   MED    │ Order is saved in DB    │
│        │ send the WhatsApp message  │        │          │ before redirect. Admin  │
│        │ (closes app, loses         │        │          │ can see unsent orders.  │
│        │ connection)                │        │          │ Follow up via phone.    │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-04   │ No online payment means    │  MED   │   LOW    │ COD is standard in      │
│        │ order cancellations and    │        │          │ Morocco. Track cancel   │
│        │ no-shows at delivery       │        │          │ rate. Blacklist repeat  │
│        │                            │        │          │ offenders (V2).         │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-05   │ Product price discrepancy  │  MED   │   MED    │ Snapshot prices in      │
│        │ between order time and     │        │          │ hri_order_item table.   │
│        │ delivery time (admin       │        │          │ Confirm price via       │
│        │ changes price)             │        │          │ WhatsApp before         │
│        │                            │        │          │ delivery.               │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-06   │ Arabic RTL layout breaks   │  MED   │   MED    │ Thorough RTL testing.   │
│        │ on certain devices/browsers│        │          │ Use Bootstrap 5's RTL   │
│        │                            │        │          │ support. Separate       │
│        │                            │        │          │ rtl.css for overrides.  │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-07   │ Mobile performance issues  │  MED   │   LOW    │ Lazy load images.       │
│        │ with many product images   │        │          │ Use thumbnails for      │
│        │ on slow 3G/4G connections  │        │          │ listings. Compress all  │
│        │                            │        │          │ images. Paginate.       │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-08   │ SQL injection or security  │  HIGH  │   LOW    │ PDO prepared statements │
│        │ breach                     │        │          │ exclusively. CSRF       │
│        │                            │        │          │ tokens. Input           │
│        │                            │        │          │ validation. Regular     │
│        │                            │        │          │ security reviews.       │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-09   │ Data loss (no backup       │  HIGH  │   MED    │ Daily automated DB      │
│        │ strategy)                  │        │          │ backups. Store backups  │
│        │                            │        │          │ off-server. Test        │
│        │                            │        │          │ restore procedure.      │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-10   │ Store owner overwhelmed    │  LOW   │   LOW    │ If volume grows,        │
│        │ with WhatsApp orders       │        │          │ consider WhatsApp       │
│        │ (scaling issue)            │        │          │ Business API with auto  │
│        │                            │        │          │ responses. Add order    │
│        │                            │        │          │ management features.    │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-11   │ Shared hosting limitations │  MED   │   MED    │ Start with shared       │
│        │ (PHP version, resources,   │        │          │ hosting that meets min  │
│        │ cron jobs for backups)     │        │          │ requirements. Plan      │
│        │                            │        │          │ migration to VPS if     │
│        │                            │        │          │ traffic grows.          │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-12   │ Inconsistent variable      │  MED   │   MED    │ This PRD defines ALL    │
│        │ naming when different AI   │        │          │ variable names as       │
│        │ assistants generate code   │        │          │ permanent standards.    │
│        │                            │        │          │ Always reference this   │
│        │                            │        │          │ document. Include       │
│        │                            │        │          │ naming rules in every   │
│        │                            │        │          │ AI prompt.              │
├────────┼────────────────────────────┼────────┼──────────┼─────────────────────────┤
│ R-13   │ Users unfamiliar with      │  LOW   │   MED    │ Clear UX with guided    │
│        │ online ordering (older     │        │          │ checkout flow. Familiar │
│        │ demographic in Safi)       │        │          │ WhatsApp button. Simple │
│        │                            │        │          │ UI. Visual product      │
│        │                            │        │          │ images. Arabic support. │
└────────┴────────────────────────────┴────────┴──────────┴─────────────────────────┘
```

### 11.2 Future Enhancements (V2 Roadmap)

text

```
PHASE 2 (After initial launch):
├── WhatsApp Business API integration (auto-confirm orders)
├── Push notifications for order status updates
├── Customer reviews and ratings
├── Wishlist / favorites functionality
├── Reorder from past orders (one-click)
├── Multiple delivery time slots
├── Neighborhood-based delivery zones & fees
├── Coupon/discount code system
├── Product barcode scanning for admin
├── Basic inventory management alerts
├── SEO optimization (meta tags, structured data)
└── PWA (Progressive Web App) for install-to-homescreen

PHASE 3 (Growth):
├── Online payment integration (CMI, PayPal)
├── Multiple store branches
├── Delivery tracking (basic)
├── Customer loyalty points
├── Mobile app (React Native or Flutter)
├── Advanced analytics dashboard
├── Email/SMS notifications
└── Expand to other cities beyond Safi
```

---

## Appendix A: Quick Reference Card

text

```
╔══════════════════════════════════════════════════════════════╗
║        HRI SUPERMARKET — DEVELOPER QUICK REFERENCE          ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  PROJECT NAME    : matjar_el_kotobia                           ║
║  DATABASE NAME   : matjar_el_kotobia_db                        ║
║  TABLE PREFIX    : hri_                                      ║
║  DEFAULT CITY    : Safi                                      ║
║  CURRENCY        : DH (MAD)                                  ║
║  LANGUAGES       : French (fr), Arabic (ar)                  ║
║  DEFAULT LANG    : fr                                        ║
║  CHECKOUT METHOD : WhatsApp (wa.me deep link)                ║
║  PAYMENT         : Cash on Delivery (COD)                    ║
║                                                              ║
║  PHP NAMING      : snake_case                                ║
║  JS NAMING       : camelCase                                 ║
║  CSS NAMING      : kebab-case (BEM with hri- prefix)         ║
║  CONSTANTS       : UPPER_SNAKE_CASE                          ║
║  DB COLUMNS      : {entity}_{descriptor}                     ║
║                                                              ║
║  KEY FILES:                                                  ║
║  config/constants.php  → All global constants                ║
║  config/database.php   → PDO connection                      ║
║  lang/fr.php           → French translations                 ║
║  lang/ar.php           → Arabic translations                 ║
║  includes/functions.php→ All helper functions                ║
║                                                              ║
║  KEY TABLES:                                                 ║
║  hri_admin, hri_customer, hri_category,                      ║
║  hri_product, hri_order, hri_order_item,                     ║
║  hri_settings, hri_guest_info                                ║
║                                                              ║
║  SECURITY: PDO prepared statements, bcrypt passwords,        ║
║  CSRF tokens, input sanitization, .htaccess protection       ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

---

_Document Version: 1.0_  
_Created: Mars 2026_  
_Status: Ready for Development_  
_Author: Matjar El Kotobia Project Team_
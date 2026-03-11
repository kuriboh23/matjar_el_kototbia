# Matjar El Kotobia

![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg) ![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg) ![License](https://img.shields.io/badge/License-MIT-green.svg)

> **Matjar El Kotobia** is a mobile‑first, bilingual (French/Arabic) grocery
> e‑commerce web application for a local supermarket in Safi, Morocco. Users
> browse and search products, manage a shopping cart, and place orders via
> WhatsApp. The admin panel enables catalog management, order processing, and
> printable receipts.

---

## 🔍 Key Features

* **Customer‑facing**
  * Browse by category with responsive grid layout
  * Live search (French/Arabic) with AJAX suggestions
  * Shopping cart with AJAX add/update/remove/clear/count and persistent state
  * Checkout form that generates a formatted WhatsApp message
  * Guest checkout or optional user accounts with profile & order history
  * Bilingual UI (FR / AR) with RTL support
  * Mobile‑first design and breakpoints for tablets/desktops

* **Admin panel**
  * Dashboard with quick stats, low‑stock alerts and pending orders
  * CRUD for products and categories (image upload, featured/sale flags)
  * Order management with status updates, filters, and WhatsApp re‑send
  * Print‑friendly order receipts (browser print or screenshot via HTML2Canvas)
  * Customer listing, settings page, and basic analytics
  * Admin authentication separate from customers

* **Technology**
  * PHP 8.x, MySQL, vanilla JS/AJAX, Bootstrap/UIkit
  * Structured with includes for headers, footers, and shared helpers
  * SQL schema and sample data in `sql/` folder

---

## 🚀 Getting Started

### Prerequisites

* Web server with PHP 8.1+ (Apache, Nginx, etc.)
* MySQL 8.0+ or compatible
* Composer (optional, for future dependency management)
* Git and VS Code are recommended for development

### Installation

```bash
# clone repository
cd c:\xampp\htdocs
git clone <repo-url> matjar_el_kotobia
cd matjar_el_kotobia
```

1. Start Apache and MySQL (XAMPP, Laragon, etc.)
2. Import the database schema and sample data:
   ```sql
   -- using phpMyAdmin or MySQL CLI
   SOURCE sql/matjar_el_kotobia.sql;
   ```
   The `sql` directory also contains `sample_products.sql` for testing.
3. Update configuration files:
   * `config/database.php` — database credentials
   * `config/constants.php` — WhatsApp number, timezone, etc.
   * `config/config.php` — base URL and other environment settings
4. Open in browser:
   * Front‑end: `http://localhost/matjar_el_kotobia/`
   * Admin panel: `http://localhost/matjar_el_kotobia/admin/`

> **Admin credentials**: default username `admin`; password defined in the
> imported SQL or adjustable via the `hri_admin` table.

See `docs/SETUP.md` for extended setup and deployment notes.

---

## 🗂 Project Structure

```
├── admin/          # back‑office scripts and views
├── ajax/           # AJAX endpoints for cart, search, orders, etc.
├── assets/         # CSS, JS, and image assets
├── config/         # configuration and database connection
├── includes/       # shared helpers, headers, auth checks
├── lang/           # language files (fr, ar)
├── pages/          # front‑end page fragments and templates
├── sql/            # database schema and sample data
│   ├── matjar_el_kotobia.sql      # full schema + initial data
│   └── sample_products.sql        # additional seed data
├── docs/           # PRD, setup and developer documentation
├── pages/          # public PHP pages for customers
└── …
```

---

## 📄 Documentation

* `docs/PRD.md` – product requirements and feature spec (updated 2026‑03‑11)
* `docs/SETUP.md` – developer environment and deployment notes

---

## 🛠 Development Notes

* **Cart logic** – `includes/cart_functions.php` manages session/database cart
  state; AJAX endpoints in `ajax/` drive the UI.
* **Localization** – `includes/language_handler.php` uses cookies and query
  params to toggle FR/AR; RTL styles are applied dynamically.
* **Order creation** – handled in `includes/order_functions.php`; produces the
  WhatsApp text and persists orders to `hri_order` table.
* **Admin auth** – `includes/admin_auth_check.php` guards backend pages.
* **Utilities** – common helpers available in `includes/functions.php`.

### Database Schema Overview

Key tables (see `sql/matjar_el_kotobia.sql` for full DDL):

| Table | Purpose |
|-------|---------|
| `hri_product` | Product catalog entries |
| `hri_category` | Category hierarchy |
| `hri_customer` | Registered users |
| `hri_order` | Orders metadata |
| `hri_order_item` | Products per order |
| `hri_admin` | Administrator credentials |
| `hri_settings` | Store configuration (WhatsApp, fees, etc.) |

Each table includes Arabic and French fields where appropriate; several have
indexes on `is_active` and foreign key relationships for referential integrity.


---

## 📄 Documentation

* `docs/PRD.md` – product requirements and feature spec (updated 2026‑03‑11)
* `docs/SETUP.md` – developer environment and deployment notes

---

## 📦 License

This project is released under the [MIT License](LICENSE). Modify and redistribute
freely.

---

## 📦 License

This project is provided as‑is for educational or internal use. Modify freely.

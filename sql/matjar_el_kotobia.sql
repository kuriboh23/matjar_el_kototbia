-- ================================================================
-- FILE: sql/matjar_el_kotobia.sql
-- PROJECT: Matjar El Kotobia — HRI Supermarket
-- PURPOSE: Complete database schema + seed data
-- DATABASE: matjar_el_kotobia_db
-- CHARSET: utf8mb4 / utf8mb4_unicode_ci  (Arabic + French support)
-- PREFIX: hri_  (all tables)
-- ENGINE: InnoDB (foreign keys, transactions)
-- CURRENCY: MAD (Moroccan Dirham — DH)
-- NOTE: Column & table names are PERMANENT per PRD v1.0.
--       Always reference the PRD before renaming anything.
-- ================================================================


-- ================================================================
-- 0. DATABASE CREATION
-- ================================================================
CREATE DATABASE IF NOT EXISTS `matjar_el_kotobia_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `matjar_el_kotobia_db`;

-- Ensure consistent charset for the session
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;


-- ================================================================
-- 1. TABLE: hri_admin
-- PURPOSE: Admin user accounts (back-office access only)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_admin` (
    `admin_id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `admin_username`    VARCHAR(50)     NOT NULL                 COMMENT 'Unique login username',
    `admin_email`       VARCHAR(100)    NOT NULL                 COMMENT 'Admin email address',
    `admin_password`    VARCHAR(255)    NOT NULL                 COMMENT 'bcrypt hashed password (cost >= 10)',
    `admin_full_name`   VARCHAR(100)    NOT NULL                 COMMENT 'Display name',
    `admin_role`        ENUM('super_admin', 'manager')
                                        NOT NULL DEFAULT 'manager'
                                                                 COMMENT 'Role level',
    `admin_is_active`   TINYINT(1)      NOT NULL DEFAULT 1       COMMENT '1=active, 0=disabled',
    `admin_last_login`  DATETIME                 DEFAULT NULL    COMMENT 'Last successful login timestamp',
    `admin_created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `admin_updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                 ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`admin_id`),
    UNIQUE KEY `uq_admin_username` (`admin_username`),
    UNIQUE KEY `uq_admin_email`    (`admin_email`),

    -- ---- Indexes ----
    INDEX `idx_admin_username`  (`admin_username`),
    INDEX `idx_admin_email`     (`admin_email`),
    INDEX `idx_admin_is_active` (`admin_is_active`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Admin accounts — back-office access only';


-- ================================================================
-- 2. TABLE: hri_customer
-- PURPOSE: Registered customer accounts
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_customer` (
    `customer_id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `customer_full_name`        VARCHAR(100)    NOT NULL                 COMMENT 'Full name (FR or AR)',
    `customer_phone`            VARCHAR(20)     NOT NULL                 COMMENT 'Primary login identifier, Moroccan format (e.g. 0612345678)',
    `customer_email`            VARCHAR(100)             DEFAULT NULL    COMMENT 'Optional email address',
    `customer_password`         VARCHAR(255)    NOT NULL                 COMMENT 'bcrypt hashed password',
    `customer_address`          VARCHAR(255)             DEFAULT NULL    COMMENT 'Default delivery address',
    `customer_neighborhood`     VARCHAR(100)             DEFAULT NULL    COMMENT 'Quartier / neighborhood',
    `customer_city`             VARCHAR(50)     NOT NULL DEFAULT 'Safi'  COMMENT 'City — Safi for V1',
    `customer_notes`            TEXT                     DEFAULT NULL    COMMENT 'Default delivery notes',
    `customer_preferred_lang`   ENUM('fr', 'ar') NOT NULL DEFAULT 'fr'  COMMENT 'UI language preference',
    `customer_is_active`        TINYINT(1)      NOT NULL DEFAULT 1       COMMENT '1=active, 0=banned/disabled',
    `customer_remember_token`   VARCHAR(255)             DEFAULT NULL    COMMENT '"Remember me" session token',
    `customer_last_login`       DATETIME                 DEFAULT NULL,
    `customer_created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `customer_updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                         ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`customer_id`),
    UNIQUE KEY `uq_customer_phone` (`customer_phone`),

    -- ---- Indexes ----
    INDEX `idx_customer_phone`     (`customer_phone`),
    INDEX `idx_customer_email`     (`customer_email`),
    INDEX `idx_customer_city`      (`customer_city`),
    INDEX `idx_customer_is_active` (`customer_is_active`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Registered customer accounts';


-- ================================================================
-- 3. TABLE: hri_category
-- PURPOSE: Product categories (bilingual FR/AR)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_category` (
    `category_id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `category_name_fr`          VARCHAR(100)    NOT NULL                 COMMENT 'Category name in French',
    `category_name_ar`          VARCHAR(100)    NOT NULL                 COMMENT 'Category name in Arabic',
    `category_slug`             VARCHAR(120)    NOT NULL                 COMMENT 'URL-safe slug (e.g. fruits-legumes)',
    `category_icon`             VARCHAR(100)             DEFAULT NULL    COMMENT 'Bootstrap Icon class or filename',
    `category_image`            VARCHAR(255)             DEFAULT NULL    COMMENT 'Category image filename (assets/images/categories/)',
    `category_description_fr`   TEXT                     DEFAULT NULL    COMMENT 'Optional description in French',
    `category_description_ar`   TEXT                     DEFAULT NULL    COMMENT 'Optional description in Arabic',
    `category_display_order`    INT             NOT NULL DEFAULT 0       COMMENT 'Sort order for nav/listing display',
    `category_is_active`        TINYINT(1)      NOT NULL DEFAULT 1       COMMENT '1=visible, 0=hidden',
    `category_created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `category_updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                         ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`category_id`),
    UNIQUE KEY `uq_category_slug` (`category_slug`),

    -- ---- Indexes ----
    INDEX `idx_category_slug`         (`category_slug`),
    INDEX `idx_category_active_order` (`category_is_active`, `category_display_order`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Product categories — bilingual FR/AR';


-- ================================================================
-- 4. TABLE: hri_product
-- PURPOSE: Product catalog (bilingual, pricing in MAD)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_product` (
    `product_id`                INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `product_category_id`       INT UNSIGNED    NOT NULL                 COMMENT 'FK → hri_category.category_id',
    `product_name_fr`           VARCHAR(200)    NOT NULL                 COMMENT 'Product name in French',
    `product_name_ar`           VARCHAR(200)    NOT NULL                 COMMENT 'Product name in Arabic',
    `product_slug`              VARCHAR(220)    NOT NULL                 COMMENT 'URL-safe slug — unique per product',
    `product_description_fr`    TEXT                     DEFAULT NULL    COMMENT 'Full description in French',
    `product_description_ar`    TEXT                     DEFAULT NULL    COMMENT 'Full description in Arabic',
    `product_price`             DECIMAL(10,2)   NOT NULL                 COMMENT 'Regular price in DH (MAD)',
    `product_sale_price`        DECIMAL(10,2)            DEFAULT NULL    COMMENT 'Promotional price in DH — NULL = no sale',
    `product_unit`              ENUM('kg','piece','pack','liter','gram','unit')
                                                NOT NULL DEFAULT 'piece' COMMENT 'Unit of measure',
    `product_unit_step`         DECIMAL(5,2)    NOT NULL DEFAULT 1.00   COMMENT 'Cart quantity increment (e.g. 0.5 for ½ kg)',
    `product_stock_quantity`    INT             NOT NULL DEFAULT 0       COMMENT 'Available stock — 0 = out of stock',
    `product_image`             VARCHAR(255)    NOT NULL DEFAULT 'default_product.jpg'
                                                                         COMMENT 'Main image filename (assets/uploads/products/)',
    `product_image_thumb`       VARCHAR(255)             DEFAULT NULL    COMMENT 'Thumbnail filename (generated by PHP GD)',
    `product_is_featured`       TINYINT(1)      NOT NULL DEFAULT 0       COMMENT '1 = show in homepage featured section',
    `product_is_on_sale`        TINYINT(1)      NOT NULL DEFAULT 0       COMMENT '1 = show sale badge overlay',
    `product_is_active`         TINYINT(1)      NOT NULL DEFAULT 1       COMMENT '1 = visible to customers',
    `product_view_count`        INT UNSIGNED    NOT NULL DEFAULT 0       COMMENT 'Page-view counter for basic analytics',
    `product_sort_order`        INT             NOT NULL DEFAULT 0       COMMENT 'Custom sort within category',
    `product_created_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `product_updated_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                         ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`product_id`),
    UNIQUE KEY `uq_product_slug` (`product_slug`),

    CONSTRAINT `fk_product_category`
        FOREIGN KEY (`product_category_id`)
        REFERENCES `hri_category` (`category_id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    -- ---- Indexes ----
    INDEX `idx_product_category`  (`product_category_id`),
    INDEX `idx_product_slug`      (`product_slug`),
    INDEX `idx_product_active`    (`product_is_active`),
    INDEX `idx_product_featured`  (`product_is_featured`, `product_is_active`),
    INDEX `idx_product_sale`      (`product_is_on_sale`,  `product_is_active`),
    INDEX `idx_product_price`     (`product_price`),
    INDEX `idx_product_stock`     (`product_stock_quantity`),

    -- Full-text search across both languages
    FULLTEXT INDEX `idx_product_search` (`product_name_fr`, `product_name_ar`,
                                          `product_description_fr`, `product_description_ar`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Product catalog — prices in MAD, bilingual FR/AR';


-- ================================================================
-- 5. TABLE: hri_livreur
-- PURPOSE: Delivery personnel (Livreurs)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_livreur` (
    `livreur_id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `livreur_name`          VARCHAR(100)    NOT NULL                 COMMENT 'Full name of delivery person',
    `livreur_phone`         VARCHAR(20)              DEFAULT NULL    COMMENT 'Phone number',
    `livreur_is_active`     TINYINT(1)      NOT NULL DEFAULT 1       COMMENT '1=active, 0=inactive',
    `livreur_created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `livreur_updated_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`livreur_id`),
    INDEX `idx_livreur_active` (`livreur_is_active`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Delivery personnel tracking';


-- ================================================================
-- 6. TABLE: hri_order
-- PURPOSE: Customer orders (WhatsApp checkout)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_order` (
    `order_id`                      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `order_number`                  VARCHAR(30)     NOT NULL                 COMMENT 'Human-readable ref e.g. HRI-20260101-001',
    `order_customer_id`             INT UNSIGNED             DEFAULT NULL    COMMENT 'FK → hri_customer (NULL for guests)',
    `order_livreur_id`              INT UNSIGNED             DEFAULT NULL    COMMENT 'FK → hri_livreur (Assigned delivery person)',
    `order_customer_name`           VARCHAR(100)    NOT NULL                 COMMENT 'Name snapshot at checkout',
    `order_customer_phone`          VARCHAR(20)     NOT NULL                 COMMENT 'Phone snapshot at checkout',
    `order_customer_address`        VARCHAR(255)    NOT NULL                 COMMENT 'Address snapshot at checkout',
    `order_customer_neighborhood`   VARCHAR(100)             DEFAULT NULL    COMMENT 'Quartier snapshot',
    `order_customer_city`           VARCHAR(50)     NOT NULL DEFAULT 'Safi',
    `order_notes`                   TEXT                     DEFAULT NULL    COMMENT 'Customer delivery instructions',
    `order_subtotal`                DECIMAL(10,2)   NOT NULL                 COMMENT 'Items total before delivery fee (DH)',
    `order_delivery_fee`            DECIMAL(10,2)   NOT NULL DEFAULT 0.00   COMMENT 'Delivery fee charged (DH)',
    `order_total`                   DECIMAL(10,2)   NOT NULL                 COMMENT 'Grand total = subtotal + delivery_fee (DH)',
    `order_item_count`              INT UNSIGNED    NOT NULL DEFAULT 0       COMMENT 'Total distinct items in cart',
    `order_status`                  ENUM('pending','confirmed','preparing',
                                         'out_for_delivery','delivered','cancelled')
                                                    NOT NULL DEFAULT 'pending'
                                                                             COMMENT 'Current fulfillment status',
    `order_payment_method`          VARCHAR(30)     NOT NULL DEFAULT 'cod'   COMMENT 'Payment method (cod = cash on delivery)',
    `order_whatsapp_sent`           TINYINT(1)      NOT NULL DEFAULT 0       COMMENT '1 = WhatsApp tab was opened by customer',
    `order_language`                ENUM('fr','ar') NOT NULL DEFAULT 'fr'    COMMENT 'UI language used at checkout',
    `order_ip_address`              VARCHAR(45)              DEFAULT NULL    COMMENT 'Customer IP for security logging',
    `order_created_at`              DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `order_updated_at`              DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                             ON UPDATE CURRENT_TIMESTAMP,
    `order_delivered_at`            DATETIME                 DEFAULT NULL    COMMENT 'Timestamp when marked delivered',

    -- ---- Constraints ----
    PRIMARY KEY (`order_id`),
    UNIQUE KEY `uq_order_number` (`order_number`),

    CONSTRAINT `fk_order_customer`
        FOREIGN KEY (`order_customer_id`)
        REFERENCES `hri_customer` (`customer_id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT `fk_order_livreur`
        FOREIGN KEY (`order_livreur_id`)
        REFERENCES `hri_livreur` (`livreur_id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    -- ---- Indexes ----
    INDEX `idx_order_number`      (`order_number`),
    INDEX `idx_order_customer`    (`order_customer_id`),
    INDEX `idx_order_livreur`     (`order_livreur_id`),
    INDEX `idx_order_status`      (`order_status`),
    INDEX `idx_order_date`        (`order_created_at`),
    INDEX `idx_order_status_date` (`order_status`, `order_created_at`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Customer orders — WhatsApp COD checkout';


-- ================================================================
-- 7. TABLE: hri_order_item
-- PURPOSE: Line items within an order (price snapshot)
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_order_item` (
    `order_item_id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `order_item_order_id`       INT UNSIGNED    NOT NULL                 COMMENT 'FK → hri_order.order_id',
    `order_item_product_id`     INT UNSIGNED             DEFAULT NULL    COMMENT 'FK → hri_product (NULL if product later deleted)',
    `order_item_name_fr`        VARCHAR(200)    NOT NULL                 COMMENT 'Product name FR snapshot',
    `order_item_name_ar`        VARCHAR(200)    NOT NULL                 COMMENT 'Product name AR snapshot',
    `order_item_unit_price`     DECIMAL(10,2)   NOT NULL                 COMMENT 'Price per unit at order time (DH)',
    `order_item_quantity`       DECIMAL(10,2)   NOT NULL DEFAULT 1.00   COMMENT 'Ordered quantity',
    `order_item_unit`           VARCHAR(20)     NOT NULL DEFAULT 'piece' COMMENT 'Unit of measure snapshot',
    `order_item_subtotal`       DECIMAL(10,2)   NOT NULL                 COMMENT 'unit_price × quantity (DH)',

    -- ---- Constraints ----
    PRIMARY KEY (`order_item_id`),

    CONSTRAINT `fk_order_item_order`
        FOREIGN KEY (`order_item_order_id`)
        REFERENCES `hri_order` (`order_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `fk_order_item_product`
        FOREIGN KEY (`order_item_product_id`)
        REFERENCES `hri_product` (`product_id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    -- ---- Indexes ----
    INDEX `idx_order_item_order`   (`order_item_order_id`),
    INDEX `idx_order_item_product` (`order_item_product_id`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Line items within an order — prices snapshotted at checkout';


-- ================================================================
-- 7. TABLE: hri_settings
-- PURPOSE: Key-value store for site-wide configuration
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_settings` (
    `setting_id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `setting_key`           VARCHAR(100)    NOT NULL                 COMMENT 'Machine-readable key (permanent)',
    `setting_value`         TEXT                     DEFAULT NULL    COMMENT 'Stored value',
    `setting_type`          ENUM('text','number','boolean','json')
                                            NOT NULL DEFAULT 'text'  COMMENT 'Value type for validation',
    `setting_group`         VARCHAR(50)     NOT NULL DEFAULT 'general'
                                                                     COMMENT 'Grouping for admin UI',
    `setting_label_fr`      VARCHAR(150)             DEFAULT NULL    COMMENT 'Human-readable label (FR)',
    `setting_label_ar`      VARCHAR(150)             DEFAULT NULL    COMMENT 'Human-readable label (AR)',
    `setting_updated_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                     ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`setting_id`),
    UNIQUE KEY `uq_setting_key` (`setting_key`),

    -- ---- Indexes ----
    INDEX `idx_setting_key`   (`setting_key`),
    INDEX `idx_setting_group` (`setting_group`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Site-wide configuration key-value store';


-- ================================================================
-- 8. TABLE: hri_guest_info
-- PURPOSE: Saved checkout info for non-registered (guest) customers
-- ================================================================
CREATE TABLE IF NOT EXISTS `hri_guest_info` (
    `guest_id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `guest_phone`           VARCHAR(20)     NOT NULL                 COMMENT 'Phone used as guest identifier',
    `guest_full_name`       VARCHAR(100)             DEFAULT NULL,
    `guest_address`         VARCHAR(255)             DEFAULT NULL,
    `guest_neighborhood`    VARCHAR(100)             DEFAULT NULL,
    `guest_city`            VARCHAR(50)     NOT NULL DEFAULT 'Safi',
    `guest_notes`           TEXT                     DEFAULT NULL,
    `guest_created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `guest_updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                     ON UPDATE CURRENT_TIMESTAMP,

    -- ---- Constraints ----
    PRIMARY KEY (`guest_id`),
    UNIQUE KEY `uq_guest_phone` (`guest_phone`),

    -- ---- Indexes ----
    INDEX `idx_guest_phone` (`guest_phone`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Saved info for guest (non-registered) customers';

-- ================================================================
-- END OF FILE
-- To verify:
--   SELECT table_name, table_comment FROM information_schema.tables
--   WHERE table_schema = 'matjar_el_kotobia_db';
-- ================================================================


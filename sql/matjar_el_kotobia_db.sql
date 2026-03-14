-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2026 at 04:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matjar_el_kotobia_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `hri_admin`
--

CREATE TABLE `hri_admin` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `admin_username` varchar(50) NOT NULL COMMENT 'Unique login username',
  `admin_email` varchar(100) NOT NULL COMMENT 'Admin email address',
  `admin_password` varchar(255) NOT NULL COMMENT 'bcrypt hashed password (cost >= 10)',
  `admin_full_name` varchar(100) NOT NULL COMMENT 'Display name',
  `admin_role` enum('super_admin','manager') NOT NULL DEFAULT 'manager' COMMENT 'Role level',
  `admin_is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=active, 0=disabled',
  `admin_last_login` datetime DEFAULT NULL COMMENT 'Last successful login timestamp',
  `admin_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin accounts — back-office access only';

--
-- Dumping data for table `hri_admin`
--

INSERT INTO `hri_admin` (`admin_id`, `admin_username`, `admin_email`, `admin_password`, `admin_full_name`, `admin_role`, `admin_is_active`, `admin_last_login`, `admin_created_at`, `admin_updated_at`) VALUES
(1, 'admin', 'admin@matjarelkotobia.ma', '$2y$12$0.Rqa8pKKdYt9w8nRiV5d.qQRpt6Ix1G5A.MqrGZ7wywpx/HAFwCW', 'Administrateur Principal', 'super_admin', 1, '2026-03-12 15:53:27', '2026-03-08 09:17:22', '2026-03-12 15:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `hri_category`
--

CREATE TABLE `hri_category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name_fr` varchar(100) NOT NULL COMMENT 'Category name in French',
  `category_name_ar` varchar(100) NOT NULL COMMENT 'Category name in Arabic',
  `category_slug` varchar(120) NOT NULL COMMENT 'URL-safe slug (e.g. fruits-legumes)',
  `category_icon` varchar(100) DEFAULT NULL COMMENT 'Bootstrap Icon class or filename',
  `category_image` varchar(255) DEFAULT NULL COMMENT 'Category image filename (assets/images/categories/)',
  `category_description_fr` text DEFAULT NULL COMMENT 'Optional description in French',
  `category_description_ar` text DEFAULT NULL COMMENT 'Optional description in Arabic',
  `category_display_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Sort order for nav/listing display',
  `category_is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=visible, 0=hidden',
  `category_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `category_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product categories — bilingual FR/AR';

--
-- Dumping data for table `hri_category`
--

INSERT INTO `hri_category` (`category_id`, `category_name_fr`, `category_name_ar`, `category_slug`, `category_icon`, `category_image`, `category_description_fr`, `category_description_ar`, `category_display_order`, `category_is_active`, `category_created_at`, `category_updated_at`) VALUES
(1, 'Fruits & Légumes', 'فواكه وخضروات', 'fruits-legumes', 'bi-apple', NULL, NULL, NULL, 1, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(2, 'Viandes\'et  Volailles', 'لحوم ودواجن', 'viandes-volailles', 'bi-egg-fried', NULL, NULL, NULL, 3, 1, '2026-03-08 09:17:22', '2026-03-11 02:08:36'),
(3, 'Poissons', 'أسماك', 'poissons', 'bi-water', NULL, NULL, NULL, 2, 1, '2026-03-08 09:17:22', '2026-03-11 02:05:47'),
(4, 'Produits Laitiers', 'منتجات الحليب', 'produits-laitiers', 'bi-cup-straw', NULL, NULL, NULL, 4, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(6, 'Épicerie', 'بقالة', 'epicerie', 'bi-box', NULL, NULL, NULL, 6, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(7, 'Boissons', 'مشروبات', 'boissons', 'bi-droplet', NULL, NULL, NULL, 7, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(8, 'Surgelés', 'مجمدات', 'surgeles', 'bi-snow', NULL, NULL, NULL, 8, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(9, 'Hygiène & Beauté', 'نظافة وجمال', 'hygiene-beaute', 'bi-stars', NULL, NULL, NULL, 9, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(10, 'Bébé', 'مستلزمات الأطفال', 'bebe', 'bi-balloon', NULL, NULL, NULL, 10, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(11, 'Ménage', 'مستلزمات المنزل', 'menage', 'bi-house', NULL, NULL, NULL, 11, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22'),
(12, 'Conserves', 'معلبات', 'conserves', 'bi-archive', NULL, NULL, NULL, 12, 1, '2026-03-08 09:17:22', '2026-03-08 09:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `hri_customer`
--

CREATE TABLE `hri_customer` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_full_name` varchar(100) NOT NULL COMMENT 'Full name (FR or AR)',
  `customer_phone` varchar(20) NOT NULL COMMENT 'Primary login identifier, Moroccan format (e.g. 0612345678)',
  `customer_email` varchar(100) DEFAULT NULL COMMENT 'Optional email address',
  `customer_password` varchar(255) NOT NULL COMMENT 'bcrypt hashed password',
  `customer_address` varchar(255) DEFAULT NULL COMMENT 'Default delivery address',
  `customer_neighborhood` varchar(100) DEFAULT NULL COMMENT 'Quartier / neighborhood',
  `customer_city` varchar(50) NOT NULL DEFAULT 'Safi' COMMENT 'City — Safi for V1',
  `customer_notes` text DEFAULT NULL COMMENT 'Default delivery notes',
  `customer_preferred_lang` enum('fr','ar') NOT NULL DEFAULT 'fr' COMMENT 'UI language preference',
  `customer_is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=active, 0=banned/disabled',
  `customer_remember_token` varchar(255) DEFAULT NULL COMMENT '"Remember me" session token',
  `customer_last_login` datetime DEFAULT NULL,
  `customer_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `customer_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registered customer accounts';

--
-- Dumping data for table `hri_customer`
--

INSERT INTO `hri_customer` (`customer_id`, `customer_full_name`, `customer_phone`, `customer_email`, `customer_password`, `customer_address`, `customer_neighborhood`, `customer_city`, `customer_notes`, `customer_preferred_lang`, `customer_is_active`, `customer_remember_token`, `customer_last_login`, `customer_created_at`, `customer_updated_at`) VALUES
(1, 'Hamza Boussalham', '0708816321', 'hamzaboussalham@gmail.com', '$2y$12$0BVsC2BwHMNQyuFhKyT69elZS5TWQtqxUo/lrQGwv3S1zj.G182H6', '60 Rue aman najah el amir', 'changitt', 'Safi', NULL, 'fr', 1, NULL, NULL, '2026-03-08 12:16:46', '2026-03-12 13:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `hri_livreur`
--

CREATE TABLE `hri_livreur` (
  `livreur_id` int(10) UNSIGNED NOT NULL,
  `livreur_name` varchar(100) NOT NULL,
  `livreur_phone` varchar(20) DEFAULT NULL,
  `livreur_is_active` tinyint(1) DEFAULT 1,
  `livreur_created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hri_livreur`
--

INSERT INTO `hri_livreur` (`livreur_id`, `livreur_name`, `livreur_phone`, `livreur_is_active`, `livreur_created_at`) VALUES
(4, 'Hamza', '0620283725', 1, '2026-03-11 15:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `hri_order`
--

CREATE TABLE `hri_order` (
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(30) NOT NULL COMMENT 'Human-readable ref e.g. HRI-20260101-001',
  `order_customer_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → hri_customer (NULL for guests)',
  `order_customer_name` varchar(100) NOT NULL COMMENT 'Name snapshot at checkout',
  `order_customer_phone` varchar(20) NOT NULL COMMENT 'Phone snapshot at checkout',
  `order_customer_address` varchar(255) NOT NULL COMMENT 'Address snapshot at checkout',
  `order_customer_neighborhood` varchar(100) DEFAULT NULL COMMENT 'Quartier snapshot',
  `order_customer_city` varchar(50) NOT NULL DEFAULT 'Safi',
  `order_notes` text DEFAULT NULL COMMENT 'Customer delivery instructions',
  `order_subtotal` decimal(10,2) NOT NULL COMMENT 'Items total before delivery fee (DH)',
  `order_delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Delivery fee charged (DH)',
  `order_total` decimal(10,2) NOT NULL COMMENT 'Grand total = subtotal + delivery_fee (DH)',
  `order_item_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total distinct items in cart',
  `order_status` enum('pending','confirmed','preparing','out_for_delivery','delivered','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Current fulfillment status',
  `order_payment_method` varchar(30) NOT NULL DEFAULT 'cod' COMMENT 'Payment method (cod = cash on delivery)',
  `order_whatsapp_sent` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = WhatsApp tab was opened by customer',
  `order_language` enum('fr','ar') NOT NULL DEFAULT 'fr' COMMENT 'UI language used at checkout',
  `order_ip_address` varchar(45) DEFAULT NULL COMMENT 'Customer IP for security logging',
  `order_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `order_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_delivered_at` datetime DEFAULT NULL COMMENT 'Timestamp when marked delivered',
  `order_livreur_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Customer orders — WhatsApp COD checkout';

--
-- Dumping data for table `hri_order`
--

INSERT INTO `hri_order` (`order_id`, `order_number`, `order_customer_id`, `order_customer_name`, `order_customer_phone`, `order_customer_address`, `order_customer_neighborhood`, `order_customer_city`, `order_notes`, `order_subtotal`, `order_delivery_fee`, `order_total`, `order_item_count`, `order_status`, `order_payment_method`, `order_whatsapp_sent`, `order_language`, `order_ip_address`, `order_created_at`, `order_updated_at`, `order_delivered_at`, `order_livreur_id`) VALUES
(27, 'MK-20260312-AA40', 1, 'Hamza Boussalham', '0708816321', '60 Rue aman najah el amir', NULL, 'Safi', '', 33.00, 15.00, 48.00, 2, 'confirmed', 'cod', 0, 'fr', '::1', '2026-03-12 13:35:15', '2026-03-12 13:44:38', NULL, 4),
(28, 'MK-20260312-E66D', 1, 'Hamza Boussalham', '0708816321', '60 Rue aman najah el amir', NULL, 'Safi', '', 203.00, 0.00, 203.00, 4, 'out_for_delivery', 'cod', 0, 'fr', '::1', '2026-03-12 14:24:41', '2026-03-12 15:48:18', NULL, 4),
(29, 'MK-20260312-D8E3', 1, 'Hamza Boussalham', '0708816321', '60 Rue aman najah el amir', '', 'Safi', 'yoo', 50.00, 15.00, 65.00, 1, 'pending', 'cod', 0, 'fr', '::1', '2026-03-12 15:12:12', '2026-03-12 15:12:12', NULL, 4),
(30, 'MK-20260312-2C2A', 1, 'Hamza Boussalham', '0708816321', '60 Rue aman najah el amir', 'changitt', 'Safi', 'wayeeh', 20.50, 15.00, 35.50, 2, 'pending', 'cod', 0, 'fr', '::1', '2026-03-12 15:21:33', '2026-03-12 15:21:33', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `hri_order_item`
--

CREATE TABLE `hri_order_item` (
  `order_item_id` int(10) UNSIGNED NOT NULL,
  `order_item_order_id` int(10) UNSIGNED NOT NULL COMMENT 'FK → hri_order.order_id',
  `order_item_product_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → hri_product (NULL if product later deleted)',
  `order_item_name_fr` varchar(200) NOT NULL COMMENT 'Product name FR snapshot',
  `order_item_name_ar` varchar(200) NOT NULL COMMENT 'Product name AR snapshot',
  `order_item_unit_price` decimal(10,2) NOT NULL COMMENT 'Price per unit at order time (DH)',
  `order_item_quantity` decimal(10,2) NOT NULL DEFAULT 1.00 COMMENT 'Ordered quantity',
  `order_item_unit` varchar(20) NOT NULL DEFAULT 'piece' COMMENT 'Unit of measure snapshot',
  `order_item_subtotal` decimal(10,2) NOT NULL COMMENT 'unit_price × quantity (DH)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Line items within an order — prices snapshotted at checkout';

--
-- Dumping data for table `hri_order_item`
--

INSERT INTO `hri_order_item` (`order_item_id`, `order_item_order_id`, `order_item_product_id`, `order_item_name_fr`, `order_item_name_ar`, `order_item_unit_price`, `order_item_quantity`, `order_item_unit`, `order_item_subtotal`) VALUES
(62, 27, 8, 'Fine Couscous 1KG Al itkane', 'كسكس ناعم 1 كيلو الاتكان', 16.50, 2.00, 'kg', 33.00),
(63, 28, 3, 'Huile d\'olive extra vierge 1L', 'زيت الزيتون البكر الممتاز 1 لتر', 50.00, 1.00, 'liter', 50.00),
(64, 28, 6, 'Test / bebe', 'اختبار / بيبي', 120.00, 1.00, 'unit', 120.00),
(65, 28, 7, 'Fine Couscous 1KG Al itkane', 'كسكس ناعم 1 كيلو الاتكان', 16.50, 2.00, 'kg', 33.00),
(66, 29, 3, 'Huile d\'olive extra vierge 1L', 'زيت الزيتون البكر الممتاز 1 لتر', 50.00, 1.00, 'liter', 50.00),
(67, 30, 4, 'Eau minérale Sidi Ali 1,5L', 'ماء معدني سيدي علي 1.5 لتر', 4.50, 1.00, 'liter', 4.50),
(68, 30, 8, 'Fine Couscous 1KG Al itkane', 'كسكس ناعم 1 كيلو الاتكان', 16.00, 1.00, 'kg', 16.00);

-- --------------------------------------------------------

--
-- Table structure for table `hri_product`
--

CREATE TABLE `hri_product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL COMMENT 'FK → hri_category.category_id',
  `product_name_fr` varchar(200) NOT NULL COMMENT 'Product name in French',
  `product_name_ar` varchar(200) NOT NULL COMMENT 'Product name in Arabic',
  `product_slug` varchar(220) NOT NULL COMMENT 'URL-safe slug — unique per product',
  `product_description_fr` text DEFAULT NULL COMMENT 'Full description in French',
  `product_description_ar` text DEFAULT NULL COMMENT 'Full description in Arabic',
  `product_price` decimal(10,2) NOT NULL COMMENT 'Regular price in DH (MAD)',
  `product_sale_price` decimal(10,2) DEFAULT NULL COMMENT 'Promotional price in DH — NULL = no sale',
  `product_unit` enum('kg','piece','pack','liter','gram','unit') NOT NULL DEFAULT 'piece' COMMENT 'Unit of measure',
  `product_unit_step` decimal(5,2) NOT NULL DEFAULT 1.00 COMMENT 'Cart quantity increment (e.g. 0.5 for ½ kg)',
  `product_stock_quantity` int(11) NOT NULL DEFAULT 0 COMMENT 'Available stock — 0 = out of stock',
  `product_image` varchar(255) NOT NULL DEFAULT 'default_product.png' COMMENT 'Main image filename (assets/uploads/products/)',
  `product_image_thumb` varchar(255) DEFAULT NULL COMMENT 'Thumbnail filename (generated by PHP GD)',
  `product_is_featured` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = show in homepage featured section',
  `product_is_on_sale` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = show sale badge overlay',
  `product_is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = visible to customers',
  `product_view_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Page-view counter for basic analytics',
  `product_sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Custom sort within category',
  `product_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `product_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product catalog — prices in MAD, bilingual FR/AR';

--
-- Dumping data for table `hri_product`
--

INSERT INTO `hri_product` (`product_id`, `product_category_id`, `product_name_fr`, `product_name_ar`, `product_slug`, `product_description_fr`, `product_description_ar`, `product_price`, `product_sale_price`, `product_unit`, `product_unit_step`, `product_stock_quantity`, `product_image`, `product_image_thumb`, `product_is_featured`, `product_is_on_sale`, `product_is_active`, `product_view_count`, `product_sort_order`, `product_created_at`, `product_updated_at`) VALUES
(2, 7, 'Lait Central entier 1L', 'حليب سنطرال كامل الدسم 1 لتر', 'lait-central-entier-1l', 'Lait pasteurisé entier Central, 1 litre. Riche en calcium et protéines.', 'حليب مبستر كامل الدسم من سنطرال، 1 لتر. غني بالكالسيوم والبروتينات.', 7.00, NULL, 'liter', 1.00, 80, 'product_69b0a1129f3ea_1773183250.webp', NULL, 1, 0, 1, 0, 0, '2026-03-08 09:17:22', '2026-03-10 22:54:10'),
(3, 7, 'Huile d\'olive extra vierge 1L', 'زيت الزيتون البكر الممتاز 1 لتر', 'huile-olive-extra-vierge-1l', 'Huile d\'olive marocaine extra vierge, première pression à froid. Bouteille 1 litre. y\'es', 'زيت زيتون مغربي بكر ممتاز، عصر بارد أول. زجاجة 1 لتر.', 55.00, 50.00, 'liter', 1.00, 50, 'product_69ada7925d436_1772988306.jpg', NULL, 1, 1, 1, 0, 0, '2026-03-08 09:17:22', '2026-03-11 02:07:28'),
(4, 7, 'Eau minérale Sidi Ali 1,5L', 'ماء معدني سيدي علي 1.5 لتر', 'eau-minerale-sidi-ali-1-5l', 'Eau minérale naturelle Sidi Ali, bouteille 1,5 litre. Source des montagnes du Moyen Atlas.', 'ماء معدني طبيعي سيدي علي، قارورة 1.5 لتر. من ينابيع جبال الأطلس المتوسط.', 4.50, NULL, 'liter', 1.00, 200, 'product_69ada6f8d8b29_1772988152.jpg', NULL, 1, 0, 1, 0, 0, '2026-03-08 09:17:22', '2026-03-08 16:42:32'),
(6, 7, 'Test / bebe', 'اختبار / بيبي', 'test-bebe', 'dunoo just test', 'اختبار / بيبي', 100.00, 120.00, 'piece', 1.00, 10, 'product_69ad67abed5c9_1772971947.png', NULL, 1, 1, 1, 0, 0, '2026-03-08 12:12:27', '2026-03-12 15:01:48'),
(7, 6, 'Fine Couscous 1KG Al itkane', 'كسكس ناعم 1 كيلو الاتكان', 'fine-couscous-1kg-al-itkane', 'Discover Alitkane Fine Couscous 1KG, the premium semolina for your traditional dishes. Finely milled and light, it guarantees a perfect texture for every preparation. Ideal for couscous, tagines, and side dishes, this premium semolina offers an authentic and natural flavor. Easy to cook and versatile, it transforms your meals into true family feasts. Choose Alitkane for an exceptional culinary experience every time.', 'اكتشف اليتكاني كسكس ناعم 1 كجم، السميد الفاخر لأطباقك التقليدية. مطحون جيدًا وخفيف الوزن، مما يضمن ملمسًا مثاليًا لكل تحضير. مثالي لتحضير الكسكس والطواجن والأطباق الجانبية، هذا السميد الفاخر يقدم نكهة أصيلة وطبيعية. سهل الطهي ومتعدد الاستخدامات، فهو يحول وجباتك إلى وليمة عائلية حقيقية. اختر Alitkane لتجربة طهي استثنائية في كل مرة.', 16.50, 15.00, 'kg', 1.00, 20, 'product_69aecfd15b7d6_1773064145.webp', NULL, 0, 0, 1, 0, 0, '2026-03-09 13:49:05', '2026-03-11 01:50:55'),
(8, 6, 'Fine Couscous 1KG Al itkane', 'كسكس ناعم 1 كيلو الاتكان', 'fine-couscous-1kg-al-itkane-1773069653', 'Discover Alitkane Fine Couscous 1KG, the premium semolina for your traditional dishes. Finely milled and light, it guarantees a perfect texture for every preparation. Ideal for couscous, tagines, and side dishes, this premium semolina offers an authentic and natural flavor. Easy to cook and versatile, it transforms your meals into true family feasts. Choose Alitkane for an exceptional culinary experience every time.', 'اكتشف اليتكاني كسكس ناعم 1 كجم، السميد الفاخر لأطباقك التقليدية. مطحون جيدًا وخفيف الوزن، مما يضمن ملمسًا مثاليًا لكل تحضير. مثالي لتحضير الكسكس والطواجن والأطباق الجانبية، هذا السميد الفاخر يقدم نكهة أصيلة وطبيعية. سهل الطهي ومتعدد الاستخدامات، فهو يحول وجباتك إلى وليمة عائلية حقيقية. اختر Alitkane لتجربة طهي استثنائية في كل مرة.', 16.50, 16.00, 'kg', 1.00, 20, 'product_69aee5557a7c1_1773069653.webp', NULL, 1, 1, 1, 0, 0, '2026-03-09 15:20:53', '2026-03-12 14:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `hri_settings`
--

CREATE TABLE `hri_settings` (
  `setting_id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL COMMENT 'Machine-readable key (permanent)',
  `setting_value` text DEFAULT NULL COMMENT 'Stored value',
  `setting_type` enum('text','number','boolean','json') NOT NULL DEFAULT 'text' COMMENT 'Value type for validation',
  `setting_group` varchar(50) NOT NULL DEFAULT 'general' COMMENT 'Grouping for admin UI',
  `setting_label_fr` varchar(150) DEFAULT NULL COMMENT 'Human-readable label (FR)',
  `setting_label_ar` varchar(150) DEFAULT NULL COMMENT 'Human-readable label (AR)',
  `setting_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Site-wide configuration key-value store';

--
-- Dumping data for table `hri_settings`
--

INSERT INTO `hri_settings` (`setting_id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `setting_label_fr`, `setting_label_ar`, `setting_updated_at`) VALUES
(1, 'store_name_fr', 'Matjar El Kotobia', 'text', 'general', 'Nom du magasin (FR)', 'اسم المتجر (فرنسية)', '2026-03-08 09:17:22'),
(2, 'store_name_ar', 'متجر الكتابية', 'text', 'general', 'Nom du magasin (AR)', 'اسم المتجر (عربية)', '2026-03-08 09:17:22'),
(3, 'store_phone', '062000083', 'text', 'contact', 'Téléphone du magasin', 'هاتف المتجر', '2026-03-11 01:52:30'),
(4, 'store_whatsapp', '212620283725', 'text', 'contact', 'Numéro WhatsApp', 'رقم واتساب', '2026-03-11 13:31:57'),
(5, 'store_email', 'contact@matjarelkotobia.ma', 'text', 'contact', 'Email du magasin', 'بريد المتجر', '2026-03-08 09:17:22'),
(6, 'store_address', 'Safi, Maroc', 'text', 'contact', 'Adresse du magasin', 'عنوان المتجر', '2026-03-08 09:17:22'),
(7, 'store_city', 'Safi', 'text', 'contact', 'Ville', 'المدينة', '2026-03-08 09:17:22'),
(8, 'minimum_order_amount', '20', 'number', 'orders', 'Montant minimum commande (DH)', 'الحد الأدنى للطلب (درهم)', '2026-03-11 01:54:39'),
(9, 'delivery_fee', '15', 'number', 'orders', 'Frais de livraison (DH)', 'رسوم التوصيل (درهم)', '2026-03-11 01:54:25'),
(10, 'free_delivery_threshold', '200', 'number', 'orders', 'Seuil livraison gratuite (DH)', 'حد التوصيل المجاني (درهم)', '2026-03-11 01:54:25'),
(13, 'maintenance_mode', '0', 'boolean', 'general', 'Mode maintenance', 'وضع الصيانة', '2026-03-11 02:17:37'),
(14, 'default_language', 'fr', 'text', 'general', 'Langue par défaut', 'اللغة الافتراضية', '2026-03-08 09:17:22'),
(15, 'css_version', '1.0.0', 'text', 'general', 'Version du CSS (Cache-busting)', 'إصدار ملف CSS', '2026-03-09 15:17:40'),
(16, 'preparation_whatsapp', '212708816321', 'text', 'contact', 'Numéro WhatsApp de Ouvrier', 'رقم واتساب التحضير (العامل)', '2026-03-12 16:01:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hri_admin`
--
ALTER TABLE `hri_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `uq_admin_username` (`admin_username`),
  ADD UNIQUE KEY `uq_admin_email` (`admin_email`),
  ADD KEY `idx_admin_username` (`admin_username`),
  ADD KEY `idx_admin_email` (`admin_email`),
  ADD KEY `idx_admin_is_active` (`admin_is_active`);

--
-- Indexes for table `hri_category`
--
ALTER TABLE `hri_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_category_slug` (`category_slug`),
  ADD KEY `idx_category_slug` (`category_slug`),
  ADD KEY `idx_category_active_order` (`category_is_active`,`category_display_order`);

--
-- Indexes for table `hri_customer`
--
ALTER TABLE `hri_customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `uq_customer_phone` (`customer_phone`),
  ADD KEY `idx_customer_phone` (`customer_phone`),
  ADD KEY `idx_customer_email` (`customer_email`),
  ADD KEY `idx_customer_city` (`customer_city`),
  ADD KEY `idx_customer_is_active` (`customer_is_active`);

--
-- Indexes for table `hri_livreur`
--
ALTER TABLE `hri_livreur`
  ADD PRIMARY KEY (`livreur_id`);

--
-- Indexes for table `hri_order`
--
ALTER TABLE `hri_order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `uq_order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_order_customer` (`order_customer_id`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_order_date` (`order_created_at`),
  ADD KEY `idx_order_status_date` (`order_status`,`order_created_at`),
  ADD KEY `fk_order_livreur` (`order_livreur_id`);

--
-- Indexes for table `hri_order_item`
--
ALTER TABLE `hri_order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_item_order` (`order_item_order_id`),
  ADD KEY `idx_order_item_product` (`order_item_product_id`);

--
-- Indexes for table `hri_product`
--
ALTER TABLE `hri_product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `uq_product_slug` (`product_slug`),
  ADD KEY `idx_product_category` (`product_category_id`),
  ADD KEY `idx_product_slug` (`product_slug`),
  ADD KEY `idx_product_active` (`product_is_active`),
  ADD KEY `idx_product_featured` (`product_is_featured`,`product_is_active`),
  ADD KEY `idx_product_sale` (`product_is_on_sale`,`product_is_active`),
  ADD KEY `idx_product_price` (`product_price`),
  ADD KEY `idx_product_stock` (`product_stock_quantity`);
ALTER TABLE `hri_product` ADD FULLTEXT KEY `idx_product_search` (`product_name_fr`,`product_name_ar`,`product_description_fr`,`product_description_ar`);

--
-- Indexes for table `hri_settings`
--
ALTER TABLE `hri_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `uq_setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`),
  ADD KEY `idx_setting_group` (`setting_group`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hri_admin`
--
ALTER TABLE `hri_admin`
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hri_category`
--
ALTER TABLE `hri_category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hri_customer`
--
ALTER TABLE `hri_customer`
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hri_livreur`
--
ALTER TABLE `hri_livreur`
  MODIFY `livreur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hri_order`
--
ALTER TABLE `hri_order`
  MODIFY `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hri_order_item`
--
ALTER TABLE `hri_order_item`
  MODIFY `order_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `hri_product`
--
ALTER TABLE `hri_product`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hri_settings`
--
ALTER TABLE `hri_settings`
  MODIFY `setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hri_order`
--
ALTER TABLE `hri_order`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`order_customer_id`) REFERENCES `hri_customer` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_livreur` FOREIGN KEY (`order_livreur_id`) REFERENCES `hri_livreur` (`livreur_id`) ON DELETE SET NULL;

--
-- Constraints for table `hri_order_item`
--
ALTER TABLE `hri_order_item`
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_item_order_id`) REFERENCES `hri_order` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_item_product` FOREIGN KEY (`order_item_product_id`) REFERENCES `hri_product` (`product_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hri_product`
--
ALTER TABLE `hri_product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`product_category_id`) REFERENCES `hri_category` (`category_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ================================================================
-- FILE: sql/sample_products.sql
-- PURPOSE: Sample product data for testing/demo
-- NOTE: Run AFTER matjar_el_kotobia.sql
-- ================================================================
-- TODO: Add sample INSERT statements for demo products


-- ================================================================
-- ================================================================
--                        SEED DATA
-- ================================================================
-- ================================================================


-- ================================================================
-- SEED: Default super-admin account
-- IMPORTANT: Replace the password hash before deploying to production.
--   Generate with PHP: password_hash('YourStrongPassword', PASSWORD_BCRYPT)
-- ================================================================
INSERT INTO `hri_admin`
    (`admin_username`, `admin_email`, `admin_password`, `admin_full_name`, `admin_role`)
VALUES
    (
        'admin',
        'admin@matjarelkotobia.ma',
        '$2y$12$0.Rqa8pKKdYt9w8nRiV5d.qQRpt6Ix1G5A.MqrGZ7wywpx/HAFwCW',
        'Administrateur Principal',
        'super_admin'
    );


-- ================================================================
-- SEED: Store settings
-- ================================================================
INSERT INTO `hri_settings`
    (`setting_key`, `setting_value`, `setting_type`, `setting_group`, `setting_label_fr`, `setting_label_ar`)
VALUES
    ('store_name_fr',           'Matjar El Kotobia',        'text',    'general',  'Nom du magasin (FR)',         'اسم المتجر (فرنسية)'),
    ('store_name_ar',           'متجر الكتابية',            'text',    'general',  'Nom du magasin (AR)',         'اسم المتجر (عربية)'),
    ('store_phone',             '0600000000',               'text',    'contact',  'Téléphone du magasin',        'هاتف المتجر'),
    ('store_whatsapp',          '212600000000',             'text',    'contact',  'Numéro WhatsApp',             'رقم واتساب'),
    ('store_email',             'contact@matjarelkotobia.ma','text',   'contact',  'Email du magasin',            'بريد المتجر'),
    ('store_address',           'Safi, Maroc',              'text',    'contact',  'Adresse du magasin',          'عنوان المتجر'),
    ('store_city',              'Safi',                     'text',    'contact',  'Ville',                       'المدينة'),
    ('minimum_order_amount',    '50',                       'number',  'orders',   'Montant minimum commande (DH)','الحد الأدنى للطلب (درهم)'),
    ('delivery_fee',            '10',                       'number',  'orders',   'Frais de livraison (DH)',     'رسوم التوصيل (درهم)'),
    ('free_delivery_threshold', '200',                      'number',  'orders',   'Seuil livraison gratuite (DH)','حد التوصيل المجاني (درهم)'),
    ('store_hours_fr',          'Lun–Sam : 8h–22h',        'text',    'general',  'Horaires d\'ouverture (FR)',  'أوقات العمل (فرنسية)'),
    ('store_hours_ar',          'الإثنين–السبت: 8–22',     'text',    'general',  'Horaires d\'ouverture (AR)',  'أوقات العمل (عربية)'),
    ('maintenance_mode',        '0',                        'boolean', 'general',  'Mode maintenance',            'وضع الصيانة'),
    ('default_language',        'fr',                       'text',    'general',  'Langue par défaut',           'اللغة الافتراضية');


-- ================================================================
-- SEED: Product categories
-- ================================================================
INSERT INTO `hri_category`
    (`category_name_fr`, `category_name_ar`, `category_slug`, `category_icon`, `category_display_order`)
VALUES
    ('Fruits & Légumes',    'فواكه وخضروات',        'fruits-legumes',    'bi-apple',     1),
    ('Viandes & Volailles', 'لحوم ودواجن',          'viandes-volailles', 'bi-egg-fried', 2),
    ('Poissons',            'أسماك',                'poissons',          'bi-water',     3),
    ('Produits Laitiers',   'منتجات الحليب',        'produits-laitiers', 'bi-cup-straw', 4),
    ('Boulangerie',         'مخبوزات',              'boulangerie',       'bi-basket',    5),
    ('Épicerie',            'بقالة',                'epicerie',          'bi-box',       6),
    ('Boissons',            'مشروبات',              'boissons',          'bi-droplet',   7),
    ('Surgelés',            'مجمدات',               'surgeles',          'bi-snow',      8),
    ('Hygiène & Beauté',    'نظافة وجمال',          'hygiene-beaute',    'bi-stars',     9),
    ('Bébé',                'مستلزمات الأطفال',     'bebe',              'bi-balloon',  10),
    ('Ménage',              'مستلزمات المنزل',      'menage',            'bi-house',    11),
    ('Conserves',           'معلبات',               'conserves',         'bi-archive',  12);


-- ================================================================
-- SEED: 5 sample products
-- Categories used (matching slugs above):
--   category_id 1 = Fruits & Légumes
--   category_id 4 = Produits Laitiers
--   category_id 6 = Épicerie
--   category_id 7 = Boissons
-- ================================================================

-- Product 1 — Tomates (Fruits & Légumes)
INSERT INTO `hri_product` (
    `product_category_id`,
    `product_name_fr`,
    `product_name_ar`,
    `product_slug`,
    `product_description_fr`,
    `product_description_ar`,
    `product_price`,
    `product_sale_price`,
    `product_unit`,
    `product_unit_step`,
    `product_stock_quantity`,
    `product_image`,
    `product_is_featured`,
    `product_is_on_sale`,
    `product_is_active`
) VALUES (
    1,
    'Tomates fraîches',
    'طماطم طازجة',
    'tomates-fraiches',
    'Tomates fraîches cultivées localement à Safi. Idéales pour salades, sauces et tajines.',
    'طماطم طازجة محلية من سافي. مثالية للسلطات والصلصات والطاجين.',
    7.00,
    NULL,
    'kg',
    0.50,
    100,
    'tomates-fraiches.jpg',
    1,
    0,
    1
);

-- Product 2 — Lait Central (Produits Laitiers)
INSERT INTO `hri_product` (
    `product_category_id`,
    `product_name_fr`,
    `product_name_ar`,
    `product_slug`,
    `product_description_fr`,
    `product_description_ar`,
    `product_price`,
    `product_sale_price`,
    `product_unit`,
    `product_unit_step`,
    `product_stock_quantity`,
    `product_image`,
    `product_is_featured`,
    `product_is_on_sale`,
    `product_is_active`
) VALUES (
    4,
    'Lait Central entier 1L',
    'حليب سنطرال كامل الدسم 1 لتر',
    'lait-central-entier-1l',
    'Lait pasteurisé entier Central, 1 litre. Riche en calcium et protéines.',
    'حليب مبستر كامل الدسم من سنطرال، 1 لتر. غني بالكالسيوم والبروتينات.',
    7.00,
    NULL,
    'liter',
    1.00,
    80,
    'lait-central-1l.jpg',
    0,
    0,
    1
);

-- Product 3 — Huile d'olive (Épicerie) — on sale
INSERT INTO `hri_product` (
    `product_category_id`,
    `product_name_fr`,
    `product_name_ar`,
    `product_slug`,
    `product_description_fr`,
    `product_description_ar`,
    `product_price`,
    `product_sale_price`,
    `product_unit`,
    `product_unit_step`,
    `product_stock_quantity`,
    `product_image`,
    `product_is_featured`,
    `product_is_on_sale`,
    `product_is_active`
) VALUES (
    6,
    'Huile d\'olive extra vierge 1L',
    'زيت الزيتون البكر الممتاز 1 لتر',
    'huile-olive-extra-vierge-1l',
    'Huile d\'olive marocaine extra vierge, première pression à froid. Bouteille 1 litre.',
    'زيت زيتون مغربي بكر ممتاز، عصر بارد أول. زجاجة 1 لتر.',
    55.00,
    45.00,
    'liter',
    1.00,
    50,
    'huile-olive-1l.jpg',
    1,
    1,
    1
);

-- Product 4 — Eau Sidi Ali 1.5L (Boissons) — featured
INSERT INTO `hri_product` (
    `product_category_id`,
    `product_name_fr`,
    `product_name_ar`,
    `product_slug`,
    `product_description_fr`,
    `product_description_ar`,
    `product_price`,
    `product_sale_price`,
    `product_unit`,
    `product_unit_step`,
    `product_stock_quantity`,
    `product_image`,
    `product_is_featured`,
    `product_is_on_sale`,
    `product_is_active`
) VALUES (
    7,
    'Eau minérale Sidi Ali 1,5L',
    'ماء معدني سيدي علي 1.5 لتر',
    'eau-minerale-sidi-ali-1-5l',
    'Eau minérale naturelle Sidi Ali, bouteille 1,5 litre. Source des montagnes du Moyen Atlas.',
    'ماء معدني طبيعي سيدي علي، قارورة 1.5 لتر. من ينابيع جبال الأطلس المتوسط.',
    4.50,
    NULL,
    'liter',
    1.00,
    200,
    'sidi-ali-1-5l.jpg',
    1,
    0,
    1
);

-- Product 5 — Couscous Dari 1kg (Épicerie)
INSERT INTO `hri_product` (
    `product_category_id`,
    `product_name_fr`,
    `product_name_ar`,
    `product_slug`,
    `product_description_fr`,
    `product_description_ar`,
    `product_price`,
    `product_sale_price`,
    `product_unit`,
    `product_unit_step`,
    `product_stock_quantity`,
    `product_image`,
    `product_is_featured`,
    `product_is_on_sale`,
    `product_is_active`
) VALUES (
    6,
    'Couscous Dari grain moyen 1kg',
    'كسكس داري حبة متوسطة 1 كلغ',
    'couscous-dari-grain-moyen-1kg',
    'Couscous de blé dur Dari, grain moyen. Sachet 1 kg. Idéal pour le couscous traditionnel du vendredi.',
    'كسكس من القمح الصلب داري، حبة متوسطة. كيس 1 كلغ. مثالي لكسكس يوم الجمعة التقليدي.',
    18.50,
    NULL,
    'kg',
    1.00,
    120,
    'couscous-dari-1kg.jpg',
    0,
    0,
    1
);


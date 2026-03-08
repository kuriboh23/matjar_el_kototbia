<?php
/**
 * FILE: index.php
 * PURPOSE: Homepage with PRD naming and New Design aesthetic.
 */

require_once __DIR__ . '/config/config.php';

global $current_language, $is_rtl, $lang;

$page_title = $lang['site_name'] . ' - ' . $lang['site_tagline'];

// Data Fetching
$category_list      = get_active_categories();
$featured_products  = get_featured_products(12);

include_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <!-- Category bar (Circular Design) -->
    <div class="hri-category-bar no-scrollbar">
        <?php foreach ($category_list as $cat): ?>
            <a href="<?php echo SITE_URL; ?>/pages/category.php?slug=<?php echo $cat['category_slug']; ?>" class="hri-category-bar__item">
                <div class="hri-category-bar__icon">
                    <?php if (!empty($cat['category_image'])): ?>
                        <img src="<?php echo UPLOAD_URL . 'categories/' . $cat['category_image']; ?>" alt="<?php echo htmlspecialchars($current_language === 'ar' ? $cat['category_name_ar'] : $cat['category_name_fr']); ?>">
                    <?php else: ?>
                        <i class="bi <?php echo $cat['category_icon'] ?? 'bi-grid'; ?>"></i>
                    <?php endif; ?>
                </div>
                <span class="hri-category-bar__label"><?php echo htmlspecialchars($current_language === 'ar' ? $cat['category_name_ar'] : $cat['category_name_fr']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Hero Banner -->
    <div class="hri-hero-banner shadow-sm mb-4">
        <img src="<?php echo SITE_URL; ?>/assets/images/banners/hero_banner.gif" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='https://placehold.co/800x200/f5f5f5/9e9e9e?text=Matjar+El+Kotobia'">
        <div class="hri-hero-banner__content">
            <h5 class="text-white fw-bold mb-0"><?php echo $lang['site_name']; ?></h5>
            <p class="text-white-50 small mb-0"><?php echo $lang['site_tagline']; ?></p>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="hri-section-header">
        <h2 class="hri-section-title"><?php echo $current_language === 'ar' ? 'منتجات مختارة' : 'Produits en vedette'; ?></h2>
        <a href="<?php echo SITE_URL; ?>/pages/products.php" class="view-all"><?php echo $current_language === 'ar' ? 'عرض الكل' : 'Voir tout'; ?></a>
    </div>

    <div class="row g-3">
        <?php foreach ($featured_products as $product_data): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <?php include __DIR__ . '/pages/_product_card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Services Grid (Nos Services) -->
    <section class="hri-services-section shadow-sm">
        <h2 class="hri-services-section__header"><?php echo $current_language === 'ar' ? 'خدماتنا' : 'Nos services'; ?></h2>
        <div class="hri-services-grid">
            <div class="hri-service-card">
                <i data-lucide="truck"></i>
                <span class="hri-service-title"><?php echo $current_language === 'ar' ? 'توصيل في آسفي' : 'Livraison Safi'; ?></span>
                <p class="hri-service-desc"><?php echo $current_language === 'ar' ? 'توصيل سريع لباب منزلك' : 'Livraison rapide à domicile'; ?></p>
            </div>
            <div class="hri-service-card">
                <i data-lucide="wallet" class="icon-green"></i>
                <span class="hri-service-title"><?php echo $current_language === 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery'; ?></span>
                <p class="hri-service-desc"><?php echo $current_language === 'ar' ? 'ادفع عند استلام طلبك' : 'Paiement à la livraison'; ?></p>
            </div>
            <div class="hri-service-card">
                <i data-lucide="shield-check"></i>
                <span class="hri-service-title"><?php echo $current_language === 'ar' ? 'جودة مضمونة' : 'Qualité Garantie'; ?></span>
                <p class="hri-service-desc"><?php echo $current_language === 'ar' ? 'منتجات طازجة ومختارة' : 'Produits frais et sélectionnés'; ?></p>
            </div>
            <div class="hri-service-card">
                <i data-lucide="headset" class="icon-orange"></i>
                <span class="hri-service-title"><?php echo $current_language === 'ar' ? 'دعم 7/7' : 'Support 7j/7'; ?></span>
                <p class="hri-service-desc"><?php echo $current_language === 'ar' ? 'فريقنا في خدمتكم' : 'Notre équipe à votre écoute'; ?></p>
            </div>
        </div>
    </section>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>

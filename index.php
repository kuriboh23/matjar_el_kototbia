<?php
/**
 * FILE: index.php
 * PURPOSE: Homepage with Jumia-inspired layout and new Moroccan Green identity.
 */

require_once __DIR__ . '/config/config.php';

global $current_language, $is_rtl, $lang;

$page_title = $lang['site_name'] . ' - ' . $lang['site_tagline'];

// Data Fetching
$category_list      = get_active_categories();
$featured_products  = get_featured_products(12);
$sale_products      = get_sale_products(6);

include_once __DIR__ . '/includes/header.php';
?>

<div class="container py-3">
    <!-- Desktop: Layout Adjustment -->
    <div class="row g-3 justify-content-center">
        
        <!-- Center Column: Hero Carousel (Wider now that sidebar is gone) -->
        <div class="col-lg-9 col-12">
            <div id="heroCarousel" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="<?php echo SITE_URL; ?>/assets/images/banners/banner1.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" onerror="this.src='https://placehold.co/800x300/2E7D32/white?text=Promotion+Ramadan'">
                        <div class="carousel-caption d-none d-md-block text-start" style="left: 5%; bottom: 10%;">
                            <h2 class="fw-bold">Matjar El Kotobia</h2>
                            <p>Qualité et fraîcheur à votre porte.</p>
                            <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn hri-btn-orange text-white px-4 py-2 rounded-pill fw-bold">DÉCOUVRIR</a>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="<?php echo SITE_URL; ?>/assets/images/banners/banner2.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" onerror="this.src='https://placehold.co/800x300/FF8F00/white?text=Ventes+Flash'">
                        <div class="carousel-caption d-none d-md-block text-start" style="left: 5%; bottom: 10%;">
                            <h2 class="fw-bold">Ventes Flash</h2>
                            <p>Jusqu'à -50% sur une sélection de produits.</p>
                            <a href="<?php echo SITE_URL; ?>/pages/products.php?filter=sale" class="btn hri-btn-orange text-white px-4 py-2 rounded-pill fw-bold">DÉCOUVRIR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick-Action Cards -->
        <div class="col-lg-3 d-none d-lg-flex flex-column gap-3">
            <div class="card border-0 shadow-sm p-3 flex-grow-1 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-question-circle fs-3 text-warning"></i>
                <div>
                    <h6 class="fw-bold mb-1 small">Assistance</h6>
                    <p class="text-muted mb-0" style="font-size: 0.7rem;">Service client</p>
                </div>
            </div>
            <div class="card border-0 shadow-sm p-3 flex-grow-1 d-flex flex-row align-items-center gap-3" style="cursor:pointer;" onclick="window.open('https://wa.me/<?php echo STORE_WHATSAPP_NUMBER; ?>')">
                <i class="bi bi-whatsapp fs-3" style="color: var(--color-whatsapp);"></i>
                <div>
                    <h6 class="fw-bold mb-1 small">WhatsApp</h6>
                    <p class="text-muted mb-0" style="font-size: 0.7rem;">Commander vite</p>
                </div>
            </div>
            <div class="card border-0 shadow-sm p-3 flex-grow-1 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-shop fs-3 text-secondary"></i>
                <div>
                    <h6 class="fw-bold mb-1 small">Magasin</h6>
                    <p class="text-muted mb-0" style="font-size: 0.7rem;">Horaires & infos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Pills Removed as requested -->

    <!-- Promo Shortcut Row -->
    <div class="mt-3 overflow-auto d-flex gap-3 pb-3 hri-category-bar">
        <?php 
        $shortcuts = [
            ['icon' => 'bi-tag', 'label' => 'Offres du jour'],
            ['icon' => 'bi-lightning', 'label' => 'Ventes Flash'],
            ['icon' => 'bi-truck', 'label' => 'Livraison Gratuite'],
            ['icon' => 'bi-plus-circle', 'label' => 'Nouveautés'],
            ['icon' => 'bi-telephone', 'label' => 'Appeler le magasin'],
            ['icon' => 'bi-shop-window', 'label' => 'Boutique Officielle'],
            ['icon' => 'bi-percent', 'label' => 'Promos'],
            ['icon' => 'bi-person-plus', 'label' => 'Nouveaux Clients']
        ];
        foreach ($shortcuts as $s): 
        ?>
            <div class="text-center flex-shrink-0" style="width: 120px;">
                <div class="rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center py-3 px-2" style="background-color: var(--color-primary-dark); height: 85px;">
                    <i class="bi <?php echo $s['icon']; ?> fs-3 text-white mb-1"></i>
                    <span class="text-white small d-block" style="font-size: 0.7rem;"><?php echo $s['label']; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Featured Products Section -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><?php echo $lang['featured']; ?></h4>
            <a href="<?php echo SITE_URL; ?>/pages/products.php" class="text-primary text-decoration-none fw-bold small">VOIR PLUS <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="row g-2 g-md-3">
            <?php foreach ($featured_products as $product_data): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <?php include __DIR__ . '/pages/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>

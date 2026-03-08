<?php
/**
 * FILE: pages/about.php
 * PURPOSE: Static about page with store information, mission, location.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$page_title = translate('about_us') . ' - ' . $lang['site_name'];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h1 class="h2 fw-bold mb-4 text-center"><?= translate('about_us') ?></h1>
                
                <div class="mb-5 text-center">
                    <img src="<?= SITE_URL ?>/assets/images/logo/logo.png" alt="<?= $lang['site_name'] ?>" class="img-fluid mb-4" style="max-height: 100px;" onerror="this.src='https://placehold.co/200x100/2E7D32/white?text=LOGO'">
                    <p class="lead text-muted">"سوبرماركت هري — تسوقك اليومي أونلاين"</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 fw-bold"><?= $current_language === 'ar' ? 'من نحن؟' : 'Qui sommes-nous ?' ?></h2>
                    <p class="text-muted">
                        <?= $current_language === 'ar' 
                            ? 'نحن متجر محلي في مدينة آسفي، نسعى لتوفير أفضل المنتجات الغذائية والمنزلية لعملائنا بكل سهولة وسرعة من خلال الطلب عبر واتساب.' 
                            : 'Nous sommes une supérette locale située à Safi, dédiée à fournir les meilleurs produits alimentaires et ménagers à nos clients avec simplicité et rapidité grâce à la commande via WhatsApp.' 
                        ?>
                    </p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 fw-bold"><?= $current_language === 'ar' ? 'مهمتنا' : 'Notre Mission' ?></h2>
                    <p class="text-muted">
                        <?= $current_language === 'ar' 
                            ? 'مهمتنا هي تبسيط تجربة التسوق اليومية لسكان مدينة آسفي من خلال منصة رقمية سهلة الاستخدام وتوصيل سريع إلى باب المنزل.' 
                            : 'Notre mission est de simplifier l\'expérience d\'achat quotidienne des habitants de Safi grâce à une plateforme numérique conviviale et une livraison rapide à domicile.' 
                        ?>
                    </p>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-truck fs-1 text-primary mb-2 d-block"></i>
                            <h3 class="h6 fw-bold mb-1">Livraison Rapide</h3>
                            <p class="small text-muted mb-0">Partout à Safi</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-shield-check fs-1 text-primary mb-2 d-block"></i>
                            <h3 class="h6 fw-bold mb-1">Qualité Garantie</h3>
                            <p class="small text-muted mb-0">Produits sélectionnés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

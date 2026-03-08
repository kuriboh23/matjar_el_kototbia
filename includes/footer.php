<?php
/**
 * FILE: includes/footer.php
 * PURPOSE: Customer-facing footer, mobile bottom nav, and closing scripts.
 */
?>
    </main>
    <!-- ===== MAIN CONTENT ENDS ===== -->

    <!-- ===== FOOTER ===== -->
    <footer class="hri-footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <!-- Store Info -->
                <div class="col-md-4 mb-3">
                    <h5><?= translate('site_name') ?></h5>
                    <p class="mb-1"><i class="bi bi-geo-alt"></i> <?= DEFAULT_CITY ?>, <?= DEFAULT_COUNTRY ?></p>
                    <p class="mb-1"><i class="bi bi-telephone"></i> <?= STORE_PHONE_DISPLAY ?></p>
                    <p class="mb-1"><i class="bi bi-whatsapp"></i> WhatsApp: <?= STORE_PHONE_DISPLAY ?></p>
                </div>
                <!-- Quick Links -->
                <div class="col-md-4 mb-3">
                    <h5><?= translate('categories') ?></h5>
                    <ul class="list-unstyled">
                        <?php
                        $footer_categories = get_active_categories();
                        $footer_cat_count = 0;
                        foreach ($footer_categories as $footer_cat):
                            if ($footer_cat_count >= 6) break; // Show max 6
                        ?>
                            <li><a href="<?= SITE_URL ?>/pages/category.php?slug=<?= $footer_cat['category_slug'] ?>" class="text-white-50"><?= get_category_name($footer_cat) ?></a></li>
                        <?php
                            $footer_cat_count++;
                        endforeach;
                        ?>
                    </ul>
                </div>
                <!-- About -->
                <div class="col-md-4 mb-3">
                    <h5><?= translate('about_us') ?></h5>
                    <p class="text-white-50"><?= translate('site_tagline') ?></p>
                    <p class="text-white-50"><i class="bi bi-clock"></i> <?= get_setting('store_hours_' . $current_language) ?? 'Lun-Sam: 8h-22h' ?></p>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center text-white-50 mb-0">
                &copy; <?= date('Y') ?> <?= SITE_NAME_FR ?> — <?= translate('all_rights_reserved') ?>
            </p>
        </div>
    </footer>

    <!-- ===== MOBILE BOTTOM NAVIGATION ===== -->
    <nav class="hri-mobile-nav d-lg-none">
        <a href="<?= SITE_URL ?>" class="hri-mobile-nav__item">
            <i class="bi bi-house"></i>
            <span><?= translate('home') ?></span>
        </a>
        <a href="<?= SITE_URL ?>/pages/products.php" class="hri-mobile-nav__item">
            <i class="bi bi-grid"></i>
            <span><?= translate('categories') ?></span>
        </a>
        <a href="<?= SITE_URL ?>/pages/cart.php" class="hri-mobile-nav__item position-relative">
            <i class="bi bi-cart3"></i>
            <span><?= translate('cart') ?></span>
            <span id="cart-badge-bottom" class="badge rounded-pill bg-danger position-absolute <?= $cart_count === 0 ? 'd-none' : '' ?>" style="top:0;right:15px;font-size:0.6rem;">
                <?= $cart_count ?>
            </span>
        </a>
        <a href="<?= is_customer_logged_in() ? SITE_URL . '/pages/profile.php' : SITE_URL . '/pages/login.php' ?>" class="hri-mobile-nav__item">
            <i class="bi bi-person"></i>
            <span><?= translate('my_account') ?></span>
        </a>
    </nav>

    <!-- ===== SCRIPTS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= SITE_URL ?>/assets/js/main.js"></script>
    <script src="<?= SITE_URL ?>/assets/js/cart.js"></script>
    <script src="<?= SITE_URL ?>/assets/js/search.js"></script>
</body>
</html>

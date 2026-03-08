<?php
/**
 * Footer Include
 * Matjar El Kotobia - Grocery E-commerce
 * 
 * Contains: Footer with contact info, links, closing scripts
 */

// Prevent direct access
if (!defined('SITE_ROOT')) {
    die('Direct access not permitted');
}

// Global language variables
global $current_language, $is_rtl, $lang, $category_list;

// Store info from constants
$store_name = ($current_language === 'ar') ? SITE_NAME_AR : SITE_NAME_FR;
$store_phone = STORE_WHATSAPP_NUMBER;
$store_address = DEFAULT_CITY . ', ' . DEFAULT_COUNTRY;
$current_year = date('Y');
?>

    </main><!-- End Main Content Wrapper -->
    
    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-4 pb-3">
        <div class="container">
            <div class="row g-4">
                <!-- Store Info Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo-white.png" alt="<?php echo htmlspecialchars($store_name); ?>" height="50" class="mb-2" onerror="this.style.display='none'">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($store_name); ?></h5>
                    </div>
                    <p class="text-light mb-3" style="opacity: 0.8;">
                        <?php echo ($current_language === 'ar') ? SITE_TAGLINE_AR : SITE_TAGLINE_FR; ?>
                    </p>
                    
                    <!-- Contact Info -->
                    <ul class="list-unstyled footer-contact">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill me-2 text-success"></i>
                            <span><?php echo htmlspecialchars($store_address); ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill me-2 text-success"></i>
                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', STORE_PHONE_DISPLAY); ?>" class="text-white text-decoration-none">
                                <?php echo htmlspecialchars(STORE_PHONE_DISPLAY); ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-whatsapp me-2" style="color: var(--color-whatsapp);"></i>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $store_phone); ?>" class="text-white text-decoration-none" target="_blank" rel="noopener">
                                WhatsApp
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Quick Links Column -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3"><?php echo $lang['quick_links'] ?? ($is_rtl ? 'روابط سريعة' : 'Liens Rapides'); ?></h6>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/index.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['home']; ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/pages/products.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['products']; ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['cart']; ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['profile'] ?? $lang['my_account']; ?>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Categories Column -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3"><?php echo $lang['categories']; ?></h6>
                    <ul class="list-unstyled footer-links">
                        <?php 
                        // Display first 6 categories in footer
                        $footer_categories = array_slice($category_list ?? [], 0, 6);
                        foreach ($footer_categories as $cat): 
                        ?>
                            <li class="mb-2">
                                <a href="<?php echo SITE_URL; ?>/pages/category.php?slug=<?php echo urlencode($cat['category_slug']); ?>" class="text-light text-decoration-none">
                                    <i class="bi bi-chevron-right me-1 small"></i>
                                    <?php echo htmlspecialchars($is_rtl ? $cat['category_name_ar'] : $cat['category_name_fr']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Info & Payment Column -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3"><?php echo $is_rtl ? 'معلومات' : 'Informations'; ?></h6>
                    <ul class="list-unstyled footer-links mb-4">
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/pages/about.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['about_us']; ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>
                                <?php echo $lang['contact_us']; ?>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Payment Method -->
                    <h6 class="text-uppercase fw-bold mb-3"><?php echo $is_rtl ? 'طريقة الدفع' : 'Paiement'; ?></h6>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-success py-2 px-3">
                            <i class="bi bi-cash-coin me-1"></i>
                            <?php echo $lang['payment_on_delivery']; ?>
                        </span>
                    </div>
                    
                    <!-- WhatsApp Order Button -->
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $store_phone); ?>?text=<?php echo urlencode('Bonjour, je voudrais commander...'); ?>" 
                       class="btn w-100" 
                       style="background-color: var(--color-whatsapp); color: white;"
                       target="_blank" 
                       rel="noopener">
                        <i class="bi bi-whatsapp me-2"></i>
                        <?php echo $lang['send_via_whatsapp']; ?>
                    </a>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            
            <!-- Bottom Footer -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 small" style="opacity: 0.8;">
                        &copy; <?php echo $current_year; ?> <?php echo htmlspecialchars($store_name); ?>. 
                        <?php echo $lang['all_rights_reserved']; ?>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="#" class="text-white me-3" title="Facebook" aria-label="Facebook">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="text-white me-3" title="Instagram" aria-label="Instagram">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $store_phone); ?>" class="text-white" title="WhatsApp" aria-label="WhatsApp" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Spacer for mobile bottom nav -->
    <div class="d-lg-none" style="height: 60px;"></div>
    
    <!-- JQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap 5.3 JS Bundle (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/cart.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/search.js"></script>
    
    <?php if (isset($page_scripts) && !empty($page_scripts)): ?>
        <!-- Page-specific Scripts -->
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo SITE_URL; ?>/assets/js/<?php echo htmlspecialchars($script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

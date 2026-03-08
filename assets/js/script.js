/**
 * FILE: assets/js/script.js
 * PURPOSE: Interaction logic for Matjar El Kotobia.
 */

$(document).ready(function() {
    
    // --- Drawer Logic ---
    const elDrawer = document.getElementById('hri-drawer');
    const elOverlay = document.getElementById('hri-drawer-overlay');
    const elOpenBtn = document.getElementById('hri-drawer-open');
    const elCloseBtn = document.getElementById('hri-drawer-close');

    if (elOpenBtn && elDrawer) {
        elOpenBtn.addEventListener('click', () => {
            elDrawer.classList.add('is-active');
            elOverlay.classList.add('is-active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    }

    if (elCloseBtn && elDrawer) {
        elCloseBtn.addEventListener('click', () => {
            elDrawer.classList.remove('is-active');
            elOverlay.classList.remove('is-active');
            document.body.style.overflow = '';
        });
    }

    if (elOverlay) {
        elOverlay.addEventListener('click', () => {
            elDrawer.classList.remove('is-active');
            elOverlay.classList.remove('is-active');
            document.body.style.overflow = '';
        });
    }

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Global functions for product card interaction
    window.addCardToCart = function(productId) {
        const $card = $(`.hri-product-card[data-product-id="${productId}"]`);
        const $addBtn = $card.find('.hri-btn-add-cart');
        const $control = $card.find('.hri-cart-control');
        const $qtyValue = $card.find('.hri-qty-value');

        $.ajax({
            url: SITE_URL + '/ajax/cart_add.php',
            type: 'POST',
            data: { product_id: productId, quantity: 1 },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $addBtn.hide();
                    $control.css('display', 'flex');
                    $qtyValue.text(1);
                    
                    showFlashBar();
                    updateHeaderCartCount(response.data.count);
                }
            }
        });
    };

    window.updateCardQty = function(productId, delta) {
        const $card = $(`.hri-product-card[data-product-id="${productId}"]`);
        const $addBtn = $card.find('.hri-btn-add-cart');
        const $control = $card.find('.hri-cart-control');
        const $qtyValue = $card.find('.hri-qty-value');
        
        let currentQty = parseInt($qtyValue.text());
        let newQty = currentQty + delta;

        if (newQty <= 0) {
            $.ajax({
                url: SITE_URL + '/ajax/cart_remove.php',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $control.hide();
                        $addBtn.show();
                        updateHeaderCartCount(response.data.count);
                    }
                }
            });
        } else {
            $.ajax({
                url: SITE_URL + '/ajax/cart_update.php',
                type: 'POST',
                data: { product_id: productId, quantity: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $qtyValue.text(newQty);
                        updateHeaderCartCount(response.data.count);
                    }
                }
            });
        }
    };

    function showFlashBar() {
        const $flashBar = $('#hri-flash-bar');
        if ($flashBar.length) {
            $flashBar.fadeIn().css('display', 'flex');
            setTimeout(() => {
                $flashBar.fadeOut();
            }, 3000);
        }
    }

    function updateHeaderCartCount(count) {
        const $badge = $('#hri-cart-badge');
        if ($badge.length) {
            $badge.text(count);
            if (count > 0) {
                $badge.removeClass('d-none');
            } else {
                $badge.addClass('d-none');
            }
        }
    }

    // Category sidebar toggle
    const $toggleBtn = $('#toggle-categories');
    if ($toggleBtn.length) {
        $toggleBtn.on('click', function() {
            const $extraCats = $('.extra-cat');
            $extraCats.toggleClass('d-none');
            const isHidden = $extraCats.first().hasClass('d-none');
            const lang = $('html').attr('lang');
            
            $(this).html(isHidden 
                ? `<i class="bi bi-chevron-down me-2"></i> ${lang === 'ar' ? 'فئات أخرى' : 'Autres catégories'}`
                : `<i class="bi bi-chevron-up me-2"></i> ${lang === 'ar' ? 'إخفاء' : 'Masquer'}`
            );
        });
    }

});

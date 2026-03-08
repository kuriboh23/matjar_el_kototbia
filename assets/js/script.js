/**
 * FILE: assets/js/script.js
 * PURPOSE: Interaction logic for Matjar El Kotobia - PRD compliant.
 */

// --- 1. Hero Loader Logic (Robust Implementation) ---
function hideHeroLoader() {
    const elHomeLoader = document.getElementById('hri-home-loader');
    if (elHomeLoader && !elHomeLoader.classList.contains('loader-hidden')) {
        setTimeout(() => {
            elHomeLoader.classList.add('loader-hidden');
        }, 800);
    }
}

// Check if page is already loaded
if (document.readyState === 'complete') {
    hideHeroLoader();
} else {
    window.addEventListener('load', hideHeroLoader);
}

$(document).ready(function() {
    
    // Safety fallback for loader
    setTimeout(hideHeroLoader, 3000);
    
    // --- 2. Drawer Logic ---
    const body = document.getElementById('hri-body');
    const elDrawerOpen = document.getElementById('hri-drawer-open');
    const elDrawerClose = document.getElementById('hri-drawer-close');
    const elOverlay = document.getElementById('hri-drawer-overlay');

    if (elDrawerOpen) {
        elDrawerOpen.addEventListener('click', () => {
            body.classList.add('drawer-open');
        });
    }

    if (elDrawerClose) {
        elDrawerClose.addEventListener('click', () => {
            body.classList.remove('drawer-open');
        });
    }

    if (elOverlay) {
        elOverlay.addEventListener('click', () => {
            body.classList.remove('drawer-open');
        });
    }

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // --- 3. Data Handler (Processing UI) ---
    const $dataHandler = $('#hri-data-handler');
    const $topBar = $('#hri-top-bar');
    const $dynamicLoaderIcon = $('#dynamic-loader-icon');

    function startProcessing(iconName = 'shopping-basket') {
        $dynamicLoaderIcon.attr('data-lucide', iconName);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        $('body').addClass('is-processing');
        $dataHandler.addClass('is-active');
        $topBar.addClass('is-visible').css('width', '40%');
    }

    function endProcessing() {
        $topBar.css('width', '100%');
        setTimeout(() => {
            $('body').removeClass('is-processing');
            $dataHandler.removeClass('is-active');
            $topBar.removeClass('is-visible').css('width', '0%');
        }, 300);
    }

    // --- 4. Product Card Actions ---
    const processingCards = new Set();

    window.refreshCartPage = function() {
        const $cartPage = $('#hri-cart-page-content');
        if ($cartPage.length) {
            $.ajax({
                url: HriApp.ajaxUrl + '/get_cart_html.php',
                type: 'GET',
                success: function(html) {
                    $cartPage.html(html);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }
    };

    window.addCardToCart = function(productId) {
        if (processingCards.has(productId)) return;
        
        const $card = $(`.hri-product-card[data-product-id="${productId}"]`);
        const $qtyNum = $card.find('.qty-num');

        processingCards.add(productId);
        startProcessing('shopping-cart');

        $.ajax({
            url: HriApp.ajaxUrl + '/cart_add.php',
            type: 'POST',
            data: { product_id: productId, quantity: 1 },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $card.addClass('is-active');
                    $qtyNum.text(1);
                    showFlashBar();
                    updateHeaderCartCount(response.data.count);
                    window.refreshCartPage();
                }
            },
            complete: function() {
                processingCards.delete(productId);
                endProcessing();
            }
        });
    };

    window.updateCardQty = function(productId, delta) {
        if (processingCards.has(productId)) return;

        const $card = $(`.hri-product-card[data-product-id="${productId}"]`);
        const $qtyNum = $card.find('.qty-num');
        
        let currentQty = parseInt($qtyNum.text()) || 0;
        let newQty = currentQty + delta;

        startProcessing(delta > 0 ? 'plus' : 'minus');

        if (newQty <= 0) {
            newQty = 0;
            processingCards.add(productId);
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_remove.php',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $card.removeClass('is-active');
                        $qtyNum.text(0);
                        updateHeaderCartCount(response.data.count);
                        window.refreshCartPage();
                    }
                },
                complete: function() {
                    processingCards.delete(productId);
                    endProcessing();
                }
            });
        } else {
            processingCards.add(productId);
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_update.php',
                type: 'POST',
                data: { product_id: productId, quantity: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $qtyNum.text(newQty);
                        updateHeaderCartCount(response.data.count);
                        window.refreshCartPage();
                    }
                },
                complete: function() {
                    processingCards.delete(productId);
                    endProcessing();
                }
            });
        }
    };

    // Dedicated Cart Page Functions
    window.updateCartItemQty = function(productId, delta) {
        if (processingCards.has(productId)) return;
        
        const $itemRow = $(`.hri-cart-item[data-product-id="${productId}"]`);
        const $qtyNum = $itemRow.find('.fw-bold.small');
        
        let currentQty = parseInt($qtyNum.text()) || 0;
        let newQty = currentQty + delta;

        startProcessing(delta > 0 ? 'plus' : 'minus');

        if (newQty <= 0) {
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_remove.php',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateHeaderCartCount(response.data.count);
                        window.refreshCartPage();
                    }
                },
                complete: function() {
                    endProcessing();
                }
            });
        } else {
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_update.php',
                type: 'POST',
                data: { product_id: productId, quantity: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateHeaderCartCount(response.data.count);
                        window.refreshCartPage();
                    }
                },
                complete: function() {
                    endProcessing();
                }
            });
        }
    };

    window.clearCartConfirm = function() {
        const msg = HriApp.currentLanguage === 'ar' ? 'هل تريد إفراغ السلة؟' : 'Voulez-vous vider votre panier ?';
        if (confirm(msg)) {
            startProcessing('trash-2');
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_clear.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateHeaderCartCount(0);
                        window.refreshCartPage();
                    }
                },
                complete: function() {
                    endProcessing();
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

    // --- 5. Search Experience Logic ---
    const $searchOverlay = $('#hri-search-overlay');
    const $searchInput = $('#main-search-input');
    const $searchResults = $('#hri-search-results');
    const $searchLoader = $('#hri-search-loader');

    window.openSearch = function() {
        $searchOverlay.addClass('is-active');
        $searchInput.focus();
        $('body').css('overflow', 'hidden');
    };

    window.closeSearch = function() {
        $searchOverlay.removeClass('is-active');
        $searchResults.empty();
        $searchInput.val('');
        $('body').css('overflow', '');
    };

    let typingTimer;
    if ($searchInput.length) {
        $searchInput.on('input', function() {
            clearTimeout(typingTimer);
            const query = $(this).val().trim();
            
            if (query.length < 2) {
                $searchResults.empty();
                $searchLoader.removeClass('is-visible');
                return;
            }

            $searchLoader.addClass('is-visible');
            
            typingTimer = setTimeout(() => {
                $.ajax({
                    url: HriApp.ajaxUrl + '/search_products.php',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(response) {
                        $searchLoader.removeClass('is-visible');
                        renderSearchResults(response.data, query);
                    },
                    error: function() {
                        $searchLoader.removeClass('is-visible');
                    }
                });
            }, 500);
        });
    }

    function renderSearchResults(data, query) {
        $searchResults.empty();
        if (!data || data.length === 0) {
            const noResultsMsg = HriApp.currentLanguage === 'ar' 
                ? `لا توجد نتائج لـ "${query}"` 
                : `Aucun produit trouvé pour "${query}"`;
            $searchResults.append(`<p style="text-align:center; color:#999; margin-top:40px;">${noResultsMsg}</p>`);
            return;
        }

        data.forEach((p, index) => {
            const item = $(`
                <a href="${HriApp.siteUrl}/pages/product_detail.php?slug=${p.product_slug}" class="result-item" style="animation-delay: ${index * 0.05}s">
                    <div class="result-img">
                        <img src="${p.image_url}" alt="${p.product_name}">
                    </div>
                    <div class="result-info">
                        <b>${p.product_name}</b>
                        <span>${p.price_display}</span>
                    </div>
                </a>
            `);
            $searchResults.append(item);
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // --- 6. Product Detail Specifics ---
    window.openLightbox = function() {
        const $mainImg = $('#mainImg');
        const $lightbox = $('#lightbox');
        const $lightboxImg = $('#lightboxImg');
        if ($mainImg.length && $lightbox.length) {
            $lightboxImg.attr('src', $mainImg.attr('src'));
            $lightbox.addClass('open');
            $('body').css('overflow', 'hidden');
        }
    };

    window.closeLightbox = function() {
        $('#lightbox').removeClass('open');
        if (!$('#hri-search-overlay').hasClass('is-active')) {
            $('body').css('overflow', '');
        }
    };

    window.initAcheterDetail = function(productId) {
        window.addCardToCartDetail(productId);
    };

    window.addCardToCartDetail = function(productId) {
        if (processingCards.has(productId)) return;
        const $wrapper = $('#actionWrap');
        const $qtyNum = $('#qty-display');
        processingCards.add(productId);
        startProcessing('shopping-cart');
        $.ajax({
            url: HriApp.ajaxUrl + '/cart_add.php',
            type: 'POST',
            data: { product_id: productId, quantity: 1 },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $wrapper.addClass('is-active');
                    $qtyNum.text(1);
                    showFlashBar();
                    updateHeaderCartCount(response.data.count);
                    $(`.hri-product-card[data-product-id="${productId}"]`).addClass('is-active').find('.qty-num').text(1);
                }
            },
            complete: function() {
                processingCards.delete(productId);
                endProcessing();
            }
        });
    };

    window.changeQtyDetail = function(productId, delta) {
        if (processingCards.has(productId)) return;
        const $wrapper = $('#actionWrap');
        const $qtyNum = $('#qty-display');
        let currentQty = parseInt($qtyNum.text()) || 0;
        let newQty = currentQty + delta;
        startProcessing(delta > 0 ? 'plus' : 'minus');
        if (newQty <= 0) {
            newQty = 0;
            processingCards.add(productId);
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_remove.php',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $wrapper.removeClass('is-active');
                        $qtyNum.text(0);
                        updateHeaderCartCount(response.data.count);
                        $(`.hri-product-card[data-product-id="${productId}"]`).removeClass('is-active').find('.qty-num').text(0);
                    }
                },
                complete: function() {
                    processingCards.delete(productId);
                    endProcessing();
                }
            });
        } else {
            processingCards.add(productId);
            $.ajax({
                url: HriApp.ajaxUrl + '/cart_update.php',
                type: 'POST',
                data: { product_id: productId, quantity: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $qtyNum.text(newQty);
                        updateHeaderCartCount(response.data.count);
                        $(`.hri-product-card[data-product-id="${productId}"]`).find('.qty-num').text(newQty);
                    }
                },
                complete: function() {
                    processingCards.delete(productId);
                    endProcessing();
                }
            });
        }
    };

});

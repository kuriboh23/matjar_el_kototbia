/**
 * FILE: assets/js/cart.js
 * PURPOSE: Client-side cart management using localStorage.
 *          Syncs with server-side session via AJAX endpoints.
 * GLOBAL VARS: hriCartItems, hriCartCount
 */

let hriCartItems = [];
let hriCartCount = 0;

// Load cart from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCartFromStorage();
    updateCartBadge();
});

/**
 * Add a product to the cart.
 * @param {number} productId - Product ID
 * @param {number} quantity  - Quantity to add
 */
function addToCart(productId, quantity = 1) {
    makeAjaxRequest(HriApp.ajaxUrl + '/cart_add.php', 'POST', {
        product_id: productId,
        quantity: quantity
    }).then(response => {
        if (response.success) {
            showToast(response.message || 'Ajouté au panier ✓', 'success');
            updateCartBadge();
            // Also update localStorage for offline reference
            saveCartToStorage();
        } else {
            showToast(response.message || 'Erreur', 'error');
        }
    }).catch(err => {
        console.error('Cart add error:', err);
        showToast('Erreur de connexion', 'error');
    });
}

/**
 * Update cart badge counters across all locations (header + mobile nav).
 */
function updateCartBadge() {
    makeAjaxRequest(HriApp.ajaxUrl + '/cart_count.php', 'GET').then(response => {
        const count = response.data ? response.data.count : 0;
        hriCartCount = count;

        // Update all badge elements
        const badges = document.querySelectorAll('#cart-badge, #cart-badge-mobile, #cart-badge-bottom');
        badges.forEach(badge => {
            badge.textContent = count;
            if (count > 0) {
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });
    });
}

function saveCartToStorage() {
    localStorage.setItem('hri_cart_backup', JSON.stringify(hriCartItems));
}

function loadCartFromStorage() {
    const stored = localStorage.getItem('hri_cart_backup');
    if (stored) {
        try { hriCartItems = JSON.parse(stored); } catch(e) { hriCartItems = []; }
    }
}

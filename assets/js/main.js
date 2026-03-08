/**
 * FILE: assets/js/main.js
 * PURPOSE: Global frontend JavaScript for Matjar El Kotobia.
 * DEPENDS ON: HriApp global object (set in header.php)
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Matjar El Kotobia loaded. Language:', HriApp.currentLanguage);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(el) { return new bootstrap.Tooltip(el); });
});

/**
 * Show a toast notification at the bottom of the screen.
 *
 * @param {string} message  - Message text
 * @param {string} type     - 'success', 'error', 'info'
 */
function showToast(message, type = 'success') {
    // Remove existing toasts
    const existing = document.querySelector('.hri-toast');
    if (existing) existing.remove();

    const bgClass = type === 'error' ? 'bg-danger' : type === 'info' ? 'bg-info' : 'bg-success';

    const toast = document.createElement('div');
    toast.className = `hri-toast alert ${bgClass} text-white py-2 px-4 shadow`;
    toast.textContent = message;
    document.body.appendChild(toast);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

/**
 * Make an AJAX request using fetch API.
 *
 * @param {string} url     - Request URL
 * @param {string} method  - 'GET' or 'POST'
 * @param {object} data    - Data to send (for POST)
 * @returns {Promise}
 */
async function makeAjaxRequest(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    };

    if (method === 'POST' && data) {
        const formData = new FormData();
        for (const key in data) {
            formData.append(key, data[key]);
        }
        formData.append(HriApp.csrfTokenName || 'hri_csrf_token', HriApp.csrfToken);
        options.body = formData;
    }

    const response = await fetch(url, options);
    return await response.json();
}

/**
 * Format a price number with currency.
 *
 * @param {number} amount
 * @returns {string} Formatted price (e.g., "74.50 DH")
 */
function formatPrice(amount) {
    return parseFloat(amount).toFixed(2) + ' ' + HriApp.currency;
}

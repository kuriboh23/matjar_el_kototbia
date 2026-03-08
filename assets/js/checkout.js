/**
 * FILE: assets/js/checkout.js
 * PURPOSE: Checkout form validation, customer info save/load,
 *          and WhatsApp message URL generation.
 */

document.addEventListener('DOMContentLoaded', function() {
    loadSavedCustomerInfo();
});

/**
 * Load previously saved customer info from localStorage.
 */
function loadSavedCustomerInfo() {
    const saved = localStorage.getItem('hri_customer_info');
    if (saved) {
        try {
            const info = JSON.parse(saved);
            const fields = ['customer_full_name', 'customer_phone', 'customer_address', 'customer_neighborhood'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (input && info[field]) {
                    input.value = info[field];
                }
            });
        } catch (e) {
            console.warn('Could not load saved customer info:', e);
        }
    }
}

/**
 * Save customer info to localStorage.
 */
function saveCustomerInfo() {
    const info = {
        customer_full_name: document.getElementById('customer_full_name')?.value || '',
        customer_phone: document.getElementById('customer_phone')?.value || '',
        customer_address: document.getElementById('customer_address')?.value || '',
        customer_neighborhood: document.getElementById('customer_neighborhood')?.value || '',
    };
    localStorage.setItem('hri_customer_info', JSON.stringify(info));
}

/**
 * Validate the checkout form before submission.
 * @returns {boolean}
 */
function validateCheckoutForm() {
    let isValid = true;
    const requiredFields = ['customer_full_name', 'customer_phone', 'customer_address'];

    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input || !input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    // Validate Moroccan phone format
    const phone = document.getElementById('customer_phone')?.value || '';
    const cleanPhone = phone.replace(/[\s\-\.]/g, '');
    if (!/^(0[5-7]\d{8}|(\+212|00212)[5-7]\d{8})$/.test(cleanPhone)) {
        document.getElementById('customer_phone').classList.add('is-invalid');
        isValid = false;
    }

    return isValid;
}

/**
 * Handle the WhatsApp checkout button click.
 * Saves order to DB via AJAX, then opens WhatsApp.
 */
function openWhatsappCheckout() {
    if (!validateCheckoutForm()) {
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
        return;
    }

    // Save info if checkbox is checked
    const saveCheckbox = document.getElementById('save_info');
    if (saveCheckbox && saveCheckbox.checked) {
        saveCustomerInfo();
    }

    // Collect form data
    const formData = {
        customer_full_name: document.getElementById('customer_full_name').value,
        customer_phone: document.getElementById('customer_phone').value,
        customer_address: document.getElementById('customer_address').value,
        customer_neighborhood: document.getElementById('customer_neighborhood')?.value || '',
        customer_city: document.getElementById('customer_city')?.value || 'Safi',
        order_notes: document.getElementById('order_notes')?.value || '',
    };

    // Send to server to save order
    makeAjaxRequest(HriApp.ajaxUrl + '/save_order.php', 'POST', formData)
        .then(response => {
            if (response.success && response.data && response.data.whatsapp_url) {
                // Open WhatsApp
                window.open(response.data.whatsapp_url, '_blank');
                showToast('Commande enregistrée ! Envoyez le message sur WhatsApp.', 'success');
            } else {
                showToast(response.message || 'Erreur lors de la commande.', 'error');
            }
        })
        .catch(err => {
            console.error('Checkout error:', err);
            showToast('Erreur de connexion. Réessayez.', 'error');
        });
}

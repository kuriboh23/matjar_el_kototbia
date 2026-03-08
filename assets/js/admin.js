/**
 * FILE: assets/js/admin.js
 * PURPOSE: Admin panel JavaScript interactions (confirm deletes, form validation, etc).
 */

document.addEventListener('DOMContentLoaded', function() {
    // Confirm before delete actions
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Êtes-vous sûr ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Image preview on upload
    const imageInput = document.getElementById('product_image_upload');
    const imagePreview = document.getElementById('product_image_preview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

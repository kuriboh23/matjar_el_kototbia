/**
 * FILE: assets/js/search.js
 * PURPOSE: Live AJAX product search with debounce.
 */

document.addEventListener('DOMContentLoaded', function() {
    const elSearchInput = document.getElementById('search-input');
    const elSearchResults = document.getElementById('search-results');

    if (!elSearchInput || !elSearchResults) return;

    let searchTimeout = null;

    elSearchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            elSearchResults.classList.add('d-none');
            elSearchResults.innerHTML = '';
            return;
        }

        // Debounce: wait 300ms after user stops typing
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Close search results on click outside
    document.addEventListener('click', function(e) {
        if (!elSearchInput.contains(e.target) && !elSearchResults.contains(e.target)) {
            elSearchResults.classList.add('d-none');
        }
    });

    function performSearch(query) {
        fetch(HriApp.ajaxUrl + '/search_products.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(response => {
                if (response.success && response.data && response.data.length > 0) {
                    renderSearchResults(response.data);
                } else {
                    elSearchResults.innerHTML = '<div class="p-3 text-muted text-center small">Aucun résultat</div>';
                    elSearchResults.classList.remove('d-none');
                }
            })
            .catch(err => console.error('Search error:', err));
    }

    function renderSearchResults(results) {
        let html = '';
        results.forEach(product => {
            html += '<a href="' + HriApp.siteUrl + '/pages/product_detail.php?slug=' + product.product_slug + '"'
                  + ' class="d-flex align-items-center p-2 text-decoration-none text-dark border-bottom hri-search-result-item">'
                  + '<img src="' + (product.image_url || '') + '" width="40" height="40" class="rounded me-2" style="object-fit:cover;">'
                  + '<div><strong class="small">' + (product.product_name || '') + '</strong>'
                  + '<br><small class="text-success">' + (product.price_display || '') + '</small></div>'
                  + '</a>';
        });
        elSearchResults.innerHTML = html;
        elSearchResults.classList.remove('d-none');
    }
});

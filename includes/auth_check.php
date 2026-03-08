<?php
/**
 * FILE: includes/auth_check.php
 * PURPOSE: Customer authentication middleware.
 *          Include this at the top of any page that requires login.
 *          Redirects to login page if customer is not authenticated.
 */

if (!is_customer_logged_in()) {
    set_flash_message('warning', translate('error_login_required') ?: 'Veuillez vous connecter.');
    redirect(SITE_URL . '/pages/login.php?redirect=' . urlencode(get_current_url()));
}

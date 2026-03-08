<?php
/**
 * FILE: pages/logout.php
 * PURPOSE: Log out customer and redirect to home.
 */

require_once __DIR__ . '/../config/config.php';

logout_customer();
redirect(SITE_URL . '/index.php');

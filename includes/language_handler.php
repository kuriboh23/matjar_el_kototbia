<?php
/**
 * FILE: includes/language_handler.php
 * PURPOSE: Detect, set, and load the correct language (French or Arabic).
 * CREATES GLOBALS:
 *   $current_language — 'fr' or 'ar'
 *   $lang             — Translations array from lang/fr.php or lang/ar.php
 *   $is_rtl           — true if Arabic (RTL layout)
 *   $text_direction   — 'rtl' or 'ltr'
 *   $font_family      — CSS font-family for current language
 */

/* ----------------------------------------------------------------
 * DETECTION PRIORITY:
 * 1. URL parameter: ?lang=ar
 * 2. Cookie: hri_language
 * 3. Session variable
 * 4. Customer DB preference (if logged in)
 * 5. DEFAULT_LANGUAGE constant ('fr')
 * ---------------------------------------------------------------- */

$current_language = DEFAULT_LANGUAGE;

// Priority 1: URL parameter
if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
    $current_language = $_GET['lang'];
    // Save to cookie and session
    setcookie(LANG_COOKIE_NAME, $current_language, time() + LANG_COOKIE_EXPIRY, '/');
    $_SESSION['hri_current_language'] = $current_language;

// Priority 2: Cookie
} elseif (isset($_COOKIE[LANG_COOKIE_NAME]) && in_array($_COOKIE[LANG_COOKIE_NAME], SUPPORTED_LANGUAGES)) {
    $current_language = $_COOKIE[LANG_COOKIE_NAME];
    $_SESSION['hri_current_language'] = $current_language;

// Priority 3: Session
} elseif (isset($_SESSION['hri_current_language']) && in_array($_SESSION['hri_current_language'], SUPPORTED_LANGUAGES)) {
    $current_language = $_SESSION['hri_current_language'];
}

/* ----------------------------------------------------------------
 * LOAD LANGUAGE FILE
 * ---------------------------------------------------------------- */
$lang_file = SITE_ROOT . '/lang/' . $current_language . '.php';
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    // Fallback to French
    require_once SITE_ROOT . '/lang/fr.php';
    $current_language = 'fr';
}

/* ----------------------------------------------------------------
 * SET DIRECTION VARIABLES
 * ---------------------------------------------------------------- */
$is_rtl         = ($current_language === 'ar');
$text_direction = $is_rtl ? 'rtl' : 'ltr';
$font_family    = $is_rtl ? "'Cairo', sans-serif" : "'Poppins', sans-serif";

/**
 * Load the language array for a specific code.
 * Updates global variables.
 *
 * @param  string $code  Language code ('fr' or 'ar')
 * @return void
 */
function load_language(string $code): void
{
    global $current_language, $lang, $is_rtl, $text_direction, $font_family;

    if (in_array($code, SUPPORTED_LANGUAGES)) {
        $current_language = $code;
        $lang_file = SITE_ROOT . '/lang/' . $code . '.php';
        if (file_exists($lang_file)) {
            require $lang_file; // This re-defines $lang
        }
        $is_rtl         = ($code === 'ar');
        $text_direction = $is_rtl ? 'rtl' : 'ltr';
        $font_family    = $is_rtl ? "'Cairo', sans-serif" : "'Poppins', sans-serif";
    }
}

/* ----------------------------------------------------------------
 * SET LANGUAGE FUNCTION (for switching)
 * ---------------------------------------------------------------- */
function set_language(string $language_code): void
{
    if (in_array($language_code, SUPPORTED_LANGUAGES)) {
        setcookie(LANG_COOKIE_NAME, $language_code, time() + LANG_COOKIE_EXPIRY, '/');
        $_SESSION['hri_current_language'] = $language_code;

        // If customer is logged in, update their preference
        if (is_customer_logged_in()) {
            execute_query(
                "UPDATE hri_customer SET customer_preferred_lang = :lang WHERE customer_id = :id",
                [':lang' => $language_code, ':id' => get_current_customer_id()]
            );
        }
    }
}

/**
 * Get text direction for current language.
 *
 * @return string  'rtl' or 'ltr'
 */

/** 
*function get_direction(): string
*{
  *  global $text_direction;
 *   return $text_direction;
*}
*/
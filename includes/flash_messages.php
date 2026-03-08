<?php
/**
 * FILE: includes/flash_messages.php
 * PURPOSE: Session-based flash messages (success, error, info, warning).
 *          Messages persist for exactly one page load, then disappear.
 */

/**
 * Set a flash message in session.
 *
 * @param  string $type     Message type: 'success', 'error', 'info', 'warning'
 * @param  string $message  The message text
 * @return void
 */
function set_flash_message(string $type, string $message): void
{
    $_SESSION['hri_flash_messages'][] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Get all flash messages and clear them from session.
 *
 * @return array  Array of flash message arrays
 */
function get_flash_messages(): array
{
    $messages = $_SESSION['hri_flash_messages'] ?? [];
    unset($_SESSION['hri_flash_messages']);
    return $messages;
}

/**
 * Render flash messages as Bootstrap alert HTML.
 *
 * @return string  HTML string of alerts
 */
function render_flash_messages(): string
{
    $messages = get_flash_messages();
    $html = '';

    foreach ($messages as $flash) {
        $alert_class = 'alert-info'; // default
        switch ($flash['type']) {
            case 'success': $alert_class = 'alert-success'; break;
            case 'error':   $alert_class = 'alert-danger';  break;
            case 'warning': $alert_class = 'alert-warning'; break;
            case 'info':    $alert_class = 'alert-info';    break;
        }
        $safe_message = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');
        $html .= '<div class="alert ' . $alert_class . ' alert-dismissible fade show" role="alert">';
        $html .= $safe_message;
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        $html .= '</div>';
    }

    return $html;
}

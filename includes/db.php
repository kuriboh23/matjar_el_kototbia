<?php
/**
 * FILE: includes/db.php
 * PURPOSE: Establish a secure PDO connection to the MySQL database.
 * NOTE: This file defines database constants and creates the $db_connection object.
 */

/* ============================================================
 * DATABASE CONFIGURATION CONSTANTS
 * ============================================================ */
define('DB_HOST', 'localhost');
define('DB_NAME', 'matjar_el_kotobia_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/* ============================================================
 * PDO CONNECTION
 * ============================================================ */
try {
    // Data Source Name (DSN)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // PDO Options
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use actual prepared statements
    ];

    // Create the PDO instance (Global Connection Object)
    $db_connection = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    /* ----------------------------------------------------------------
     * ERROR HANDLING
     * In a production environment, errors should be logged to a file
     * and never displayed to the end user to prevent security leaks.
     * ---------------------------------------------------------------- */
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Stop execution and show a user-friendly message
    die("Error: Could not connect to the database. Please try again later.");
}
?>

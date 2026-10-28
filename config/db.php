<?php
/**
 * FEATHER FITS — CENTRAL DATABASE CONNECTION ENGINE
 * Features:
 * - Robust PDO connection with UTF-8 mb4 encoding
 * - Graceful fallback mode if MySQL credentials are not yet configured
 * - Singleton PDO connection
 */

// Load credentials if present
$credentialsPath = __DIR__ . '/db_credentials.php';
if (file_exists($credentialsPath)) {
    require_once $credentialsPath;
}

function getDBConnection() {
    static $pdoInstance = null;
    static $hasAttempted = false;

    if ($pdoInstance !== null) {
        return $pdoInstance;
    }

    if ($hasAttempted) {
        return null;
    }

    $hasAttempted = true;

    $host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: 'localhost');
    $db   = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: '');
    $user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: '');
    $pass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
    $port = defined('DB_PORT') ? DB_PORT : (getenv('DB_PORT') ?: '3306');

    // If database credentials are still default placeholders, return null (fallback mode)
    if (empty($db) || $db === 'u123456789_featherfits' || empty($user) || $user === 'u123456789_dbuser') {
        return null;
    }

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        $pdoInstance = new PDO($dsn, $user, $pass, $options);
        return $pdoInstance;
    } catch (PDOException $e) {
        error_log("Hostinger MySQL Connection Error: " . $e->getMessage());
        return null;
    }
}

function isMySQLActive() {
    return getDBConnection() !== null;
}

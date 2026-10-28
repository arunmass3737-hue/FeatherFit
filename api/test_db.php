<?php
/**
 * FEATHER FITS — HOSTINGER MYSQL DATABASE DIAGNOSTIC TEST
 * Accessible at: http://localhost:3005/api/test_db.php OR yourdomain.com/api/test_db.php
 */

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config/db.php';

$host = defined('DB_HOST') ? DB_HOST : 'localhost';
$db   = defined('DB_NAME') ? DB_NAME : '';
$user = defined('DB_USER') ? DB_USER : '';
$pass = defined('DB_PASS') ? DB_PASS : '';
$port = defined('DB_PORT') ? DB_PORT : '3306';

$response = [
    'timestamp' => date('c'),
    'configured_host' => $host,
    'configured_database' => $db,
    'configured_user' => $user,
    'status' => 'pending'
];

if (empty($db) || $db === 'u123456789_featherfits') {
    $response['status'] = 'not_configured';
    $response['message'] = 'Hostinger MySQL details not yet entered. Please open config/db_credentials.php and enter your Hostinger Database Name, User, and Password.';
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Test queries
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $tableCounts = [];
    foreach (['products', 'orders', 'users', 'admins', 'carriers', 'banners'] as $tbl) {
        if (in_array($tbl, $tables)) {
            $cnt = $pdo->query("SELECT COUNT(*) FROM `{$tbl}`")->fetchColumn();
            $tableCounts[$tbl] = (int)$cnt;
        }
    }

    $response['status'] = 'connected_successfully';
    $response['message'] = '🎉 Hostinger MySQL Connected Successfully!';
    $response['tables_found'] = count($tables);
    $response['tables'] = $tables;
    $response['counts'] = $tableCounts;

} catch (PDOException $e) {
    $response['status'] = 'connection_failed';
    $response['error_message'] = $e->getMessage();
    $response['troubleshooting_tips'] = [
        'Check if Database Name and User match exactly what Hostinger hPanel shows (including prefix like u123456789_).',
        'Verify your MySQL user password in Hostinger hPanel.',
        'Ensure Host is set to localhost (Hostinger phpMyAdmin and web server run on localhost).',
        'Have you imported database/featherfits_schema.sql in Hostinger phpMyAdmin?'
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

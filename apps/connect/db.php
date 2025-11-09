<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Set environment variables for database credentials
$env = parse_ini_file(__DIR__ . '/../../.env', true);
$db_host = $env['DB_HOST'];
$db_user = $env['DB_USER'];
$db_pass = $env['DB_PASSWORD'];
$db_database = $env['DB_DATABASE'];
// Set API key from environment variable
$apiKey = $env['API_KEY'];

try {
    // Create a new PDO instance with secure settings
    
    $db = new PDO(
        'mysql:host=' . $db_host . ';dbname=' . $db_database,
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Log the error and send a more informative error message to the user
    header("Location: ../apps/admin/error.php" );
    exit;
}

try {
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_database",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Log error or redirect
    // error_log($e->getMessage());
    header("Location: ../apps/admin/error.php");
    exit;
}

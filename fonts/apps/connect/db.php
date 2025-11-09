<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Database credentials (hardcoded)

$db_host = "sql103.infinityfree.com";
$db_user = "if0_40367315";
$db_pass = "7JwnkNeioVi";
$db_database = "if0_40367315_movie_db";

// API key
$apiKey = "your_api_key_here";

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
    echo "Connected successfully!";
} catch (PDOException $e) {
    // Log error or redirect
    // error_log($e->getMessage());
    header("Location: ../apps/admin/error.php");
    exit;
}

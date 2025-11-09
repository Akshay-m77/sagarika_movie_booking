<?php
// Include the database connection code
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('connect.php');

// Decode the JSON data from the request
$inputData = file_get_contents("php://input");
$requestData = json_decode($inputData, true);

// Log the incoming request data for debugging

// Function to handle errors
function handle_error($message) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("error" => $message));
    exit();
}

// User ID to delete from the request data
$userId = isset($requestData['userId']) ? $requestData['userId'] : null;

file_put_contents("x.txt", "User ID: " . print_r($userId, true)); // Log the user ID

if (!$userId) {
    handle_error("User ID not provided");
}

try {
    // Get the list of tables in the database
    $stmt_tables = $db->query("SHOW TABLES");
    $tables = $stmt_tables->fetchAll(PDO::FETCH_COLUMN);

    // Begin a transaction
    $db->beginTransaction();

    // Iterate through each table
    foreach ($tables as $table) {
        // Check if the table contains a foreign key referencing the user ID
        $stmt_foreign_key = $db->prepare("SELECT COUNT(*) FROM information_schema.key_column_usage WHERE table_name = ? AND column_name = 'U_ID'");
        $stmt_foreign_key->execute([$table]);
        $has_foreign_key = $stmt_foreign_key->fetchColumn();

        if ($has_foreign_key) {
            // If the table contains a foreign key, construct and execute a DELETE statement
            $stmt_delete = $db->prepare("DELETE FROM $table WHERE U_ID = ?");
            $stmt_delete->execute([$userId]);
        }
    }

    // Commit the transaction
    $db->commit();

    // Respond with a success message
    echo json_encode(array("success" => "Records deleted successfully"));
} catch (PDOException $e) {
    // Rollback the transaction if an error occurs
    $db->rollBack();

    // Handle the exception (e.g., display an error message)
    handle_error("Database error: " . $e->getMessage());
}
?>

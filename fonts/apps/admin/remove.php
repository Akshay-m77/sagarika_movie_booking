<?php
// Include the database connection code
include("auth.php");
include('../connect/db.php');
$Log_Id=$_SESSION['SESS_ADMIN_ID'];

// User ID to delete
$uid = $_GET['uid'];

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
            $stmt_delete->execute([$uid]);
        }
    }

    // Commit the transaction
    $db->commit();

    // For demonstration purposes, redirect back to the previous page with a success message
    header("Location: ".$_SERVER['HTTP_REFERER']."?success=Records deleted successfully");
    exit();
} catch (PDOException $e) {
    // Rollback the transaction if an error occurs
    $db->rollBack();

    // Handle the exception (e.g., display an error message)
    echo "Error: " . $e->getMessage();
    // You might want to log the error or redirect to an error page
}
?>

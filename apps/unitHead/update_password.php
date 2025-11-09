<?php
session_start(); // Start the session

// Include your database connection file
include('../connect/db.php');

// Check if the form data is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data from POST parameters
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if new password is not null and has at least 6 characters
    if (empty($newPassword)) {
        echo "<script>alert('New password cannot be empty.'); window.location.href = 'profile.php';</script>";
        exit;
    }
    if (strlen($newPassword) < 4) {
        echo "<script>alert('New password must have at least 4 characters.'); window.location.href = 'profile.php';</script>";
        exit;
    }

    // Check if new password and confirm password match
    if ($confirmPassword != $newPassword) {
        echo "<script>alert('New password and confirmation password do not match.'); window.location.href = 'profile.php';</script>";
        exit;
    }

    // Assuming you have a session or some identifier for the user, fetch user data from the database
    $uid = $_SESSION['user_id'];
    $sql = "SELECT PASSWORD FROM admin WHERE ID = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $uid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the stored hashed password
    $storedPassword = $row['PASSWORD'];

    // Verify if the current password matches the hashed password stored in the database
    if (password_verify($currentPassword, $storedPassword)) {
        // Current password is correct, proceed with updating the password

        // Hash the new password before storing it
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateSql = "UPDATE admin SET PASSWORD = :new_password WHERE ID = :user_id";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->bindParam(':new_password', $hashedNewPassword);
        $updateStmt->bindParam(':user_id', $uid);
        if ($updateStmt->execute()) {
            // Password updated successfully
            echo "<script>alert('Password updated successfully.'); window.location.href = 'index.php';</script>";
            exit;
        } else {
            // Error updating password
            echo "<script>alert('Error updating password.'); window.location.href = 'profile.php';</script>";
        }
    } else {
        // Current password is incorrect
        echo "<script>alert('Current password is incorrect.'); window.location.href = 'profile.php';</script>";
    }
} else {
    // Handle the case where the form data is not submitted via POST method
    echo "<script>alert('Form data not submitted via POST method.'); window.location.href = 'profile.php';</script>";
}
?>

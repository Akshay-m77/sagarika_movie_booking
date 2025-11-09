<?php
// remove_schedule.php
include("auth.php");
include('../connect/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sid"])) {
    // Include your database connection code here

    $sid = $_POST["sid"];

    $stmt = $db->prepare("UPDATE schedules SET STATUS = 1 WHERE S_ID = :sid");
    $stmt->bindParam(":sid", $sid, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Update successful
        echo "Success";
    } else {
        // Update failed
        echo "Error";
    }
} else {
    // Invalid request
    echo "Invalid request";
}
?>

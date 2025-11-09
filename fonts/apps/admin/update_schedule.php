<?php
include("auth.php");

include('../connect/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sid'])) {
    $sid = $_POST['sid'];

    // Update schedule status to 5
    $stmt = $db->prepare("UPDATE schedules SET STATUS = 2 WHERE S_ID = :sid");
    $stmt->bindParam(':sid', $sid);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request";
}
?>

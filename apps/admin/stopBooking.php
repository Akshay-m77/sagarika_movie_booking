<?php
include("auth.php");
include('../connect/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check raw POST data
    $rawPostData = file_get_contents('php://input');

    // Sanitize and validate sid
    $data = json_decode($rawPostData, true); // Decode the JSON payload
    $sid = filter_var($data['sid'], FILTER_SANITIZE_NUMBER_INT); // Sanitize sid


    // Check if sid is valid
    if (!$sid) {
        echo json_encode(["status" => "error", "message" => "No valid sid received"]);
        exit;
    }

    // Prepare and execute the statement
    $stmt = $db->prepare("UPDATE schedules SET STATUS = 2 WHERE S_ID = :sid");
    $stmt->bindParam(':sid', $sid);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Booking stopped."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error stopping booking."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

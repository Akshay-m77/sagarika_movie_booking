<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');

include "connect.php";

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the request data from the raw input
    $requestData = json_decode(file_get_contents("php://input"), true);

    // Check if userId and bookingId are provided
    if (isset($requestData['userId']) && isset($requestData['bookingId'])) {
        // Prepare SQL statement
        $sql = "DELETE FROM booking WHERE U_ID = ? AND BOOKING_ID = ?";
        $stmt = $db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(1, $requestData['userId']);
        $stmt->bindParam(2, $requestData['bookingId']);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Ticket cancellation successful
            $response = ['success' => true, 'message' => 'Ticket cancelled successfully'];
            http_response_code(200);
            echo json_encode($response);
        } else {
            // Ticket cancellation failed
            $response = ['success' => false, 'message' => 'Failed to cancel ticket'];
            http_response_code(500);
            echo json_encode($response);
        }
    } else {
        // If userId or bookingId is missing, return an error response
        $response = ['success' => false, 'message' => 'Missing userId or bookingId'];
        http_response_code(400);
        echo json_encode($response);
    }
} else {
    // If the request method is not DELETE, return an error response
    $response = ['success' => false, 'message' => 'Only DELETE requests are allowed'];
    http_response_code(405);
    echo json_encode($response);
}

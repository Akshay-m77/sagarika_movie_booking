<?php

include 'connect.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON data sent from the Flutter application
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract feedback and rating from the JSON data
    $feedback = $data['feedback'];
    $rating = $data['rating'];
    $title = $data['title'];
    $uid = $data['uid'];
    $desc = $data['feedback'];

    // Prepare SQL statement to insert feedback data into the database
    $sql = "INSERT INTO feedback (U_ID, SERVICES, DESCRIPTION, RATING) VALUES (:uid, :title, :desc, :rating)";
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':desc', $desc);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    try {
        $stmt->execute();
        // Return success response
        echo json_encode(array("status" => "success", "message" => "Feedback submitted successfully"));
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(array("status" => "error", "message" => "Error submitting feedback: " . $e->getMessage()));
    }
} else {
    // Return error response if the request method is not POST
    echo json_encode(array("status" => "error", "message" => "Only POST requests are allowed"));
}

?>

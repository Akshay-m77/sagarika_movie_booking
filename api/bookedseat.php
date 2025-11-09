<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include the database connection file
include 'connect.php';

// Get the raw POST data (since it's sent as JSON)
$inputData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($inputData, true);

// Check if data is successfully decoded
if ($data) {
    // Retrieve data from the JSON payload
    $date = $data['date'];
    $time = $data['time'];
    $mid = $data['mid'];


    // Format date and time strings
    $formattedDate = date('Y-m-d', strtotime($date)); // Convert date string to 'Y-m-d' format
    $formattedTime = date('H:i:s', strtotime($time)); // Convert time string to 'H:i:s' format

    // Prepare SQL statement to retrieve booked seats
    $sql = "SELECT SEAT_NO FROM booking WHERE S_ID IN (SELECT S_ID FROM schedules WHERE M_ID = :mid AND DATE = :date AND SHOW_TIME = :time)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':mid', $mid);
    $stmt->bindParam(':date', $formattedDate);
    $stmt->bindParam(':time', $formattedTime);

    // Execute the query
    try {
        $stmt->execute();
        $bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Handle query execution error
        die("Query failed: " . $e->getMessage());
    }

    // Append the predefined string to the booked seats array
    $predefinedSeats = explode(',', "BB18,BB17,BB16,BB15,GH8,GH9,GH10,GH11,GH12,GH13,GH14,GH15");
    $mergedSeats = array_merge($bookedSeats, $predefinedSeats);

    // Encode the merged seats array as JSON
    $jsonResponse = json_encode($mergedSeats);

    // Return the JSON response
    echo $jsonResponse;

} else {
    // Handle JSON decoding failure
    die("Failed to decode JSON input.");
}
?>
<?php

// Allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the database connection file
include 'connect.php';

// Check if the request method is POST
// Get the raw POST data (since it's sent as JSON)
$inputData = file_get_contents("php://input");

// Decode the JSON data
$requestData = json_decode($inputData, true);
if ($requestData) {
    // Get POST data
    // Example usage
    if (isset($requestData['user_id'], $requestData['movie_id'], $requestData['date'], $requestData['showtime'], $requestData['selected_seats'])) {
        $userID = $requestData['user_id'];
        $movieID = $requestData['movie_id'];
        $date = $requestData['date'];
        $showtime = $requestData['showtime'];
        $selectedSeats = explode(',', $requestData['selected_seats']);

        // Function to add booking
        function addBooking($userID, $movieID, $date, $showtime, $selectedSeats, $conn) {
            try {
                $conn->beginTransaction();

                // Generate a unique booking ID for the transaction
                $SID = null;
                $bookingID = null;

                // Prepare SQL statement with placeholders to prevent SQL injection
                $checkQuery = "SELECT S_ID FROM schedules WHERE M_ID = ? AND DATE = ? AND SHOW_TIME = ?";
                $stmtCheck = $conn->prepare($checkQuery);
                $stmtCheck->execute([$movieID, $date, $showtime]);
                $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    throw new Exception("No available schedules found!");
                }

                $SID = $row['S_ID'];

                // Generate booking ID using SID and selected seats
                $firstSeat = min($selectedSeats);
                $lastSeat = max($selectedSeats);
                $bookingID = $SID . '_' . $firstSeat . '_' . $lastSeat;

                // Check if selected seats are available for the given SID
                $availableSeatsQuery = "SELECT SEAT_NO FROM booking WHERE S_ID = ?";
                $stmtAvailableSeats = $conn->prepare($availableSeatsQuery);
                $stmtAvailableSeats->execute([$SID]);
                $availableSeats = $stmtAvailableSeats->fetchAll(PDO::FETCH_COLUMN);

                $conflictSeats = array_intersect($availableSeats, $selectedSeats);
                if (!empty($conflictSeats)) {
                    throw new Exception("Selected seats are not available!");
                }

                // Insert booking for each selected seat with the same booking ID
                $insertQuery = "INSERT INTO booking (U_ID, SEAT_NO, S_ID, BOOKING_ID) VALUES (?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($insertQuery);
                foreach ($selectedSeats as $seat) {
                    $stmtInsert->execute([$userID, $seat, $SID, $bookingID]);
                }

                $conn->commit(); // Commit transaction

                return array("success" => true, "message" => "Booking successful!", "booking_id" => $bookingID);
            } catch (Exception $e) {
                $conn->rollBack(); // Rollback transaction in case of error

                return array("success" => false, "message" => $e->getMessage());
            }
        }

        $response = addBooking($userID, $movieID, $date, $showtime, $selectedSeats, $db);
        echo json_encode($response);
    } else {
        echo json_encode(array("success" => false, "message" => "Missing parameters"));
    }
} else {
    // Invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
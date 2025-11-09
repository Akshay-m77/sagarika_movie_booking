
<?php
header("Access-Control-Allow-Origin: *");

// Include the database connection file
include 'connect.php';

// Retrieve user ID from the GET request parameters
$userId = isset($_GET['userId']) ? $_GET['userId'] : null;

if ($userId !== null) {
    try {
        // SQL query to fetch booked tickets for the given user ID
        $sql = "SELECT s.S_ID, s.M_ID, s.SHOW_TIME, s.DATE, s.STATUS AS S_STATUS, m.NAME, m.IMAGE, m.STATUS AS MOVIE_STATUS, b.BOOKING_DATE, b.BOOKING_ID, GROUP_CONCAT(b.SEAT_NO) AS SEAT_NUMBERS
        FROM booking b 
        JOIN schedules s ON b.S_ID = s.S_ID
        JOIN movies m ON s.M_ID = m.M_ID
        WHERE b.U_ID = :userId
        AND DATE(s.DATE) >= CURDATE()
        GROUP BY s.S_ID, b.BOOKING_DATE
        ORDER BY s.S_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $ticketsBySchedule = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $scheduleId = $row['S_ID'];
            $bookingDate = $row['BOOKING_DATE'];

            $scheduleData = array(
                'movieName' => $row['NAME'],
                'date' => $row['DATE'],
                'time' => $row['SHOW_TIME'],
                'bookingDate' => $bookingDate,
                'image' => $row['IMAGE'],
                'seats' => explode(',', $row['SEAT_NUMBERS']),
                'bookingId' => $row['BOOKING_ID'],
                'movieStatus' => intval($row['MOVIE_STATUS']),
                'scheduleStatus' => intval($row['S_STATUS']),
            );

            $ticketsBySchedule[$scheduleId][$bookingDate] = $scheduleData;
        }

        if (empty($ticketsBySchedule)) {
            // No tickets found for this user
            echo json_encode(array('error' => 'No tickets found.'));
        } else {
            // Return the booked tickets data
            echo json_encode($ticketsBySchedule);
        }
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
    }
} else {
    // No user ID provided
    echo json_encode(array('error' => 'No user ID provided.'));
}

$db = null;
?>
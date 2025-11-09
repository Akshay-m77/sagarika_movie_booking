<?php

// Include the database connection code
include 'connect.php';

date_default_timezone_set('Asia/Kolkata');
$current_time = date('H:i');

// Check if 'mid' parameter is provided
if (isset($_GET['mid'])) {
    $mid = $_GET['mid'];
    $currentDateTime = date('Y-m-d H:i:s');

    try {
        // Secure query with prepared statement
        $sql = "SELECT m.*, s.DATE, s.SHOW_TIME 
                FROM movies m 
                INNER JOIN schedules s ON m.M_ID = s.M_ID
                WHERE m.M_ID = :mid 
                  AND m.STATUS = 0
                  AND s.STATUS = 0
                  AND (
                      s.DATE > CURDATE() 
                      OR (s.DATE = CURDATE() AND s.SHOW_TIME > :current_time)
                  )";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mid', $mid, PDO::PARAM_INT);
        $stmt->bindParam(':current_time', $current_time, PDO::PARAM_STR);
        $stmt->execute();

        // Check if movie details are found
        if ($stmt->rowCount() > 0) {
            $movie = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (empty($movie)) {
                    // Store general movie details only once
                    $movie = [
                        'M_ID' => $row['M_ID'],
                        'NAME' => $row['NAME'],
                        'STARRING' => $row['STARRING'],
                        'youtubeUrl' => $row['TRAILER'],
                        'RATING' => $row['RATING'],
                        'IMAGE' => $row['IMAGE'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'dateTimes' => [] // Initialize dateTimes array
                    ];
                }

                // Check if the date-time combination is not expired
                $dateTime = $row['DATE'] . ' ' . $row['SHOW_TIME'];
                if ($dateTime > $currentDateTime) {
                    $movie['dateTimes'][] = [
                        'date' => $row['DATE'],
                        'time' => $row['SHOW_TIME']
                    ];
                }
            }

            // Output JSON response
            header('Content-Type: application/json');
            echo json_encode($movie);
        } else {
            // Movie not found
            http_response_code(404);
            echo json_encode(['error' => "Movie not found for mid: $mid"]);
        }
    } catch (PDOException $e) {
        // Database query error
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }
} else {
    // 'mid' parameter is missing
    http_response_code(400);
    echo json_encode(['error' => "Please provide the 'mid' parameter."]);
}

?>

<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Or specify your frontend's URL instead of '*'
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Origin: http://localhost:57639');


// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Include the database connection code
include 'connect.php';

// Set the timezone
date_default_timezone_set('Asia/Kolkata');

// Get the current time in HH:mm format
$current_time = date('H:i');

try {
    // SQL query with placeholders for prepared statements
    $sql = "SELECT DISTINCT m.M_ID, m.IMAGE, m.NAME
            FROM movies m
            INNER JOIN schedules s ON m.M_ID = s.M_ID
            WHERE (s.DATE > CURDATE() OR (s.DATE = CURDATE() AND s.SHOW_TIME >= :current_time))
            AND m.STATUS = 0
            AND s.STATUS = 0;";

    // Prepare the query
    $stmt = $db->prepare($sql);

    // Bind the parameter
    $stmt->bindParam(':current_time', $current_time);

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results
    if (!empty($movies)) {
        // Output JSON response with movie data
        header('Content-Type: application/json');
        echo json_encode($movies);
    } else {
        // No results found
        header('Content-Type: application/json');
        echo json_encode(array("message" => "No movies found in the database."));
    }
} catch (PDOException $e) {
    // Handle exceptions and output an error message
    header('Content-Type: application/json');
    echo json_encode(array("error" => $e->getMessage()));
}

?>

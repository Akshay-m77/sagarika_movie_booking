
<?php
// Include database connection file
include_once 'connect.php';

// Ensure the correct path to the config file

// Check if uid is provided
$uid = isset($_GET['uid']) ? $_GET['uid'] : null;

if ($uid) {
    // Prepare SQL query to check if the user exists
    $query = "SELECT * FROM user WHERE U_ID = :uid";
    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT); // Bind the user ID parameter
        $stmt->execute(); // Check if a user was found

        if ($stmt->rowCount() > 0) {
            // Fetch user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $authkey = $row['AUTHKEY'];

            echo json_encode([
                'status' => 'success',
                'message' => 'User found',
                'authkey' => $authkey
            ]);
        } else {
            echo json_encode([
                'status' => 'no_user_failure',
                'message' => 'User not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'failure',
            'message' => 'Database query failed: '
        ]);
    }
} else {
    // If uid is not provided
    echo json_encode([
        'status' => 'failure',
        'message' => 'User ID not provided'
    ]);
}
?>

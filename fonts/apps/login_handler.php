<?php

// Set session settings before starting the session
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS in production
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax'); // Use 'Strict' for production if not cross-origin

// Start the session at the very top
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Secure random token
}

// Check the request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    echo "<script>alert('Something went wrong. Please try again.'); window.location.href='login.php';</script>";
    exit();


}

// Validate CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
     
    echo "<script>alert('Something went wrong. Please try again.'); window.location.href='login.php';</script>";
    exit();

}
// Function to validate user input (prevent XSS)
function validate_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Rate limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 20) {
    echo "<script>alert('Too many failed attempts. Please try again later.'); window.location.href='login.php';</script>";
    exit();
}

// Validate form data
$name = validate_input($_POST['name']);
$password = $_POST['password']; // Don't sanitize password for integrity



// Include the database connection
include('connect/db.php');

// Fetch user details from the database
$sql = "SELECT ID, NAME, PASSWORD, unit FROM admin WHERE NAME = :name";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check password
    if ($user_data && password_verify($password, $user_data['PASSWORD'])) {
        $_SESSION['login_attempts'] = 0; // Reset attempts
        session_regenerate_id(true); // Prevent session fixation

        $_SESSION['SESS_ADMIN_ID'] = $user_data['ID'];
        $_SESSION['user_id'] = $user_data['ID']; // Replace 'ID' with the actual user ID column name
       
        $_SESSION['unit'] = $user_data['unit'];
        $_SESSION['uname'] = $user_data['NAME'];

        // Regenerate CSRF token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Redirect user based on unit
        $redirect_url = ($_SESSION['unit'] === "admin") ? "admin/index.php" : "unitHead/index.php";
        header("Location: $redirect_url");
        exit();
    } else {
        $_SESSION['login_attempts']++;
        echo "<script>alert('Invalid username or password. Please try again.'); window.location.href='login.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>alert('Something went wrong. Please try again later.'); window.location.href='login.php';</script>";
    exit();
}
?>

<?php
// Ensure session initialization
session_start();

// Check if a user is logged in (optional but recommended)
if (isset($_SESSION['SESS_ADMIN_ID'])) {
    // Clear all session data
    $_SESSION = array();

    // Alternatively, if using session_destroy():
    session_destroy();

    // Destroy the session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    // Redirect to login page
    header("Location: ../login.php");
    exit(); // Ensure script termination to avoid further execution
} else {
    // Handle the case where the user is already not logged in
    header("Location: ../login.php");
    exit();
}
?>

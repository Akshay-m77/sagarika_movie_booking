<?php
include("auth.php");
include('../connect/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['movie_id'])) {
    $movieId = $_POST['movie_id'];

    try {
        // Fetch movie details for confirmation message
        $stmt = $db->prepare("SELECT * FROM movies WHERE M_ID = :movie_id");
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->execute();
        $movieDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Display confirmation box using JavaScript
        echo "<script>";
        echo "var confirmUpdate = confirm('Are you sure you want delete the movie with  title ".$movieDetails['NAME']."?');";
        echo "if (confirmUpdate) {";
        
        // Perform status update
        $updateStmt = $db->prepare("UPDATE movies SET STATUS = 1 WHERE M_ID = :movie_id");
        $updateStmt->bindParam(':movie_id', $movieId);
        $updateStmt->execute();

        // Redirect after updating status
        echo "  window.location.href = 'upcomingmovies.php';";
        echo "} else {";
        echo "  window.history.go(-1);"; // Go back if not confirmed
        echo "}";
        echo "</script>";
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $db = null;
    }
} else {
    // If the form is not submitted or movie_id is not set, redirect to the previous page
    echo "<script>window.history.go(-1);</script>";
    exit();
}
?>

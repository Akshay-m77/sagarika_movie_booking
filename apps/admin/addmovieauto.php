<?php
include("auth.php");
include('../connect/db.php');

$id = null;

// Initialize variables to null
$title = $imagePath = $description = $rating = $starring = $trailer = null;

// Check if POST variables are set, and assign values or set them to null
$title = isset($_POST['title']) ? $_POST['title'] : null;
$imagePath = isset($_POST['image']) ? $_POST['image'] : null;
$description = isset($_POST['desc']) ? $_POST['desc'] : null;
$rating = isset($_POST['rating']) ? $_POST['rating'] : null;
$starring = isset($_POST['cast']) ? $_POST['cast'] : null;
$trailer = isset($_POST['trailer']) ? $_POST['trailer'] : null;

if ($title !== null || $imagePath !== null || $description !== null || $rating !== null || $starring !== null) {
    // Prepare the SQL statement based on whether trailer is provided
    if ($trailer === null) {
        $stmt = $db->prepare("INSERT INTO movies (NAME, DESCRIPTION, STARRING, RATING, IMAGE) VALUES (:title, :description, :starring, :rating, :imagePath)");
    } else {
        $stmt = $db->prepare("INSERT INTO movies (NAME, DESCRIPTION, STARRING, RATING, IMAGE, TRAILER) VALUES (:title, :description, :starring, :rating, :imagePath, :trailer)");
    }

    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':starring', $starring);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':imagePath', $imagePath);

    if ($trailer !== null) {
        $stmt->bindParam(':trailer', $trailer);
    }

    // Execute the statement
    if ($stmt->execute()) {
        $id = $db->lastInsertId();
        echo '<div id="popup-container">
            <p>MOVIE Added. Redirecting...</p>
          </div>';
        // Redirect to another page after 3 seconds
        echo '<script>
            setTimeout(function(){
                window.location.href = "schedule.php?id=' . $id . '";
            }, 300);
        </script>';
    } else {
        echo "Error adding movie: " . $stmt->errorInfo()[2];
    }

    // Close the statement
    $stmt->closeCursor();

} else {
    echo "All required fields must be filled.";
}
?>

<?php
include("../connect/db.php");

$mid = $_POST["mid"];
$name = $_POST["name"];
$desc = $_POST["desc"];
$starring = $_POST["starring"];
$rating = $_POST["rating"];

$trailer = $_POST["tariler"];
$validImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
$targetDirectory = 'uploads/'; // Ensure it exists and has write permissions

if (isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
    // File uploadedif (!empty($_FILES["image"]["tmp_name"])) {
        $uploadedFileName = basename($_FILES["photo"]["name"]);
        $uniqueFileName = uniqid() . '_' . $uploadedFileName; // Maintain original filename extension
        $targetFile = $targetDirectory . $uniqueFileName;

        // Perform basic validation
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            if (!in_array($check["mime"], $validImageMimeTypes)) {
                echo "Invalid image format. Only JPEG, PNG, and GIF allowed.";
                // continue; // Skip further processing
            }
        } else {
            echo "File is not an image.";
            // continue; // Skip further processing
        }

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
            // echo "The file " . $uploadedFileName . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
 else {
    // No file uploaded
    $imagePath = ""; // Set a default value or leave it empty based on your requirements
}

if ($imagePath == "") {
    $sql = "update movies set NAME='$name',DESCRIPTION='$desc',STARRING='$starring',RATING='$rating',TRAILER='$trailer' where M_ID='$mid'";
    $q1 = $db->prepare($sql);
    $q1->execute();
} else {
    $sql = "update movies set NAME='$name',DESCRIPTION='$desc',STARRING='$starring',RATING='$rating',TRAILER='$trailer',IMAGE='$imagePath' where M_ID='$mid'";
    $q1 = $db->prepare($sql);
    $q1->execute();
}

header("location:index.php");
?>

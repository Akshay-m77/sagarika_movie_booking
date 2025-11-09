<?php
include("auth.php");
include('../connect/db.php');

// Define $id before using it
$id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST["title"];
    $description = $_POST["description"];
    $starring = $_POST["starring"];
    $rating = $_POST["rating"];
    $trailer = $_POST["trailer"];

    // Image upload handling (replaced with secure and validated logic)
    $validImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $targetDirectory = 'uploads/'; // Ensure it exists and has write permissions
    if (!empty($_FILES["image"]["tmp_name"])) {
        $uploadedFileName = basename($_FILES["image"]["name"]);
        $uniqueFileName = uniqid() . '_' . $uploadedFileName; // Maintain original filename extension
        $targetFile = $targetDirectory . $uniqueFileName;

        // Perform basic validation
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (!in_array($check["mime"], $validImageMimeTypes)) {
                echo "Invalid image format. Only JPEG, PNG, and GIF allowed.";
                exit; // Exit further processing
            }
        } else {
            echo "File is not an image.";
            exit; // Exit further processing
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $imageVar.$targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit; // Exit further processing
        }
    }

    // Insert data into the 'movies' table using prepared statement
    if ($trailer == NULL) {
        $stmt = $db->prepare("INSERT INTO movies (NAME, DESCRIPTION, STARRING, RATING, IMAGE) VALUES (:title, :description, :starring, :rating, :imagePath)");
        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':starring', $starring);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':imagePath', $imagePath);
    } else {
        $stmt = $db->prepare("INSERT INTO movies (NAME, DESCRIPTION, STARRING, RATING, IMAGE, TRAILER) VALUES (:title, :description, :starring, :rating, :imagePath, :trailer)");
        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':starring', $starring);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':imagePath', $imagePath);
        $stmt->bindParam(':trailer', $trailer);
    }

    if ($stmt->execute()) {
        $id = $db->lastInsertId();
        echo '<div id="popup-container">
            <p>MOVIE Added. Redirecting...</p>
            <!-- Add any other content or form elements here -->
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add movie</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("include/css.php"); ?>
    <style>
        /* Your styles here */
        #movieForm {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Add your CSS styles for the popup here */
        #popup-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        label {
            margin-bottom: 5px;
        }

        .l {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        img#imagePreview {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #007bff; /* Blue color */
            color: #fff;
            cursor: pointer;
        }

        .warning-message {
            color: red;
            margin-top: 10px;
            display: none;
        }

        .container-wrapper {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            overflow: hidden;
        }

        .container {
            display: flex;
        }

        .upload-container,
        .data-container {
            flex: 1;
            padding: 20px;
            background-color: #8CB9BD;
            border-radius: 10px;
            margin-right: 20px;
        }

        .data-container {
            background-color: #8CB9BD;
            border: 1px solid #ccc;
            border-radius: 15px
        }

        .data-container label {
            display: block;
            margin-bottom: 10px;
            border-radius: 15px
        }

        #imagePreview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <?php include("include/header.php"); ?>
        </header>
        <aside class="main-sidebar">
            <?php include("include/leftmenu.php"); ?>
        </aside>
        <div class="content-wrapper">
            <?php include("include/topmenu.php"); ?>
        </div>
        <header class="header2">
            <h1>ADD Movies</h1>
        </header>

        <div class="container-wrapper">
            <div class="container">
                <div class="upload-container">
                    <form id="movieForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <!-- Your form fields here -->
                        <label for="image">ADD MOVIE POSTER</label>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage()" required><br>
                        <img id="imagePreview" alt="Image Preview" style="max-width: 200px; height: auto;">
                        <!-- Rest of your form fields -->
                        <br><label for="genre">Title:</label>
                        <input type="text" name="title" class="l" id="title" required ><br><br>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" rows="4"  required></textarea><br><br>
                        <label for="trailer">Trailer Url:</label>
                        <textarea name="trailer" id="trailer" rows="1" ></textarea><br><br>
                        <label for="genre">Starring:</label>
                        <input type="text" name="starring" id="genre" required><br><br>
                        <label for="rating">IMDB Rating:</label>
                        <input type="number" name="rating" id="rating" min="0" max="10" step="0.1" required><br><br>
                        <center>   <input type="submit" value="Submit"></center>
                        <div class="warning-message" id="warningMessage">Please fill out all fields!</div>
                        <div class="warning-message" id="ratingLimitMessage" style="display: none;">IMDB rating should not exceed 10!</div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>
    <div class="control-sidebar-bg"></div>

    <?php include("include/js.php"); ?>

    <script>
        // Your JavaScript functions here
        function previewImage() {
            var preview = document.getElementById('imagePreview');
            var fileInput = document.getElementById('image');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function validateForm() {
            var title = document.getElementById('title').value;
            var date = document.getElementById('date').value;
            var time = document.getElementById('time').value;
            var description = document.getElementById('description').value;
            var genre = document.getElementById('starring').value;
            var rating = parseFloat(document.getElementById('rating').value);
            var trailer = document.getElementById('trailer').value;
            if (title === "" || date === "" || time === "" || description === "" || starring === "") {
                document.getElementById('warningMessage').style.display = "block";
                return false;
            } else {
                document.getElementById('warningMessage').style.display = "none";
            }

            if (rating > 10) {
                document.getElementById('ratingLimitMessage').style.display = "block";
                return false;
            } else {
                document.getElementById('ratingLimitMessage').style.display = "none";
            }

            return true;
        }
    </script>
</body>
</html>

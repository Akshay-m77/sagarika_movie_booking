<?php
include("auth.php");
include('../connect/db.php');

try {
   
    $stmt = $db->prepare("            SELECT *
    FROM movies m
    JOIN schedules s ON m.M_ID = s.M_ID
    WHERE (s.DATE > CURRENT_DATE OR 
           (s.DATE = CURRENT_DATE AND s.SHOW_TIME > CURRENT_TIME))
      AND m.STATUS = 0
    GROUP BY m.M_ID;
    
    ");
    $stmt->execute();

    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Movies</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("include/css.php"); ?>
    <style>
.button-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #AFE1AF;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

/* 

body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        } */

        .movie-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
        }

        p {
            margin: 5px 0;
            color: #666;
        }

        form {
            display: inline;
            margin-right: 10px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
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
    /* display: block; */
    margin-bottom: 5px;
}

.l{
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

/* Style the submit button */
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

        /* old */
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

        .warning-message {
            color: red;
            font-weight: bold;
            display: none;
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }

        .movie-details-box {
        background-color: #f0f0f0; /* Light grey background color */
        border: 1px solid #ddd; /* Border color */
        border-radius: 8px; /* Rounded corners */
        padding: 15px; /* Padding around the content */
        margin-bottom: 20px; /* Spacing between movie containers */
        }

        .description-box {
    background-color: #ffffff; /* White background color */
    border: 1px solid #cccccc; /* Border color */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding inside the box */
    margin-bottom: 10px; /* Spacing between description box and other elements */
}

.show-details-box {
    background-color: #f0f0f0; /* Light grey background color */
    border: 1px solid #ddd; /* Border color */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding inside the box */
    margin-top: 10px; /* Spacing between movie description and show details */
}

.show-time-box {
    display: flex; /* Use flexbox for layout */
    flex-wrap: wrap; /* Wrap items if they exceed container width */
}

.show-time {
    background-color: #ffffff; /* White background color */
    border: 1px solid #cccccc; /* Border color */
    border-radius: 5px; /* Rounded corners */
    padding: 5px 10px; /* Padding inside the box */
    margin-right: 10px; /* Right margin between show time items */
    margin-bottom: 10px; /* Bottom margin between show time items */
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
            <h1>UPCOMING MOVIES</h1>
        </header>   
        
<!-- SHOW_TIME  RATING  DESCRIPTION -->
            <!-- Movie containers -->
            <?php
            if (!empty($movies)) {
                foreach ($movies as $movie) {
                    echo "<div class='movie-container'>";
                    
                    // Image column
                    echo "<div class='movie-image' style='text-align: center;'>";
                   
                    echo "<img src='" . $movie["IMAGE"] . "' alt='" . $movie["NAME"] . "' style='max-width: 200px; max-height: 300px; display: inline-block; border-radius: 8px; margin-bottom: 10px;'>";        
                    echo "</div>"; // End of movie-image
                    
                    // Movie details box
                    echo "<div class='movie-details-box'>";
                    
                    // Movie data
                    echo "<div class='movie-data'>";
                    echo "<h3 style='text-align: center; font-weight: bold; font-size: 30px;'>" . $movie["NAME"] . "</h3>";
                    echo "<div class='description-label-box'>";
                    // echo "<p class='movie-description' ><strong>Description:&nbsp&nbsp&nbsp</strong>" . $movie["DESCRIPTION"] . "<p>";
                    echo "<p class='movie-description'><strong>Description:&nbsp&nbsp&nbsp</strong>
                    <textarea name='description' rows='4' cols='50' style='border: none;'>" . $movie['DESCRIPTION'] . "</textarea>
                    </p>";
                    
                    
                    echo "<div class='description-label-box'>";
                    echo "<p><strong>Rating:&nbsp&nbsp&nbsp</strong>" . $movie["RATING"] . "<p>";
                    echo "</div>"; // End of description-label-box
                    
                    // Show details box
                    echo "<div class='show-details-box'>";
                    echo "<p><strong>Show time: </strong></p>";
                    echo "<div class='show-time-box'>";
                    $mid=$movie["M_ID"];
                    $result2 = $db->prepare("SELECT *
                            FROM schedules where M_ID=$mid AND ((DATE > CURRENT_DATE) OR 
                                (DATE = CURRENT_DATE AND SHOW_TIME > CURRENT_TIME) )
                            ");
                    $result2->execute();
                    for($i=1; $row = $result2->fetch(); $i++) {
                        echo "<div class='show-time'>" . $row["DATE"] . " / " . $row["SHOW_TIME"] . "</div>";
                    }
                    echo "</div>"; // End of show-time-box
                    echo "</div>"; // End of show-details-box       

                    echo "<p><strong>Starring:</strong> " . $movie["STARRING"] . "</p>";
                    echo "</div>"; // End of movie-data

                    // Edit and Remove buttons
                    echo "<div class='movie-buttons'>";
                    ?>
                    <a href="edit_movie.php?data=<?php echo urlencode($mid); ?>" class="button-link">Edit</a>
                    <?php
                    echo "<form action='remove_movie.php' method='post'>";
                    echo "<input type='hidden' name='movie_id' value='" . $movie["M_ID"] . "'>";
                    echo "<input type='submit' value='Remove' style='background-color: #DC143C; color: #fff; padding: 8px 16px; border-radius: 4px;'>";
                    echo "</form>";
                    echo "</div>"; // End of movie-buttons
                    echo "</div>"; // End of movie-details-box
                    echo "</div>"; // End of movie-container
                    echo "</div>";
                }
            } else {
                echo "<p>No movies found</p>";
            }
            ?>
        </div> <!-- End of content-wrapper -->

        <?php include("include/footer.php"); ?>
        <div class="control-sidebar-bg"></div>

        <?php include("include/js.php"); ?>
     <!-- End of wrapper -->
</body>
</html>
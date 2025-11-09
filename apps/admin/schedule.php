<?php
include("auth.php");
include('../connect/db.php');

// Initialize movie ID
$id = $_GET['id'] ?? '';

// if there no movie is seelcted , this wcho will show 
//if (empty($id)) {
    //echo "Invalid or missing M_ID parameter. Please select a movie.";
    // You can also redirect the user to a page where they can select a movie
  //  exit; // Stop further execution of the script
//}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve values from the form
    $m_id = $id;
    $date = $_POST["date"];
    $time = $_POST["time"];

    // Validate input
    if (!empty($date) && !empty($time) && !empty($m_id)) {
        // Add the schedule to the database
        $sql = "INSERT INTO schedules (M_ID, DATE, SHOW_TIME) VALUES (:id, :date, :time)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $m_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);

        if ($stmt->execute()) {
            echo "<script>alert('Schedule added successfully!');</script>";;
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Please fill in all fields.";
    }
}

// Display existing schedules
 
?>
<html>
    <head>
    <title>Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="../../images/logo.png">
   
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <style>
            /* CSS styles for header */
            .header {
                background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 30px;
            }
            .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #7393B3;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

            /* CSS styles for form */
            form {
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            label {
                display: block;
                margin-bottom: 10px;
            }

            input[type="date"],
            input[type="time"] {
                width: 100%;
                padding: 8px;
                margin-bottom: 20px;
                box-sizing: border-box;
            }

            button[type="submit"] {
                
                color: #060606;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                font-size: 13px;
            }

            /* CSS styles for schedule list */
            .schedule-list {
                list-style-type: none;
                padding: 0;
            }

            .schedule-list li {
                margin-bottom: 10px;
                padding: 10px;
                background-color: #f9f9f9;
                border-radius: 5px;
            }

             /* Style for the "Add Schedule" button */
        .done-button {
            display: block;
            width: 150px;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #AFE1AF;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for the "Add Schedule" button */
        .done-button:hover {
            background-color: #6D8D6D;
        }
/* DONE*/
        body {
  background: hsl(220deg, 10%, 97%);
  margin: 0;
  padding: 0;
}

.buttons-container {
  width: 100%;
  height: 10vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

button {
  background: white;
  border: solid 2px black;
  padding: .375em 1.125em;
  font-size: 1rem;
}

.button-arounder {
  font-size: 2rem;
  background: hsl(190deg, 30%, 15%);
  color: hsl(190deg, 10%, 95%);
  
  box-shadow: 0 0px 0px hsla(190deg, 15%, 5%, .2);
  transfrom: translateY(0);
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
  
  --dur: .15s;
  --delay: .15s;
  --radius: 16px;
  
  transition:
    border-top-left-radius var(--dur) var(--delay) ease-out,
    border-top-right-radius var(--dur) calc(var(--delay) * 2) ease-out,
    border-bottom-right-radius var(--dur) calc(var(--delay) * 3) ease-out,
    border-bottom-left-radius var(--dur) calc(var(--delay) * 4) ease-out,
    box-shadow calc(var(--dur) * 4) ease-out,
    transform calc(var(--dur) * 4) ease-out,
    background calc(var(--dur) * 4) steps(4, jump-end);
}

.button-arounder:hover,
.button-arounder:focus {
  box-shadow: 0 4px 8px hsla(190deg, 15%, 5%, .2);
  transform: translateY(-4px);
  background: hsl(230deg, 50%, 45%);
  border-top-left-radius: var(--radius);
  border-top-right-radius: var(--radius);
  border-bottom-left-radius: var(--radius);
  border-bottom-right-radius: var(--radius);
}

/* Centering and styling .ex element */
.ex {
    color: #ffff;
    font-size: 20px;
    text-align: center; /* Align text to center */
}

/* Animation for .ex element */
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Apply animation to .ex element */
.ex {
    animation: fadeIn 1s ease; /* Apply fadeIn animation */
}



.movie-time-dates {
    text-transform: uppercase; /* Capitalize the text */
    font-weight: bold; /* Make the text bold */
    color: #322C2B;/* Add any additional styles here */
}

.movie-details {
    display: none;
}
/* times */
.common-times {
    margin-bottom: 20px; /* Add some space below the common time buttons */
}

.common-time-btn {
    margin-right: 10px; /* Add some space between the common time buttons */
    padding: 8px 12px; /* Add padding to make the buttons more clickable */
    background-color: #f0f0f0; /* Background color for the buttons */
    border: 1px solid #ccc; /* Border for the buttons */
    border-radius: 5px; /* Rounded corners for the buttons */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s ease; /* Smooth transition on hover */
}

.common-time-btn:hover {
    background-color: #e0e0e0; /* Darker background color on hover */
}



        </style>
        <?php
    include('include/css.php');
    ?>
     
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
            <?php include("include/topmenu.php"); ?></div>
        <!-- Header -->
        <header class="header">Add Movie Schedule</header>
        <div class="container">
        <!-- Form to add date and time -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . urlencode($id)); ?>">
            <label for="date">Date:</label>
            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            
            <label for="time">Time:</label>
            <div class="common-times">
    <!-- Add common times buttons -->
    <button class="common-time-btn" onclick="setTime('12:00')">12:00</button>
    <button class="common-time-btn" onclick="setTime('15:00')">15:00</button>
    <button class="common-time-btn" onclick="setTime('18:00')">18:00</button>
    <button class="common-time-btn" onclick="setTime('21:00')">21:00</button>
        <!-- Add more common times as needed -->
</div>
            <input id="time-input" type="time" name="time" required>

            <!-- Hidden input for M_ID -->
            <input type="hidden" name="mid" value="<?php echo $id; ?>">
            

            <button type="submit" class="done-button">ADD SCHEDULE</button>
            
        </form><br>
        <?php
        if (!empty($id)) {
            $stmt = $db->prepare("SELECT * FROM schedules WHERE M_ID = :id AND STATUS = 0");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Display the result or perform other operations
            if ($result) {
                echo "<h2 style='font-size:1px;'>Schedule Details</h2>";
                echo "<ul>";
                echo "<div class='ex'>";
                echo "<div class='movie-time-dates'>Movies Time and Dates: </div>";
   
                foreach ($result as $row) {
                    echo "<li class='movie-details'>M_ID: " . $row["M_ID"] . ", Date: " . $row["DATE"] . ", Time: " . $row["SHOW_TIME"] . "</li>";
                    echo "<li> Date: " . $row["DATE"] . ", Time: " . $row["SHOW_TIME"] . "</li> ";
                    echo "<div>";
                }
                echo "</ul>";
            } else {
                echo "<center><p>No schedule addded</p></center>";
            }
        }
    
    ?>
        

        <!-- Link to go back -->
        <!--<a href="index.php" class="done-button">Done</a>-->
        <div class="buttons-container">
  <button class="button-arounder" onclick="showAlertAndRedirect()">DONE</button>
</div>


    </div>
    <div >
        
    </div>
    <script>
function showAlertAndRedirect() {
  // Display the confirm dialog
  var result = confirm("Are you sure you want to finish adding schedules?");

  // Check the user's choice
  if (result) {
    // If user clicks "Yes", show alert and redirect
    <?php
    if (!empty($id)) {
      $stmt = $db->prepare("SELECT * FROM schedules WHERE M_ID = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Display the result or perform other operations
      if ($result) {
        echo "alert('Schedules added successfully!');";
      } else {
        echo "alert('No schedules added.');";
      }
    } else {
      echo "alert('Invalid or missing M_ID parameter. Please select a movie.');";
    }
    ?>
    window.location.href = 'index.php'; // Redirect to index.php after showing alert
  }
}


    function setTime(time) {
        document.getElementById('time-input').value = time;
    }
</script>
    
   
    </body>
    


</html>
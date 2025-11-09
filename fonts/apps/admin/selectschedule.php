<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules</title>
    <link rel="icon" type="image/png" href="../../images/logo2-removebg-preview.png">

    <style>
        /* Modal styles */
/* Ensure body takes full height */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0; /* Light background */
    overflow: hidden; /* Prevent page scrolling when modal is open */
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px; /* Adds spacing for long content */
    overflow: auto; /* Enable scrolling when content overflows */
}

/* Modal content styling */
.modal-content {
    background-color: #f9f9f9;
    padding: 30px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh; /* Prevent modal from going beyond viewport height */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow-y: auto; /* Add vertical scroll if content is too long */
    box-sizing: border-box;
}

/* Modal header with close button */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close {
    color: #555;
    font-size: 24px;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

h2 {
    font-size: 1.5em;
    margin: 0;
}

.schedule-options {
    margin: 20px 0;
}

/* Schedule options radio buttons */
.schedule-options label {
    display: block;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
}

.schedule-options input[type="radio"] {
    margin-right: 10px;
    cursor: pointer;
}

.schedule-options label:hover {
    background-color: #f0f0f0;
    border-color: #aaa;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
    width: 100%;
}

button:hover {
    background-color: #45a049;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .modal-content {
        width: 95%;
        padding: 20px;
    }

    h2 {
        font-size: 1.2em;
    }
}

    </style>
</head>
<body>

<?php
// Check if MID is passed from the previous page
if(isset($_GET['mid']) && isset($_GET['msg'])) {
    $mid = $_GET['mid'];
    $msg = $_GET['msg'];

    // Connect to your database
    include("auth.php");
    include('../connect/db.php');

    // Prepare and execute query to fetch schedules based on MID
    $sql = "SELECT * FROM schedules WHERE M_ID = :mid";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':mid', $mid);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if schedules exist for the given MID
    if(count($result) > 0) {
        // If there is more than one schedule, show a popup message
        if(count($result) > 1) {
            // Display the modal for multiple schedules
            echo "<div id='popup' class='modal'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h2>Select the schedule</h2>";
            echo "<span class='close'>&times;</span>";
            echo "</div>";
            echo "<p>Please select the schedule from the list below:</p>";
            echo "<div class='schedule-options'>";

            // Display radio buttons for each schedule option with labels
            foreach($result as $schedule) {
                $scheduleID = $schedule['S_ID'];
                echo "<label><input type='radio' name='selected_schedule' value='$scheduleID'> Date: " . $schedule['DATE'] . ", Time: " . $schedule['SHOW_TIME'] . "</label>";
            }
            echo "</div>";
            echo "<button id='submitBtn'>Select Schedule</button>";
            echo "</div>";
            echo "</div>";
        } else if (count($result) == 1) {
            // If only one schedule, redirect based on $msg
            $schedule = $result[0];
            if ($msg === "reservation") {
                echo "<script>window.location.href = 'seat.php?sid=".$schedule['S_ID']."';</script>";
            } elseif ($msg === "view") {
                echo "<script>window.location.href = 'booking.php?sid=".$schedule['S_ID']."';</script>";
            }
        }
    } else {
        echo "No schedules found.";
    }
} else {
    echo "MID or msg not provided.";
}
?>

<script>
    // Get the modal
    var modal = document.getElementById('popup');

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
        window.location.href = "movielist.php";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            window.location.href = "movielist.php";
        }
    }

    // When the user clicks the Select button
    document.getElementById('submitBtn').addEventListener('click', function() {
        var radios = document.getElementsByName('selected_schedule');
        var selectedSchedule;
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selectedSchedule = radios[i].value;
                break;
            }
        }
        if (selectedSchedule !== undefined) {
            <?php
            // Check the value of $msg and redirect accordingly
            if ($msg === "reservation") {
                echo "window.location.href = 'seat.php?sid=' + selectedSchedule;";
            } elseif ($msg === "view") {
                echo "window.location.href = 'booking.php?sid=' + selectedSchedule;";
            }
            ?>
        } else {
            alert("Please select a schedule.");
        }
    });

    // Show the modal
  //  modal.style.display = "block";
</script>

</body>
</html>

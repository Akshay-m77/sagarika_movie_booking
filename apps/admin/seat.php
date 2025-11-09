<?php
include("auth.php");
include('../connect/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Seat arrangment</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
   
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <style>
        
        button.print-button {
            padding: 15px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 30%;
            margin-top: 1%;
            align-items: 50%;
        }

        button.print-button:hover {
            background-color: #45a049;
        }

        button.stop-button {
            padding: 10px 20px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 1%;
            margin-left: 5%;
        }

        button.stop-button:hover {
            background-color: #cc0000;
        }

        .seating-container-wrapper {
            overflow: auto;
            padding: 10px;
        }

        .seating-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .seating-row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
            width: 100%;
        }

        .seat {
            position: relative;
            width: 20px;
            height: 20px;
            margin: 5px;
            background-color: lightgray;
            border: 1px solid red;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .seat-gap {
            width: 40px;
            display: flex;
            justify-content: flex-end;
            flex-direction: column-reverse;
            align-items: center;
            flex-wrap: nowrap;
        }

        .seat-gap span {
            text-align: center;
        }

        .seat.occupied {
            background-color: gray;
            cursor: not-allowed;
        }

        .seat.selected {
            background-color: green;
        }

        .seat-number {
            font-size: 12px;
        }

        .special-symbol {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: black;
        }

        .screen {
            width: 40%;
            background-color: lightgray;
            text-align: center;
        }


        .seating-plan {
            background-color: white;
            border: 2px;
            padding: 10px;
            margin: 10px;
        }

        
        @media print {
            .seating-container {
        width: 100%;
        max-width: 100%;
    }
    .seat.selected {
        background-color: green !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    /* Remove margin and padding */
    .seating-container-wrapper, .seating-container-wrapper * {
        margin: 0;
        padding: 2px;
    }

    /* Adjust seat size */
    .seat {
        width: 20px; /* Adjust as needed */
        height: 20px; /* Adjust as needed */
        margin: 2px; /* Adjust as needed */
    }

            


    .seating-container-wrapper, .seating-container-wrapper * {
        visibility: visible;
    }
    


    .seating-container-wrapper, .seating-container-wrapper * {
        visibility: visible;
    }
    .seating-container-wrapper {
        position: static;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        overflow: visible;
    }



            .row{
            display: none;
        }
        .main-footer{
            display: none;
        

        }
            
            .seat.selected {
        background-color: green !important; /* Ensure selected color is visible */
    }
    
    .seat-gap {
        width: 40px;
        display: flex;
        justify-content: flex-end;
        flex-direction: column-reverse;
        align-items: center;
        flex-wrap: nowrap;
    }
    .print-button, .stop-button {
        display: none;
    }
        }
    </style>
    <?php include('include/css.php'); ?>
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
        <div class="seating-plan">
            <?php
            if (isset($_GET['sid'])) {
                $sid = $_GET['sid'];
                $stmt = $db->prepare("SELECT DATE, SHOW_TIME, M_ID, STATUS FROM schedules WHERE S_ID = :sid");
                $stmt->bindParam(':sid', $sid);
                $stmt->execute();
                $scheduleResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($scheduleResult) {
                    $showDate = $scheduleResult['DATE'];
                    $showTime = $scheduleResult['SHOW_TIME'];
                    $mid = $scheduleResult['M_ID'];
                    $status = $scheduleResult['STATUS'];

                    $stmt = $db->prepare("SELECT NAME FROM movies WHERE M_ID = :mid");
                    $stmt->bindParam(':mid', $mid);
                    $stmt->execute();
                    $movieResult = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($movieResult) {
                        $name = $movieResult['NAME'];

                        $stmt = $db->prepare("SELECT SEAT_NO FROM booking WHERE S_ID = :sid");
                        $stmt->bindParam(':sid', $sid);
                        $stmt->execute();
                        $bookingResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $selectedSeats = [];
                        foreach ($bookingResult as $row) {
                            $selectedSeats[] = $row['SEAT_NO'];
                        }

                        $specialSeats = ["BB15", "BB16", "BB17", "BB18", "GH8", "GH9", "GH10", "GH11", "GH12", "GH13", "GH14", "GH15"];
                        $rowLabels = range('A', 'M');
                    } else {
                        echo "Movie not found for M_ID: $mid";
                    }
                } else {
                    echo "Schedule not found for S_ID: $sid";
                }
            }
            ?>
            <?php if ($status != 2): ?>
                <button class="stop-button" onclick="stopBooking()">STOP<br>BOOKING</button>
            <?php endif; ?>
            <button class="print-button" onclick="window.print()"><i class="fas fa-print"></i> PRINT</button>

            <div class="seating-container-wrapper">
                <div class="seating-container">
                    <h3><center><u>SEATING PLAN & RESERVATION CHART<br>MOVIE SCREENING - <?php echo $name ?><br>SHOW TIME - <?php echo $showDate . "&nbsp&nbsp" . $showTime ?></u></center></h3>
                    <h4>BALCONY</h4>
                    <?php
                    for ($row = 1; $row <= 5; $row++) {
                        echo '<div class="seating-row">';
                        for ($col = 24; $col >= 1; $col--) {
                            $seatId = 'B' . $rowLabels[$row - 1] . $col;
                            $selectedClass = (in_array($seatId, $selectedSeats)) ? 'selected' : '';
                            echo '<div class="seat ' . $selectedClass . '" id="' . $seatId . '"><span class="seat-number">' . $col . '</span>';
                            if (in_array($seatId, $specialSeats)) {
                                echo '<span class="special-symbol">#</span>';
                            }
                            echo '</div>';
                            if ($col == 19 || $col == 7) {
                                echo '<div class="seat-gap">' . $rowLabels[$row - 1] . '</div>';
                            }
                        }
                        echo '</div>';
                    }
                    ?>

                    <h4>GROUND FLOOR</h4>
                    <div style="margin-left: 40%;"><---- Family ----></div>
                    <?php
                    for ($row = 1; $row <= 13; $row++) {
                        echo '<div class="seating-row">';
                        if ($row == 1) {
                            $seatCount = 26;
                        } elseif ($row >= 2 && $row <= 6) {
                            $seatCount = 24;
                        } else {
                            $seatCount = 22;
                        }

                        for ($col = $seatCount; $col >= 1; $col--) {
                            $seatId = 'G' . $rowLabels[$row - 1] . $col;
                            $selectedClass = (in_array($seatId, $selectedSeats)) ? 'selected' : '';
                            echo '<div class="seat ' . $selectedClass . '" id="' . $seatId . '"><span class="seat-number">' . $col . '</span>';
                            if (in_array($seatId, $specialSeats)) {
                                echo '<span class="special-symbol">#</span>';
                            }
                            echo '</div>';
                            if (($seatCount == 26 && $col == 14) || ($seatCount == 24 && $col == 13) || ($seatCount == 22 && $col == 12)) {
                                echo '<div class="seat-gap">' . $rowLabels[$row - 1] . '</div>';
                            }
                        }
                        if ($row == 8) {
                            echo '<div style="height: 60px;"></div>';
                        }
                        echo '</div>';
                    }
                    ?>
                    <br>
                    <div class="screen">Screen</div>
                </div>
            </div>
        </div>
</div>

<?php include("include/footer.php"); ?>
<div class="control-sidebar-bg"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include("include/js.php"); ?>

<script>
function stopBooking() {
    if (!confirm("Are you sure you want to stop the booking?")) return;

    const urlParams = new URLSearchParams(window.location.search);
    const sid = urlParams.get('sid');
    fetch('stopBooking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sid: sid })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Booking has been successfully stopped."+data.message);
            location.reload();
        } else {
            alert("Failed to stop booking.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An unexpected error occurred.");
    });
}

</script>

</body>
</html>

<?php
include("auth.php");
include('../connect/db.php');
$Log_Id=$_SESSION['SESS_ADMIN_ID'];
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
<title>Bookings</title>
<link rel="icon" type="image/png" href="../../images/logo.png">
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
    include("include/css.php");
    ?>  
    
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    
    <header class="main-header"> 
    <?php
    include("include/header.php");
    ?>
    </header>
    
    <aside class="main-sidebar">
    <?php
    include("include/leftmenu.php");
    ?>
    </aside>
    
    <div class="content-wrapper">
    <?php
    include("include/topmenu.php");
    ?>
    </div>
    
    <div class="row well" style="background-color:#FAFAFA;">
      
    <?php
    if(isset($_GET['sid'])){
    $sid=$_GET['sid'];
    
    // Fetch booking details with user, movie, and schedule information from the database using JOIN
    $sql = "SELECT
    b.B_ID, 
    b.BOOKING_ID as bookingId,
    u.NAME AS username, 
    GROUP_CONCAT(b.SEAT_NO) AS seatnos, 
    m.NAME AS moviename, 
    b.BOOKING_DATE,
    s.DATE AS show_date,
    s.SHOW_TIME AS show_time
FROM 
    booking b
JOIN 
    user u ON b.U_ID = u.U_ID
LEFT JOIN 
    schedules s ON b.S_ID = s.S_ID
LEFT JOIN 
    movies m ON s.M_ID = m.M_ID
WHERE
    (DATE(s.DATE) >= CURDATE() OR TIMESTAMP(s.DATE, s.SHOW_TIME) >= NOW() - INTERVAL 24 HOUR)  -- Filter to show details where the date is today or within 24 hours
    AND m.STATUS = 0  -- Filter to show only movies with status 0
    AND s.STATUS != 1  -- Filter to show only schedules with status 0
    AND s.S_ID = $sid
GROUP BY
    u.U_ID, s.S_ID";

    // Check if a search query is provided
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql .= " HAVING username LIKE '%$search%' OR moviename LIKE '%$search%' OR bookingId LIKE '%$search%' OR seatnos LIKE '%$search%'";
    }

    $result = $db->query($sql);

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <title>Movie Booking Details</title>
        <style>
            .head{
                text-align:center;
                font-size:20px;
                font-weight: bold;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #7393B3;
                
            }

            .search-form {
                margin-bottom: 20px;
                text-align: right;
                
            }

            .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }

        .no-bookings {
    text-align: center;
    margin-top: 20px;
    font-weight: bold;
    color: #333;
}
        </style>
    </head>
    <body>
    <header class="header2">
        <h2>Movie Booking Details</h2>
    </header>
        <!-- Search Form -->
        <form class="search-form" method="GET">
    <input type="hidden" name="sid" value="<?php echo isset($_GET['sid']) ? $_GET['sid'] : ''; ?>">
    <label for="search"></label>
    <input type="text" placeholder="Search Username..."  name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit"><i class="fa fa-search"></i></button>
</form>

        <?php
// Initialize variables to store movie name and show time
$movieName = '';
$showTime = '';

// Check if there are any bookings
if ($result->rowCount() > 0) {
    // Fetch the first row to get the movie name and show time (assuming there's at least one row)
    $firstRow = $result->fetch(PDO::FETCH_ASSOC);
    
    // Store movie name and show time
    $movieName = $firstRow["moviename"];
    $showDate = $firstRow["show_date"];
    $showTime = $firstRow["show_time"];
echo "<div class='head'>";
    // Output movie name and show time above the table
    echo "<p>Movie Name: $movieName</p>";
    echo "<p>Show Date: $showDate</p>";

    echo "<p>Show Time: $showTime</p>";
echo "</div>";
    // Output the table headers
    echo "<table>";
    echo "<tr><th>Booking ID</th><th>Username</th><th>Seat Numbers</th><th>Booking Date</th></tr>";

    // Output data of each row
    do {
        echo "<tr><td>".$firstRow["bookingId"]."</td><td>".$firstRow["username"]."</td><td>".$firstRow["seatnos"]."</td><td>".$firstRow["show_date"]."</td></tr>";
    } while($firstRow = $result->fetch(PDO::FETCH_ASSOC));

    echo "</table>";
        
        } else {
            
            echo "<div class='no-bookings'>No bookings found, try searching username, Booking id or seat number </div>";;
        }
    } else{
        echo "<div class='no-bookings'>No bookings found</div>";
        
    }
        // Close the database connection
        $db = null;
        ?>

    </body>
    </html>

    </div>
</section>           
</div>
  
 <?php
  include("include/footer.php");
    ?>
        <div class="control-sidebar-bg"></div>
        </div>
        
<?php
  include("include/js.php");
?>

</body>
</html>

<?php
	include("auth.php");
	include('../connect/db.php');
	$Log_Id=$_SESSION['SESS_ADMIN_ID'];
?>	
<!DOCTYPE html>
<html>

<head>

 	
<title>Feedback</title>
<link rel="icon" type="image/png" href="../../images/logo.png">
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <?php
		include('include/css.php');
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
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback View</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }

        table {
            border-collapse: collapse;
                width: 100%;
                margin-top: 0px; /* Align table in the center */
        }

        th, td {
            border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
        }

        th {
            background-color: #7393B3;
            color: #fff;
            text-align: center;
        }

        .average-rating {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star {
            color: gold;
            font-size: 2em;
        }

        .view-btn {
            padding: 10px 20px;
            background-color: #fff ;
            color: #333;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            
        }

        .view-btn:hover {
            background-color: #ADD8E6;
            border-radius: 25px;
            
        }
    </style>
</head>
<body>
<header class="header2">
            <h1>Feedback View</h1>
        </header>
    <div class="container">
        
        <table>
            <thead>
                <tr>
                    <th>Services</th>
                    <th>Average Rating</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
            <?php
                try {
                    $query = "SELECT SERVICES, AVG(RATING) as avg_rating FROM feedback GROUP BY SERVICES";
                    $statement = $db->query($query);

                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $serviceName = $row['SERVICES'];
                        $averageRating = round($row['avg_rating']);
            ?>
            <tr>
                <td><?php echo $serviceName; ?></td>
                <td>
                    <div class="average-rating">
                        <?php
                            // Assuming a maximum rating of 5
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $averageRating) {
                                    echo '<span class="star">★</span>';
                                } else {
                                    echo '<span class="star">☆</span>';
                                }
                            }
                        ?>
                    </div>
                </td>
                <td><button class="view-btn" onclick="redirectToFeedbackDetails('<?php echo $serviceName; ?>')">View Feedback</button></td>
            </tr>
            <?php
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            ?>
        </tbody>
           </table>
    </div>
    
    <script>
        function redirectToFeedbackDetails(service) {
            window.location.href = `feedback_details.php?service=${service}`;
        }
    </script>
</body>
</html>

  

	</div>
    <?php include("include/footer.php"); ?>
        <div class="control-sidebar-bg"></div>
        <?php include("include/js.php"); ?>        


</body>
</html>

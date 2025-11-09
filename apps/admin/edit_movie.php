<?php
	include("auth.php");
	include('../connect/db.php');

	if (isset($_GET['data'])) {
		$value = urldecode($_GET['data']);
	}

	$result = $db->prepare("select * from movies where M_ID='$value'");
	$result->execute();

	for ($i = 0; $row = $result->fetch(); $i++) {
		$name = $row["NAME"];
		$rating = $row["RATING"];
		$desc = $row["DESCRIPTION"];
		$starring = $row["STARRING"];
		
		$trailer = $row["TRAILER"];
		$photo = $row["IMAGE"];
	}


	// $result = $db->prepare("select * from user where Log_Id='$Log_Id'");
	// $result->execute();
	// for($i=0; $row = $result->fetch(); $i++)
	// {
	// 	$password=$row["password"];

	// }
?>

<!DOCTYPE html>
<html>

<head>
	
<title>Edit show</title> 
<link rel="icon" type="image/png" href="../../images/logo.png">
   
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<?php
	include("include/css.php");
	?>
	<style>
/* General Styles */

/* Button Styles */
.btn-add, .btn-remove {
    background-color: #007BFF; /* Blue background for buttons */
    color: white; /* White text */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px 15px; /* Padding for buttons */
    cursor: pointer; /* Pointer cursor on hover */
    margin: 5px; /* Margin between buttons */
    transition: background-color 0.3s; /* Transition effect */
}

.btn-add:hover, .btn-remove:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Form Styles */
.schedule-form {
    background-color: white; /* White background for the form */
    padding: 20px; /* Padding inside the form */
    border-radius: 10px; /* Rounded corners for the form */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Schedule Item Styles */
.schedule-item {
    margin-bottom: 15px; /* Space between schedule items */
}

/* Input Styles */
.date-input, .time-input {
    margin-left: 10px; /* Space between label and input */
    padding: 5px; /* Padding inside inputs */
    border: 1px solid #ccc; /* Border for inputs */
    border-radius: 5px; /* Rounded corners */
    width: 150px; /* Fixed width for inputs */
}

/* Label Styles */
label {
    font-weight: bold; /* Bold labels */
    margin-right: 5px; /* Space between label and input */
}

		</style>
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

		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-danger">
							<div class="box-body no-padding">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Edit Movies
									</div>
									<div class="panel-body">
										<form method="post" action="movie_update.php" enctype="multipart/form-data" autocomplete="off">
											<div class="well">

												<div class="row">

													<div class="alert" style="padding:10px; background-color:#404040; color:white;">
														<strong>Movie Information</strong>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12">
														<label>Photo</label><br>

														<img src="<?php echo $photo; ?>" width="300" height="350" class="img-rounded img-bordered" id="previewImage">

														<input type="file" name="photo" onchange="previewFile()"><br>
													</div>

													<div class="col-md-6 col-sm-12 col-xs-12">
													<input type="hidden" name="mid" class="form-control" value="<?php echo $value ?>">
														
														<br><br><label>Name</label>
														<input type="text" name="name" class="form-control" value="<?php echo $name ?>">
														<label>Description</label>
														<textarea class="form-control" rows="2" name="desc"><?php echo $desc ?></textarea>
														<label>Starring</label>
														<textarea class="form-control" rows="2" name="starring"><?php echo $starring ?></textarea>
														<label>Trailer link</label>
														<textarea class="form-control" rows="2" name="trailer"><?php echo $trailer ?></textarea>
														
														<label>Rating</label>
														<input type="text" name="rating" value="<?php echo $rating ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-3 col-sm-6 col-xs-12 well" style="float:right">
                          
						  <div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="submit" value="Update" class="btn btn-block btn-primary">
						  </div>

						  <div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="reset" value="Reset" class="btn btn-block btn-danger">
						  </div>
					  </div>
					  <br><br><br><br><br>
                        <div class="alert" style="padding:10px; background-color:#404040; color:white">
												<strong>Show time</strong>
											</div>
                      
                      <!-- <div class="col-md-6 col-sm-6 col-xs-12"> -->
					  <!-- Edit showtime -->
					  <?php
echo "<input type='button' class='btn-add' value='Add Schedule' onclick='redirectToPage()'><br>";
echo "<form id='scheduleForm' class='schedule-form'>";

$result2 = $db->prepare("SELECT * FROM schedules WHERE M_ID = '$value' AND STATUS = 0;");
$result2->execute();

for ($i = 0; $row = $result2->fetch(); $i++) {
    $sid = $row["S_ID"];
    $date = $row["DATE"];
    $time = $row["SHOW_TIME"];

    echo "<div class='schedule-item'>";
    echo "<input type='hidden' name='sid' value='$sid'>";
    echo "<label for='date-$sid'>Date:</label>";
    echo "<input type='date' id='date-$sid' name='date' value='$date' class='date-input' readonly>";
    echo "<label for='time-$sid'>Time:</label>";
    echo "<input type='time' id='time-$sid' name='time' value='$time' class='time-input' readonly>";
    echo "<input type='button' class='btn-remove' value='Remove' onclick='removeSchedule($sid)' readonly>";
    echo "</div><br>";
}

echo "</form>";
?>









										</div>
									</div>
									</div>
									
									</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
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

		<script>

var yourIdVariable = <?php echo json_encode($value); ?>;
function redirectToPage() {
        setTimeout(function() {
            // Change 'destination.php' to the actual destination page
            
    window.location.href = 'schedule.php?id=' + yourIdVariable;
                }, 3000); // 3000 milliseconds (3 seconds) delay, adjust as needed

        // You can add additional animation effects here before the redirection
    }

function removeSchedule(sid) {
    var confirmation = confirm("Are you sure you want to remove this schedule?");
    if (confirmation) {
        // Send an AJAX request to remove_schedule.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "remove_schedule.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response, e.g., reload the page or update UI
                location.reload(); // Reload the page for simplicity
            }
        };
        xhr.send("sid=" + sid);
    }
}
			function previewFile() {
				var input = document.querySelector('input[type=file]');
				var preview = document.getElementById('previewImage');

				var file = input.files[0];
				var reader = new FileReader();

				reader.onloadend = function() {
					preview.src = reader.result;
				}

				if (file) {
					reader.readAsDataURL(file);
				} else {
					// No new file selected, use the default image
					preview.src = "uploads/<?php echo $photo; ?>";
				}
			}
		</script>

</body>

</html>

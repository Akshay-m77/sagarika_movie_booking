<?php
include("auth.php");
include('../connect/db.php');

$Log_Id = $_SESSION['SESS_ADMIN_ID'];
$uid = $_SESSION['user_id'];


try {
    // Prepare the query with a placeholder
    $query = "SELECT unit FROM admin WHERE ID = :uid";
    $statement = $db->prepare($query);

    // Bind the parameter safely
    $statement->bindParam(':uid', $uid);

    // Execute the query
    $statement->execute();

    // Fetch the result and extract the 'unit' field
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $unit = $result['unit'] ?? null; // Safely handle if no result is returned
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission to add a new admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
    $username = $_POST['username'];
    $unit = $_POST['unit']; // This will only be set if unit is not 'admin'
    $rank = $_POST['rank'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $status = 1;
    
    // Insert new admin into the database
    $sql = "INSERT INTO user (NAME, unit, RANK, MOB_NO, PASSWORD, STATUS) VALUES (:username, :unit, :rank, :phone, :password, :status)";
    $stmt = $db->prepare($sql);
    $stmt->execute(['username' => $username, 'unit' => $unit, 'rank' => $rank,'phone' => $phone, 'password' => $password, 'status'=>$status]);
echo $rank;
    header("Location: addUser.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add users</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
       <?php include("include/css.php"); ?>
    <style>
        /* Your styles */
        <style>
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
            border-radius: 15px;
        }

        .data-container label {
            display: block;
            margin-bottom: 10px;
            border-radius: 15px;
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
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
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
            <h1>Add New User</h1>
        </header>

        <div class="container-wrapper">
            <div class="upload-container">
                <form id="addAdminForm" method="POST" action="addUser.php">
                    <label for="username">Name:</label>
                    <input type="text" id="username" name="username" class="l" required>

                    <?php
                    // Conditionally display the unit field if unit is not 'admin'
                    if ($unit !== "admin") {
                    ?>
                        <label for="unit">Unit:</label>
                        <input type="text" id="unit" name="unit" class="l" value="<?php echo htmlspecialchars($unit); ?>" required readonly>
                    <?php
                    } else {
                        ?>
                        <label for="unit">Unit:</label>
                        <input type="text" id="unit" name="unit" class="l" required>
                  <?php 
                  }
                    ?>
<label for="rank">Rank:</label>
<select id="rank" name="rank" class="l" required>
    <option value="">Select Rank</option>
    <option value="Officer & equivalent">Officer & equivalent</option>
    <option value="Senior sailors & equivalent">Senior sailors & equivalent</option>
    <option value="Junior sailors & equivalent">Junior sailors & equivalent</option>
    
</select>

                    <label for="phone">Phone number:</label>
                    <input type="tel" id="phone" name="phone" class="l" pattern="^\+?[0-9\s\-]{7,15}$" required>
   
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="l" required>

                    <input type="submit" name="addUser" value="Add user">
                </form>
            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>
    <div class="control-sidebar-bg"></div>
    <?php include("include/js.php"); ?>
</body>
</html>

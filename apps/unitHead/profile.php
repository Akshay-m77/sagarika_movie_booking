<?php
include("auth.php");
include('../connect/db.php');
$Log_Id = $_SESSION['SESS_ADMIN_ID'];
$uid = $_SESSION['user_id'];

// Initialize variables to store fetched data
$unit = "";

// Fetch email and phone number from the database
$sql = "SELECT unit, NAME FROM admin WHERE ID = :admin_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':admin_id', $uid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Assign fetched values to variables
if ($stmt->rowCount() > 0) {
    // Assign fetched values to variables
    $unit = $row['unit'];
    $uname = $row['NAME'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style>
        /* Modal styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Black with transparency */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 500px; /* Max width to avoid large screens */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }

        /* Input and label styling */
        label {
            display: block;
            margin-top: 15px;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Button styling */
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 30px;
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

        .profile-info label {
            font-weight: bold;
        }

        .profile-info input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .profile-info button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-info button:hover {
            background-color: #a83542;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 90%; /* Full width on smaller screens */
            }
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
            <?php include("include/topmenu.php"); ?>
        </div>

        <header class="header2">
            <h1>Admin Profile</h1>
        </header>

        <div class="container">
            <div class="profile-info">
                <label for="uname">Username:</label>
                <input type="text" id="uname" value="<?php echo htmlspecialchars($uname); ?>" readonly>
                <label for="unit">Unit:</label>
                <input type="text" id="unit" value="<?php echo htmlspecialchars($unit); ?>" readonly>
            </div>

            <!-- Password Reset Section -->
            <div class="profile-info">
                <button class="btn" onclick="openModal()">Reset Password</button>
            </div>

            <!-- Password Change Modal -->
            <div id="passwordModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2 class="header2">Change Password</h2>
                    <form id="changePasswordForm" method="POST" action="update_password.php">
                        <label for="currentPassword">Current Password:</label>
                        <input type="password" id="currentPassword" name="currentPassword" required>
                        <label for="newPassword">New Password:</label>
                        <input type="password" id="newPassword" name="newPassword" required>
                        <label for="confirmPassword">Confirm New Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <button type="submit" class="btn">Change Password</button>
                    </form>
                </div>
            </div>

            <script>
                // JavaScript functions for modal handling
                function openModal() {
                    document.getElementById("passwordModal").style.display = "flex";
                }

                function closeModal() {
                    document.getElementById("passwordModal").style.display = "none";
                }

                // Validate password confirmation before form submission
                document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
                    var newPassword = document.getElementById("newPassword").value;
                    var confirmPassword = document.getElementById("confirmPassword").value;

                    if (newPassword !== confirmPassword) {
                        event.preventDefault(); // Prevent form submission
                        alert("New password and confirmation do not match.");
                    }
                });
            </script>
        </div>
            </div>
        <?php include("include/footer.php"); ?>
        <div class="control-sidebar-bg"></div>
        <?php include("include/js.php"); ?>
    </div>
</body>
</html>

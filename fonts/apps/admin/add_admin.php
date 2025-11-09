<?php
include("auth.php");
include('../connect/db.php');

// Check if the user is an admin
if ($_SESSION['unit'] != 'admin') {
    header("Location: unauthorized.php");
    exit;
}

// Initialize error message
$error_message = "";

// Handle form submission to add a new admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $unit = $_POST['unit'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check for existing username, email, or unit
    $sql = "SELECT * FROM admin WHERE NAME = :username OR unit = :unit";
    $stmt = $db->prepare($sql);
    $stmt->execute(['username' => $username, 'unit' => $unit]);

    if ($stmt->rowCount() > 0) {
        // If a match is found, show an error message
        $error_message = "Username or Unit already exists. Please try again with different values.";
    } else {
        // No match found, insert new admin
        $sql = "INSERT INTO admin (NAME, unit, PASSWORD) VALUES (:username, :unit, :password)";
        $stmt = $db->prepare($sql);
        $stmt->execute(['username' => $username, 'unit' => $unit, 'password' => $password]);

        // Redirect after successful insertion
        header("Location: add_admin.php");
        exit;
    }
}

// Handle admin removal
if (isset($_GET['remove_id'])) {
    $admin_id = $_GET['remove_id'];
    
    // Delete admin from the database
    $sql = "DELETE FROM admin WHERE ID = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $admin_id]);

    header("Location: add_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Units</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("include/css.php"); ?>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }
      
        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            width: 100%;
        }

        .upload-container, .data-container {
            flex: 1 1 300px;
            padding: 20px;
            background-color: #8CB9BD;
            border-radius: 10px;
        }

        .upload-container {
            background-color: #e3f2fd;
        }

        .upload-container input, .upload-container label, .upload-container select, .upload-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        .data-container {
            background-color: #8CB9BD;
            border: 1px solid #ccc;
            border-radius: 15px;
            height: 600px;
            overflow-y: auto;
        }

        table {
            width: 100%;
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

        @media (max-width: 768px) {
            .header2, .container-wrapper {
                width: 100%;
                margin: 0;
                border-radius: 0;
            }

            .container {
                flex-direction: column;
            }

            .upload-container, .data-container {
                flex: 1 1 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }

            .data-container {
                height: 300px;
                width: 100%;
            }

            input[type="submit"] {
                width: 100%;
                padding: 15px;
                font-size: 18px;
            }
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
            <h1>Manage Units</h1>
        </header>
        <div class="container-wrapper">
            <div class="container">
                <div class="upload-container">
                    <h2>Add New Admin</h2>
                    <?php if (!empty($error_message)): ?>
                        <div style="color: red; font-weight: bold;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="addAdminForm" method="POST" action="add_admin.php">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="l" required>
                        
                        <label for="unit">Unit:</label>
                        <input type="text" id="unit" name="unit" class="l" required>
                       
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="l" required>

                        <input type="submit" name="add_admin" value="Add Admin">
                    </form>
                </div>

                <div class="data-container">
                    <h2>Existing units</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all admins from the database
                            $sql = "SELECT * FROM admin WHERE unit != 'admin'";
                            $stmt = $db->query($sql);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['NAME'] . "</td>";
                                echo "<td>" . $row['unit'] . "</td>";
                                echo "<td><a href='add_admin.php?remove_id=" . $row['ID'] . "' onclick='return confirm(\"Are you sure you want to remove this admin?\");'>Remove</a></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include("include/footer.php"); ?>
    <div class="control-sidebar-bg"></div>
    <?php include("include/js.php"); ?>
</body>
</html>

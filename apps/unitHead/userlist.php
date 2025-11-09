<?php
include("auth.php");
include('../connect/db.php');
$Log_Id = $_SESSION['SESS_ADMIN_ID'];

$unit = $_SESSION['unit'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Users</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <style>
        /* Your CSS styles */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 15px;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .search-container input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 16px;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #7393B3;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
    background-color: #f9f9f9; /* Alternate row colors */
}

tr:hover {
    background-color: #f1f1f1; /* Row hover effect */
}


        .btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #a83542;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            min-width: 800px;
        }
/* Responsive adjustments */
@media (max-width: 768px) {
    table {
        font-size: 14px; /* Reduced font size on smaller screens */
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
        <header class="header2">
            <h1>User List</h1>
        </header>
        <div class="container">

            <!-- Search input -->
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search">
            </div>

            <div class="table-container">
                <table id="userTable">
                    <thead height="70">
                        <tr>
                            <th style="text-align: center;">SL No.</th>
                            <th style="text-align: center;">Name</th>
                            <th style="text-align: center;">Unit</th>
                            <th style="text-align: center;">Mobile</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query = "SELECT * FROM user where STATUS!=0 and unit=$unit";
                            $result = $db->query($query);
                            $counter = 1;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $uid = $row['U_ID'];
                                echo "<tr>";
                                echo "<td>{$counter}</td>";
                                echo "<td>{$row['NAME']}</td>";
                                echo "<td>{$row['UNIT']}</td>";
                                echo "<td>{$row['MOB_NO']}</td>";
                                echo '<td style="text-align:center;"><a href="remove.php?uid=' . $uid . '" onclick="return confirmRemove(\'' . addslashes($row['NAME']) . '\')"><i class="fas fa-trash-alt"></i></a></td>';

                                echo "</tr>";
                                $counter++;
                            }
                        } catch (PDOException $e) {
                            die("Database query failed: " . $e->getMessage());
                        } finally {
                            $db = null;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include("include/footer.php"); ?>
    <div class="control-sidebar-bg"></div>
    <?php include("include/js.php"); ?>

    <script>
    // Function to filter the table based on user input
    function filterTable() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toUpperCase();
        let table = document.getElementById('userTable');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            let tdName = tr[i].getElementsByTagName('td')[1]; // Column 1 (Name)
            let tdUnit = tr[i].getElementsByTagName('td')[2]; // Column 2 (Unit)
            let tdMobile = tr[i].getElementsByTagName('td')[3]; // Column 3 (Mobile)
            
            if (tdName || tdUnit || tdMobile) {
                let nameValue = tdName.textContent || tdName.innerText;
                let unitValue = tdUnit.textContent || tdUnit.innerText;
                let mobileValue = tdMobile.textContent || tdMobile.innerText;
                
                if (nameValue.toUpperCase().indexOf(filter) > -1 || 
                    unitValue.toUpperCase().indexOf(filter) > -1 || 
                    mobileValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    }

    function confirmRemove(userName) {
    return confirm("Are you sure you want to remove " + userName + "?");
}

</script>
</body>

</html>

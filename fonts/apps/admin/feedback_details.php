<?php
include("auth.php");
include('../connect/db.php');
$Log_Id = $_SESSION['SESS_ADMIN_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $selectedFeedbackIds = $_POST['feedback_ids'] ?? [];
    if (!empty($selectedFeedbackIds)) {
        $placeholders = implode(',', array_fill(0, count($selectedFeedbackIds), '?'));
        $deleteQuery = "DELETE FROM feedback WHERE F_ID IN ($placeholders)";
        $deleteStatement = $db->prepare($deleteQuery);
        $deleteStatement->execute($selectedFeedbackIds);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Feedback</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
   
 <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .print-btn {
            float: right;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            table-layout: fixed;
        }

        th {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th:first-child {
            border-left: none;
        }

        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 200px;
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover td {
            background-color: #e9f5ff;
        }

        td {
            word-wrap: break-word;
            white-space: normal;
        }

        @media print {
            .print-btn,
            .delete-button-container,
            .button-container {
                display: none;
            }
        }

        .header2 {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 10px;
            border-radius: 20px;
            height: 110px;
        }

        .table-container {
            overflow-x: auto;
        }

        .select-all-checkbox {
            text-align: center;
            width: 5%;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-icon {
            margin-right: 5px;
        }

        button a {
            color: white;
            text-decoration: none;
        }

        .button-container {
            text-align: left;
        }
    </style>
</head>

<body>
    <header class="header2">
        <div class="button-container">
            <a href="feedback.php"><button><i class="fas fa-arrow-left back-icon"></i>Back</button></a>
        </div>
        <h1>Feedback Details</h1>
    </header>

    <div class="container">
        <div>
            Start Date: <input type="date" id="start-date" name="start_date">
            End Date: <input type="date" id="end-date" name="end_date">
            <button onclick="filterByDate()">Filter</button>
        </div>

        <div class="table-container">
            <form method="post" action="">
                <table>
                    <colgroup>
                        <col style="width: 5%;">
                        <col style="width: 10%;">
                        <col style="width: 15%;">
                        <col style="width: 30%;">
                        <col style="width: 12%;">
                        <col style="width: 5%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="text-align:center;">S.No.</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">Rating</th>
                            <th>Feedback</th>
                            <th style="text-align:center;">Date</th>
                            <th class="select-all-checkbox">
                                <input type="checkbox" id="select-all-checkbox" onclick="toggleSelectAll()">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="feedback-table-body">
                        <?php
                        try {
                            $service = $_GET['service'];
                            $startDate = $_GET['start_date'] ?? null;
                            $endDate = $_GET['end_date'] ?? null;

                            $query = "SELECT f.F_ID, u.NAME, f.RATING, f.DESCRIPTION, f.DATE
                                      FROM feedback f
                                      INNER JOIN user u ON f.U_ID = u.U_ID
                                      WHERE f.SERVICES = :service";

                            if ($startDate && $endDate) {
                                $query .= " AND f.DATE BETWEEN :start_date AND :end_date";
                            }

                            $query .= " ORDER BY f.DATE DESC";

                            $statement = $db->prepare($query);
                            $statement->bindParam(':service', $service);
                            if ($startDate && $endDate) {
                                $statement->bindParam(':start_date', $startDate);
                                $statement->bindParam(':end_date', $endDate);
                            }
                            $statement->execute();
                            $feedbackData = $statement->fetchAll(PDO::FETCH_ASSOC);
                            echo "<center><h1>" . htmlspecialchars($service) . "</h1></center>";

                            foreach ($feedbackData as $index => $item) {
                                $ratingStars = str_repeat('★', $item['RATING']) . str_repeat('☆', 5 - $item['RATING']);
                                echo "<tr>
                                        <td>" . ($index + 1) . "</td>
                                        <td>" . htmlspecialchars($item['NAME']) . "</td>
                                        <td>" . $ratingStars . "</td>
                                        <td>" . htmlspecialchars($item['DESCRIPTION']) . "</td>
                                        <td style='text-align:center;'>" . htmlspecialchars($item['DATE']) . "</td>
                                        <td style='text-align:center;'>
                                            <input type='checkbox' name='feedback_ids[]' value='" . htmlspecialchars($item['F_ID']) . "'>
                                        </td>
                                    </tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
                <button class="print-btn" onclick="window.print()"><i class="fas fa-print">PRINT</i></button>
                <button class="delete-button-container" type="submit" name="delete" onclick="showAlert()">Delete Selected</button>
            </form>
        </div>
    </div>

    <script>
        function showAlert() {
            alert("Are you sure you want to delete the selected feedback?");
        }

        function filterByDate() {
            const startDate = document.getElementById("start-date").value;
            const endDate = document.getElementById("end-date").value;
            window.location.href = `feedback_details.php?service=<?php echo htmlspecialchars($service); ?>&start_date=${startDate}&end_date=${endDate}`;
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[name="feedback_ids[]"]');
            const selectAllCheckbox = document.getElementById("select-all-checkbox");
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        }
    </script>
</body>

</html>

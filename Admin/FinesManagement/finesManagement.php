<?php
require_once '../../Database/database.php';

$database = new Database();
$db = $database->getConnect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../../Admin/Fines Management/finesManagement.css">

    <script>
        $(document).ready(function () {
            $('#finesTable').DataTable();
        });
    </script>
</head>

<body>
<h1>ADMIN DASHBOARD</h1>
    <div class="Container">
        <div class="side_dashboard">
            <nav>
                <ul>
                    <li><a href="../../Admin/BookManagement/bookManagement.php">Book Management</a></li>
                    <li><a href="../../Admin/UserManagement/userManagement.php">User Management</a></li>
                    <li><a href="../../Admin/BorrowManagement/borrowManagement.php">Borrow Management</a></li>
                    <li><a href="../../Admin/FinesManagement/finesManagement.php">Fines Management</a></li>
                    <li><a href="../../Admin/AdminAccount/adminAccount.php">Admin Account</a></li>
                </ul>
            </nav>
        </div>

        <div class="second_container">
            <div class="main_content">
                <h2>Overdue Fines</h2>

                <table id="finesTable" class="display">
                    <thead>
                        <tr>
                            <th>Fee ID</th>
                            <th>Reservation ID</th>
                            <th>Expected Return Date</th>
                            <th>Current Date</th>
                            <th>Overdue Fine (PHP)</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $query = "SELECT o.fee_id, o.reservation_id, r.expected_return_date, 
                                         DATEDIFF(CURDATE(), r.expected_return_date) AS overdue_days,
                                         CASE WHEN DATEDIFF(CURDATE(), r.expected_return_date) > 0 THEN 
                                              DATEDIFF(CURDATE(), r.expected_return_date) * 10 ELSE 0 END AS overdue_fine
                                  FROM overdue_fees o
                                  JOIN reservation r ON o.reservation_id = r.reservation_id";
                        $stmt = $db->prepare($query);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['fee_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['expected_return_date']) . "</td>";
                            echo "<td>" . htmlspecialchars(date("Y-m-d")) . "</td>";
                            echo "<td>" . htmlspecialchars($row['overdue_fine']) . "</td>";
                            echo "</tr>";
                        }
                        ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</body>
</html>
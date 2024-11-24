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
    <title>Borrow Management</title>

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

    <link rel="stylesheet" href="../../Admin/Borrow Management/borrowManagement.css">

    <script>
        $(document).ready(function () {
            $('#borrowTable').DataTable();
        });
    </script>
</head>

<body>
<h1>ADMIN DASHBOARD</h1>
    <div class="Container">
        <div class="side_dashboard">
            <nav>
                <ul>
                    <li><a href="../../Admin/Book Management/index.php">Book Management</a></li>
                    <li><a href="../../Admin/User Management/userManagement.php">User Management</a></li>
                    <li><a href="../../Admin/Borrow Management/borrowManagement.php">Borrow Management</a></li>
                    <li><a href="../../Admin/Fines Management/finesManagement.php">Fines Management</a></li>
                    <li><a href="../../Admin/Admin Account/adminAccount.php">Admin Account</a></li>
                </ul>
            </nav>
        </div>

        <div class="second_container">
            <div class="main_content">
                <h2>Borrow History</h2>

                <table id="borrowTable" class="display">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Reservation Date</th>
                            <th>Pickup Date</th>
                            <th>Duration (Days)</th>
                            <th>Expected Return Date</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Book ID</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Fetch borrow history from the database
                        $query = "SELECT reservation_id, name, email, phone_number, reservation_date, pickup_date, 
                                  duration, notes, status, Book_ID 
                                  FROM reservation";
                        $stmt = $db->prepare($query);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Calculate the expected return date
                            $pickup_date = new DateTime($row['pickup_date']);
                            $expected_return_date = clone $pickup_date;
                            $expected_return_date->modify('+' . $row['duration'] . ' days');

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reservation_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pickup_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['duration']) . "</td>";
                            echo "<td>" . htmlspecialchars($expected_return_date->format('Y-m-d')) . "</td>";
                            echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Book_ID']) . "</td>";
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

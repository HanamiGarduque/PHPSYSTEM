<?php
require_once '../../check_session.php';
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

    <link rel="stylesheet" href="../../Admin/BorrowManagement/borrowManagement.css">

    <script>
        $(document).ready(function () {
            $('#borrowTable').DataTable();
        });

        function showConfirmation(reservationId, formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update the status of this reservation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, submit the form
                    document.getElementById(formId).submit();
                }
            });
        }
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
                            <th>Set Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT reservation_id, name, email, phone_number, reservation_date, expected_return_date, pickup_date, 
                                duration, notes, status, Book_ID 
                                FROM reservation";
                        $stmt = $db->prepare($query);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reservation_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pickup_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['duration']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['expected_return_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Book_ID']) . "</td>";
                            echo "<td>";
                            // Form with dropdown and submit button
                            echo "<form method='POST' id='statusForm_" . $row['reservation_id'] . "' action='update_status.php'>";
                            echo "<input type='hidden' name='reservation_id' value='" . $row['reservation_id'] . "'>";
                            echo "<select name='status'>
                                    <option value='Approved' " . ($row['status'] == 'Approved' ? 'selected' : '') . ">Approved</option>
                                    <option value='Done' " . ($row['status'] == 'Done' ? 'selected' : '') . ">Done</option>
                                    <option value='Overdue' " . ($row['status'] == 'Overdue' ? 'selected' : '') . ">Overdue</option>
                                    <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                </select>";
                            echo "<button type='button' onclick='showConfirmation(" . $row['reservation_id'] . ", \"statusForm_" . $row['reservation_id'] . "\")'>Submit</button>";
                            echo "</form>";
                            echo "</td>";
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

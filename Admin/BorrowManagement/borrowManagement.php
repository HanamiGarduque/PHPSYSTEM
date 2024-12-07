<?php
require_once '../../check_session.php';
require_once '../../Database/database.php';
require_once '../../Database/crud.php';

ensureAdminAccess();
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
        $(document).ready(function() {
            $('#borrowTable').DataTable();
        });

        function showConfirmation(reservationId, formId) {
            const form = document.getElementById(formId);
            const select = form.querySelector('.statusDropdown');
            const currentStatus = select.getAttribute('data-current-status');
            const selectedStatus = select.value;

            if (currentStatus == selectedStatus) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Changes Detected',
                    text: 'The selected status is the same as the current status. No action is needed.',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to update the status of this reservation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
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
                    <li><a href="../../Admin/ReservationLog/reservationLog.php">Reservation Log</a></li>
                    <li><a href="../../Admin/AdminAccount/adminAccount.php">Admin Account</a></li>
                </ul>
            </nav>
        </div>

        <div class="second_container">
            <div class="main_content">
                <h2>Borrow History</h2>
                <form method="POST" action="">
                    <button type="submit">Delete Selected</button>
                    <table id="borrowTable" class="display">
                        <thead>
                            <tr>
                                <th>Select</th>
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
                            $reservation = new Reservations($db);
                            $stmt = $reservation->read();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='reservation_ids[]' value='" . htmlspecialchars($row['reservation_id']) . "'></td>";
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
                                echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                                echo "<td>";
                                echo "<form method='POST' id='statusForm_" . $row['reservation_id'] . "' action='approveReservation.php'>";
                                echo "<input type='hidden' name='reservation_id' value='" . $row['reservation_id'] . "'>";
                                echo "<select name='status' class='statusDropdown' " . ($row['status'] == 'Done' ? 'disabled' : '') . ($row['status'] == 'Cancelled' ? 'disabled' : '') . " data-current-status='" . htmlspecialchars($row['status']) . "'>";  // Pass the current status
                                echo "<option value='Approved' " . ($row['status'] == 'Approved' ? 'selected' : '') . ">Approved</option>
                                    <option value='Active' " . ($row['status'] == 'Active' ? 'selected' : '') . ">Active</option>
                                    <option value='Done' " . ($row['status'] == 'Done' ? 'selected' : '') . ">Done</option>
                                    <option value='Overdue' " . ($row['status'] == 'Overdue' ? 'selected' : '') . ">Overdue</option>
                                    <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                </select>";
                                if ($row['status'] != 'Done' && $row['status'] != 'Cancelled') {
                                    echo "<button type='button' onclick='showConfirmation(" . $row['reservation_id'] . ", \"statusForm_" . $row['reservation_id'] . "\")'>Submit</button>";
                                }
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['reservation_ids']) && !empty($_POST['reservation_ids'])) {
                            $reservationIds = $_POST['reservation_ids'];
                            $reservation = new Reservations($db);
                    
                            foreach ($reservationIds as $reservationId) {
                                if ($reservation->delete($reservationId)) {
                                    echo "Selected reservations have been deleted.";

                                }
                                // Assuming you have a delete function in your Reservations class.
                            }
                    
                        } else {
                            echo "No reservations selected for deletion.";
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
<?php
require_once '../../Database/database.php';
require_once '../../Database/crud.php';

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

    <link rel="stylesheet" href="../../Admin/FinesManagement/finesManagement.css">

    <script>
        $(document).ready(function() {
            $('#finesTable').DataTable();
        });

        function showConfirmation(feeId, formId) {
            Swal.fire({
                title: 'Update Status',
                text: "User already paid?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paid_' + feeId).value = '1';
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
                <h2>Overdue Fines</h2>

                <table id="finesTable" class="display">
                    <thead>
                        <tr>
                            <th>Fee ID</th>
                            <th>Reservation ID</th>
                            <th>Fine or Fee</th>
                            <th>Amount (PHP)</th>
                            <th>Reason</th>
                            <th>Imposed By</th>
                            <th>Date Imposed</th>
                            <th>Paid</th>
                            <th>Set Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $finesAndFees = new FinesAndFees($db);
                        $stmt = $finesAndFees->read();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['fee_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['fine_or_fee']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['imposed_by']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date_imposed']) . "</td>";
                            echo "<td>" . ($row['paid'] ? 'Yes' : 'No') . "</td>";
                            echo "<td>";
                            echo "<form id='statusForm_" . htmlspecialchars($row['fee_id']) . "' method='POST' action=''>";
                            echo "<input type='hidden' name='fee_id' value='" . htmlspecialchars($row['fee_id']) . "'>";
                            echo "<input type='hidden' name='paid' id='paid_" . htmlspecialchars($row['fee_id']) . "' value='" . ($row['paid'] ? '1' : '0') . "'>";  // Hidden input for 'paid' status
                            echo "<button type='button' onclick='showConfirmation(" . htmlspecialchars($row['fee_id']) . ", \"statusForm_" . htmlspecialchars($row['fee_id']) . "\")'>Update Status</button>";
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
    <?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fee_id = $_POST['fee_id'];
        $paid = $_POST['paid'];  // This will be 'Yes' if the user confirmed
    
        // Assuming you have a method in your FinesAndFees class to update the 'paid' status
        $finesAndFees = new FinesAndFees($db);
        
        // Call a method to update the 'paid' field for the fee_id
        if ($finesAndFees->updatePaymentStatus($fee_id, $paid)) {
            echo "Paid status updated successfully.";
        } else {
            echo "Failed to update paid status.";
        }
    }
    ?>

</body>

</html>
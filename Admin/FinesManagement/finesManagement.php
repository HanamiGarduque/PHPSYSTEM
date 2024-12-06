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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../Admin/FinesManagement/finesManagement.css">

    <script>
        $(document).ready(function() {
            $('#finesTable').DataTable({
                scrollX: true,
                scrollY: '400px',
                scrollCollapse: true, 
                paging: true, 
                autoWidth: false
            })
            $('#finesPerUser').DataTable({
                scrollX: true, 
                scrollY: '400px', 
                scrollCollapse: true, 
                paging: true,
                autoWidth: false
            });
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
                <table id="finesPerUser" class="display">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Set Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $finesAndFees = new FinesAndFees($db);
                        $query = "
                        SELECT f.fee_id, f.user_id, SUM(f.amount) as total_amount, u.username, f.paid 
                        FROM fines_and_fees as f
                        JOIN users as u on f.user_id = u.user_id
                        WHERE f.paid = 0
                        GROUP BY f.user_id";


                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $userName = $row['username'];
                            $total_amount = $row['total_amount'];
                            echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                            echo "<td>" . ($row['paid'] ? 'Yes' : 'No') . "</td>";
                            echo "<td>";
                            echo "<form id='statusForm_" . htmlspecialchars($row['fee_id']) . "' method='POST' action=''>";
                            echo "<input type='hidden' name='fee_id' value='" . htmlspecialchars($row['fee_id']) . "'>";
                            echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "'>";
                            echo "<input type='hidden' name='paid' id='paid_" . htmlspecialchars($row['fee_id']) . "' value='" . ($row['paid'] ? '1' : '0') . "'>";  // Hidden input for 'paid' status
                            echo "<button type='button' onclick='showConfirmation(" . htmlspecialchars($row['fee_id']) . ", \"statusForm_" . htmlspecialchars($row['fee_id']) . "\")'>Update Status</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <table id="finesTable" class="display">
                    <thead>
                        <tr>
                            <th>Fee ID</th>
                            <th>Reservation ID</th>
                            <th>Fine or Fee</th>
                            <th>Amount (PHP)</th>
                            <th>Reason</th>
                            <th>Imposed By (Admin ID)</th>
                            <th>Date Imposed</th>
                            <th>User ID</th>
                            <th>Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
                            echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                            echo "<td>" . ($row['paid'] ? 'Yes' : 'No') . "</td>";
                            echo "<td>";
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
        $paid = $_POST['paid'];
        $user_id = $_POST['user_id'];
        var_dump($paid, $user_id); // Check if the correct data is submitted
        $notifications = new Notifications($db);
        $notifications->user_id = $user_id;
        $finesAndFees->paid = $paid;
        echo $paid;

        if ($finesAndFees->updatePaymentStatus($user_id)) {
            $finesAndFees->updatePaymentStatus($user_id);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $userName = $row['username'];
            $total_amount = $row['total_amount'];

            $notifications->paymentCreated($userName, $total_amount);
        } else {
            echo "Failed to update paid status.";
        }
    }
    ?>

</body>

</html>
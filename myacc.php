<?php
require_once 'check_session.php';
require_once 'Database/database.php';
require_once 'Database/crud.php';

$database = new Database();
$db = $database->getConnect();

$user = new Users($db);
$stmt = $user->getUserDetails($_SESSION['id']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "Error: User details not found.";
    exit;
}

$reservation = new Reservations($db);
$stmt = $reservation->getUserReservations($_SESSION['id']);
$reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fines_and_fees = new FinesAndFees($db);
$stmt = $fines_and_fees->getUserFines($_SESSION['id']);
$fines = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalUnpaidFees = 0;
foreach ($fines as $fine) {
    if (!$fine['paid']) {
        $totalUnpaidFees += $fine['amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="./CSS/myacc.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#finesTable').DataTable({
                scrollX: true,
                scrollY: '185px',
                scrollCollapse: true, 
                paging: false,
                autoWidth: false,
                searching: false
             });

            $('#reservationTable').DataTable({
                scrollX: true,
                scrollY: '185px',
                scrollCollapse: true,
                paging: false,
                autoWidth: false,
                searching: false
            });
        });

        function showConfirmation(reservationId, formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel your reservation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
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
    <header class="header">
        <div class="logo"></div>
        <nav class="nav">
            <a href="homepage.php">Home</a>
            <a href="search_catalog.php">Search a Book</a>
            <a href="notifications.php">Notifications</a>
            <a href="myacc.php" style="color: #F7E135;">My Account</a>
        </nav>
    </header>

    <section id="myAccount">
        <div class="container">
            <h2>My Account Details</h2>
            <table class="account-table">
                <tr>
                    <th>Username:</th>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <th>First Name:</th>
                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                </tr>
                <tr>
                    <th>Last Name:</th>
                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number:</th>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                </tr>
            </table>
        </div>
    </section>

    <section id="bookBorrow">
        <div class="container">
            <h2>Book Borrowing Details</h2>
            <?php
            if (empty($reservation)) {
                echo "<p>You have not borrowed books yet. Borrow now and start reading!</p>";
            } else {
            ?>
                <table id="reservationTable" class="display">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Reservation Date</th>
                            <th>Pickup Date</th>
                            <th>Duration (Days)</th>
                            <th>Expected Return Date</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservation as $reservation) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['Book_Title']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['Book_Author']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['pickup_date']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['duration']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['expected_return_date']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['notes']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                                <td>
                                    <?php if ($reservation['status'] != 'Done' && $reservation['status'] != 'Cancelled' &&  $reservation['status'] != 'Overdue') { ?>
                                        <form method='POST' id='cancelForm_<?php echo $reservation['reservation_id']; ?>' action='cancelReservation.php'>
                                            <input type='hidden' name='reservation_id' value='<?php echo $reservation['reservation_id']; ?>'>
                                            <button type='button' onclick='showConfirmation(<?php echo $reservation["reservation_id"]; ?>, "cancelForm_<?php echo $reservation["reservation_id"]; ?>")'>Cancel</button>
                                        </form>
                                    <?php } else { ?>
                                        <span> </span>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                    </tbody>
                </table>
            <?php

            }
            ?>
        </div>
    </section>

    <section id="finesAndFees">
        <div class="container">
            <h2>Fines and Fees</h2>
            <?php
            if (empty($fines)) {
                echo "<p>You have no outstanding fines or fees. Keep it up!</p>";
            } else {
            ?>
                <table id="finesTable" class="display">
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Amount</th>
                            <th>Date Imposed</th>
                            <th>Paid Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fines as $fine) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fine['reason']); ?></td>
                                <td><?php echo htmlspecialchars($fine['amount']); ?></td>
                                <td><?php echo htmlspecialchars($fine['date_imposed']); ?></td>
                                <td><?php echo $fine['paid'] ? "Paid" : "Unpaid"; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div id="totalUnpaidFees" style="margin-top: 10px;">
                    <strong>Total Unpaid Fees:</strong> Php. <?php echo number_format($totalUnpaidFees, 2); ?>
                </div>
            <?php
            }
            ?>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>
    </section>
</body>

</html>
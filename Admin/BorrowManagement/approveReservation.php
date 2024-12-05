<?php
require_once '../../check_session.php';
require_once '../../Database/database.php';
require_once '../../Database/crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : die('Reservation ID not found.');
    $status = isset($_POST['status']) ? $_POST['status'] : die('Status not selected.');

    $database = new Database();
    $db = $database->getConnect();

    $reservation = new Reservations($db);
    $notification = new Notifications($db);
    $reservationLog = new ReservationLog($db);
    $finesAndFees = new FinesAndFees($db);

    if (!isAdmin()) {
        exit('You are not authorized.');
    }

    // Fixing the missing comma in the SQL query
    $query = "
        SELECT 
            b.Book_Title,
            r.user_id,
            u.username,
            b.Book_Author, 
            r.reservation_date,
            r.pickup_date,
            r.duration,
            r.expected_return_date
        FROM reservation r
        INNER JOIN books b ON r.book_id = b.Book_ID
        INNER JOIN users u ON r.user_id = u.user_id
        WHERE r.reservation_id = :reservation_id
    ";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        $bookTitle = $row['Book_Title'];
        $userName = $row['username'];
        $expected_return_date = $row['expected_return_date'];
        $reservation_date = $row['reservation_date'];  // For cancellation logic
        $pickup_date = $row['pickup_date'];  // For possible future use

        // Set status based on the provided status
        if ($status == 'Approved') {
            $reservation->updateStatus('Approved');
            $notification->approvedBooking($userName, $bookTitle);
            if ($reservationLog->create('Approved', $_SESSION['id'])) {
                echo "Reservation log created successfully.";
            } else {
                echo "Failed to create reservation log.";
            }
        } else if ($status == 'Active') {
            $reservation->updateStatus('Active');
            $notification->activeBooking($userName, $bookTitle);
        } else if ($status == 'Done') {
            $reservation->updateStatus('Done');
            $notification->bookingReturnCompleted($userName, $bookTitle);
        } else if ($status == 'Cancelled') {
            $reservation->updateStatus('Cancelled');
            $notification->adminCancelledBooking($userName, $bookTitle);

            // Create reservation log entry for cancellation
            if ($reservationLog->create($reservation_id, 'Cancelled', $_SESSION['id'])) {
                echo "Cancellation log created successfully.";
            }

            // Get cancellation date from the log (Timestamp from the log)
            $cancellation_date = $reservationLog->getTimestamp(); // Make sure your `getTimestamp` method works

            // Convert expected return date and cancellation date to DateTime objects for comparison
            $expectedReturnDate = new DateTime($expected_return_date);
            $cancellationDate = new DateTime($cancellation_date);

            // Check if the cancellation was before the expected return date
            if ($expectedReturnDate > $cancellationDate) {
                // Apply a fee if cancellation was made before the expected return date
                $finesAndFees->create($reservation_id, 'Fee', 50.00, 'Cancellation before expected return', $_SESSION['id'], false);
                echo "Fee applied for cancellation before expected return date.";
            }

            // Redirect after fee application
            header('Location: borrowManagement.php');
            exit(); // To stop further execution after redirection
        }
    } else {
        echo 'Reservation not found.';
    }
} else {
    echo 'Invalid request method.';
}
?>

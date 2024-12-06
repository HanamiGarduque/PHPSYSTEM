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
    $book = new Books($db);

    if (!isAdmin()) {
        exit('You are not authorized.');
    }

    $query = "
        SELECT 
            r.Book_ID,  
            r.reservation_id,
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
        $notification->user_id = $row['user_id']; // fetch user id from resrvations
        $book->Book_ID = $row['Book_ID']; // fetch book id
        $user_id = $row['user_id'];
        $bookTitle = $row['Book_Title'];
        $userName = $row['username'];
        $expected_return_date = $row['expected_return_date'];
        $reservation_date = $row['reservation_date'];  // For cancellation logic
        $pickup_date = $row['pickup_date'];

        if ($status == 'Approved') {
            $reservation->updateStatus($reservation_id, 'Approved');
            $notification->approvedBooking($userName, $bookTitle);
            $reservationLog->create($reservation_id, 'Approved', $_SESSION['id']);

            $book->minusBookCopies();
            header('Location: borrowManagement.php');
        } else if ($status == 'Active') {
            $reservation->updateStatus($reservation_id, 'Active');
            $notification->activeBooking($userName, $bookTitle);
            $reservationLog->create($reservation_id, 'Active', $_SESSION['id']);
            header('Location: borrowManagement.php');
        } else if ($status == 'Done') {
            $reservation->updateStatus($reservation_id, 'Done');
            $notification->bookingReturnCompleted($userName, $bookTitle);
            $reservationLog->create($reservation_id, 'Done', $_SESSION['id']);

            $book->addBookCopies();
            
            header('Location: borrowManagement.php');
        } else if ($status == 'Cancelled') {
            $reservation->updateStatus($reservation_id, 'Cancelled');
            $notification->adminCancelledBooking($userName, $bookTitle);
            $reservationLog->create($reservation_id, 'Cancelled', $_SESSION['id']);

            $cancellationDate = new DateTime();
            $expectedReturnDate = new DateTime($expected_return_date);
            $pickup_date = new DateTime($pickup_date);
            //check if cancellation date is earlier than expected date
            if ($expectedReturnDate > $cancellationDate && $cancellationDate > $pickup_date) {
                $finesAndFees->reservation_id = $reservation_id;
                $finesAndFees->paid = false;

                if ($finesAndFees->create('Fee', 50.00, 'Cancellation before expected return', $_SESSION['id'])) {
                    echo "Fee applied for cancellation before expected return date.";
                } else {
                    echo "Failed to apply the fee.";
                }
            }
            header('Location: borrowManagement.php');
            exit();
        } else if ($status == 'Overdue') {
            echo $user_id;
            $reservation->updateStatus($reservation_id, 'Overdue');
            $notification->overdueBooking($userName, $bookTitle);
            $reservationLog->create($reservation_id, 'Overdue', $_SESSION['id']);
            $finesAndFees->reservation_id = $reservation_id;
            $finesAndFees->paid = false;
            $finesAndFees->user_id = $user_id;

            $finesAndFees->create('Fee', 200.00, 'Book not returned at expected return date', $_SESSION['id'], );
            header('Location: borrowManagement.php');
            exit();
        }
    } else {
        echo 'Reservation not found.';
    }
} else {
    echo 'Invalid request method.';
}

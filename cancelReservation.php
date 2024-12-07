<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : die('Reservation ID not found.');

    $reservation = new Reservations($db);
    $notification = new Notifications($db);
    $finesAndFees = new FinesAndFees($db);
    $reservationLog = new ReservationLog($db);

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

    $reservation->reservation_id = $reservation_id;
    $notification->user_id = $_SESSION['id'];
    $userName = $row['username'];
    $bookTitle = $row['Book_Title'];



    $reservation->updateStatus($reservation_id, 'Cancelled');
    $notification->userCancelledBooking($userName, $bookTitle);
    $reservationLog->create($reservation_id, 'Cancelled', $_SESSION['id']);
    $cancellationDate = new DateTime();
    $expectedReturnDate = new DateTime($expected_return_date);
    $pickup_date = new DateTime($pickup_date);
    //check if cancellation date is earlier than expected date
    if ($expectedReturnDate > $cancellationDate && $cancellationDate > $pickup_date) {
        $finesAndFees->reservation_id = $reservation_id;
        $finesAndFees->paid = false;
        $book->addBookCopies();

        if ($finesAndFees->create('Fee', 50.00, 'Cancellation before expected return', $_SESSION['id'])) {
            header("Location: myacc.php");
        } else {
            echo "Failed to apply the fee.";
        }
    }

    header('Location: myacc.php');
}

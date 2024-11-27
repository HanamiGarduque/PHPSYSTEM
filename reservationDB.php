<?php
session_start();
require_once './Database/database.php';
require_once './Database/crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Initialize database connection
    $database = new Database();
    $db = $database->getConnect();

    // Initialize the Reservations object
    $reservation = new Reservations($db);
    $book = new Books($db);
    $notification = new Notifications($db);
    $Book_ID = isset($_POST['book_id']) ? $_POST['book_id'] : die('ERROR: Book ID not found.');
    $book->Book_ID = $Book_ID; // assign book_ID


    // Sanitize and assign form data to the reservation object
    $reservation->book_id = $Book_ID;
    $reservation->name = htmlspecialchars(trim($_POST['name']));
    $reservation->email = htmlspecialchars(trim($_POST['email']));
    $reservation->phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $reservation->reservation_date = htmlspecialchars(trim($_POST['reservation_date']));
    $reservation->pickup_date = htmlspecialchars(trim($_POST['pickup_date']));
    $reservation->duration = htmlspecialchars(trim($_POST['duration']));
    $reservation->expected_return_date = htmlspecialchars(trim($_POST['expected_return_date']));    
    $reservation->notes = htmlspecialchars(trim($_POST['notes']));
    


    // Attempt to create the reservation
    if ($reservation->create()) {
        $book->updateBookCopies($Book_ID); 
        
        $notificationTitle = 'Pending Book Borrowing Request';
        // $notificationMessage = pendingBooking($_POST['name'], $book->Book_Title);

        $notification->user_id = $_SESSION['user_id'];  // Assuming the user is logged in
        $notification->message = $notificationMessage;


        if ($notification->create()) {
            // Store notification in the session for displaying it on the frontend
            $_SESSION['notification'] = [
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'type' => 'info',  // For styling purposes
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Your reservation has been successfully created!';
        $_SESSION['book_title'] = $book->Book_Title;
        $_SESSION['reservation_id'] = $reservation->reservation_id;
        $_SESSION['expected_return_date'] = $reservation->expected_return_date;

    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Failed to create the reservation. Please try again.';
    
    }

    header("Location: book.php");
    exit();
        
    
}
?>

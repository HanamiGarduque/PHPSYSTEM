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

    // Sanitize and assign form data to the reservation object
    $reservation->name = htmlspecialchars(trim($_POST['username']));
    $reservation->email = htmlspecialchars(trim($_POST['email']));
    $reservation->phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $reservation->reservation_date = htmlspecialchars(trim($_POST['reservation_date']));
    $reservation->pickup_date = htmlspecialchars(trim($_POST['pickup_date']));
    $reservation->expected_return_date = htmlspecialchars(trim($_POST['expected_return_date']));    
    $reservation->notes = htmlspecialchars(trim($_POST['notes']));

    // Attempt to create the reservation
    if ($reservation->create()) {
        $books->updateBookCopies();
        $_SESSION['status'] = 'success';

    } else {
        $_SESSION['status'] = 'error';
    }

    header("Location: bookCatalog.php");
    exit();
        
    
}
?>

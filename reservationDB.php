<?php
require_once './Database/database.php';
require_once './Database/crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Initialize database connection
    $database = new Database();
    $db = $database->getConnect();

    // Initialize the Reservations object
    $reservation = new Reservations($db);

    // Sanitize and assign form data to the reservation object
    $reservation->username = htmlspecialchars(trim($_POST['username']));
    $reservation->email = htmlspecialchars(trim($_POST['email']));
    $reservation->phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $reservation->pickup_date = htmlspecialchars(trim($_POST['pickup_date']));
    $reservation->expected_return_date = htmlspecialchars(trim($_POST['expected_return_date']));
    $reservation->reservation_date = htmlspecialchars(trim($_POST['reservation_date']));
    $reservation->notes = htmlspecialchars(trim($_POST['notes']));

    // Attempt to create the reservation
    if ($reservation->create()) {
        // SweetAlert for successful reservation creation
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Reservation created successfully!',
                    icon: 'success',
                    confirmButtonText: 'Okay',
                    background: '#fff',
                    backdrop: true
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error creating the reservation.',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    background: '#fff',
                    backdrop: true
                });
              </script>";
    }
}
?>

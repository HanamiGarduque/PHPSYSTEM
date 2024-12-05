<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    $reservation = new Reservations($db);
    $reservation->reservation_id = $reservation_id;

    if ($reservation->updateStatus('Cancelled')) {
        header('Location: notifications.php?status=success');
    } else {
        header('Location: notifications.php?status=error');
    }
} else {
    header('Location: notifications.php?status=error');
}
?>
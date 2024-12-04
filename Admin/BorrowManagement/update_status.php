<?php
require_once '../../Database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : die('Reservation ID not found.');
    $status = isset($_POST['status']) ? $_POST['status'] : die('Status not selected.');

    $database = new Database();
    $db = $database->getConnect();

    $query = "UPDATE reservation SET status = :status WHERE reservation_id = :reservation_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':reservation_id', $reservation_id);

    if ($stmt->execute()) {
        header('Location: borrowManagement.php');  
    } else {
        echo "Error updating status.";
    }
}
?>

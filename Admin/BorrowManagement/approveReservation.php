<?php
require_once '../../Database/database.php';

if (isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];

    $database = new Database();
    $db = $database->getConnect();

    // Update the reservation status to "Active"
    $query = "UPDATE reservation SET status = :status WHERE reservation_id = :reservation_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':reservation_id', $reservation_id);

    $status = 'Active';

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
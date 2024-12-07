<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

if (isset($_GET['notification_id']) && is_numeric($_GET['notification_id'])) {
    $notification_id = (int) $_GET['notification_id'];

    $notification = new Notifications($db);
    $notification->notification_id = $notification_id;

    try {
        if ($notification->delete()) {
            header('Location: notifications.php?status=success');
            exit();
        } else {
            header('Location: notifications.php?status=error&message=Deletion failed.');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error deleting notification: " . $e->getMessage(), 0);
        header('Location: notifications.php?status=error&message=Database error.');
        exit();
    }
} else {
    header('Location: notifications.php?status=error&message=Invalid notification ID.');
    exit();
}
?>

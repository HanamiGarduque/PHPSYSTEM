<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];

    $notification = new Notifications($db);
    $notification->notification_id = $notification_id;

    // if ($notification->delete()) {
    //     header('Location: notifications.php?status=success');
    // } else {
    //     header('Location: notifications.php?status=error');
    // }
} else {
    header('Location: notifications.php?status=error');
}
?>

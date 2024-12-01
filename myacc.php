<?php
// Include database connection
require_once './Database/database.php';
require_once './Database/crud.php'

// Fetch user details


// Fetch current reservations


// Fetch notifications


// Logout function

    session_start();
    session_destroy();
    header("Location: login.php"); // Redirect to login page
    exit();


// Initialize database connection
$db = new Database();
$conn = $db->getConnect();

// Example user ID (replace with session or dynamic user data)
$userId = 1;

// Fetch data
$userDetails = getUserDetails($conn, $userId);
$reservations = getUserReservations($conn, $userId);
$notifications = getUserNotifications($conn, $userId);

// Logout action
if (isset($_POST['logout'])) {
    logoutUser();
}
?>

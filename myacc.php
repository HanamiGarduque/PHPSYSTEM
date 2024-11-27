<?php
// Include database connection
require_once 'Database.php';

// Fetch user details
function getUserDetails($conn, $userId) {
    $query = "SELECT username, email, contact_number FROM users WHERE id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch current reservations
function getUserReservations($conn, $userId) {
    $query = "SELECT 
                books.Book_Title, books.Book_Author, reservations.status 
              FROM reservations 
              INNER JOIN books ON reservations.book_id = books.id 
              WHERE reservations.user_id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch notifications
function getUserNotifications($conn, $userId) {
    $query = "SELECT subject, message FROM notifications WHERE user_id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Logout function
function logoutUser() {
    session_start();
    session_destroy();
    header("Location: login.php"); // Redirect to login page
    exit();
}

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

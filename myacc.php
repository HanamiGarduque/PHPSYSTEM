<?php

session_start();
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

$user = new Users($db);
$reservation = new Reservations($db);
$notification = new Notifications($db);

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's ID from the session
$userId = $_SESSION['id'];

// Fetch user details
$userDetails = $user->getUserDetails($userId);

// Fetch user's reservations
$reservations = $reservation->getUserReservations($userId);

// Fetch user's notifications
$notifications = $notification->getUserNotifications($userId);

// Logout action
if (isset($_POST['logout'])) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($userDetails['name']); ?>!</h1>

    <h2>Your Reservations</h2>
    <ul>
        <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <li><?php echo htmlspecialchars($reservation['reservation_details']); ?> (Expected Return: <?php echo htmlspecialchars($reservation['expected_return_date']); ?>)</li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No reservations found.</li>
        <?php endif; ?>
    </ul>

    <h2>Your Notifications</h2>
    <ul>
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <li><?php echo htmlspecialchars($notification['message']); ?> (Date: <?php echo htmlspecialchars($notification['date']); ?>)</li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No notifications found.</li>
        <?php endif; ?>
    </ul>

    <!-- Logout Form -->
    <form method="POST" action="">
        <button type="submit" name="logout">Logout</button>
    </form>

</body>
</html>

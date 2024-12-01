<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Redirect to login if the user is not logged in
    exit;
}

require_once 'Database/database.php'; // Database connection
require_once 'Database/crud.php';     // CRUD functionality

$database = new Database();
$db = $database->getConnect();

$query = "SELECT id, first_name, last_name, username, email, address, phone_number FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Error: User details not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="./CSS/myacc.css">
</head>
<body>
    <header class="header">
        <div class="logo"></div>
        <nav class="nav">
            <a href="homepage.php">Home</a>
            <a href="search_catalog.php">Search</a>
            <a href="#Services">Borrow History</a>
            <a href="myAccount.php">My Account</a>
        </nav>
    </header>

    <section id="myAccount">
        <div class="container">
            <h2>My Account Details</h2>
            <table class="account-table">
                <tr>
                    <th>Username:</th>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <th>First Name:</th>
                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                </tr>
                <tr>
                    <th>Last Name:</th>
                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number:</th>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                </tr>
            </table>
            <a href="logout.php" class="logout-btn">Log Out</a> <!-- Log Out Button -->
        </div>
    </section>
</body>
</html>
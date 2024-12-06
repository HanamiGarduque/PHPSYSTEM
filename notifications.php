<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="./css/notifications.css">

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                paging: false,
                searching: false,
                info: false
            });
        });
    </script>
</head>

<body>
    <header class="header">
        <div class="logo"></div>
        <div class="head">Blib: Library Management System</div>
        <nav class="nav">
            <a href="homepage.php">Home</a>
            <a href="search_catalog.php">Search a Book</a>
            <a href="notifications.php" style="color: #F7E135;">Notifications</a>
            <a href="myacc.php">My Account</a>
        </nav>
    </header>
    <h2>Notifications</h2>
    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $notification = new Notifications($db);
            $stmt = $notification->getUserNotifications($_SESSION['id']);
            $num = $stmt->rowCount();
            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (isset($row['notification_id'])) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                        echo "<td><a href='deleteNotifications.php?notification_id=" . htmlspecialchars($row['notification_id']) . "' class='button'>Delete</a></td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='2'>Error: notification_id not found.</td></tr>";
                    }
                }}
                
            ?>
        </tbody>
    </table>


</body>

</html>
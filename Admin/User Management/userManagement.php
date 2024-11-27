<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN: User Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#userTable').DataTable();
        });
    </script>
</head>
<body>
    <nav>
    <ul>
        <li><a href="userManagement.php">User Management</a></li>
        <li><a href="bookManagement.php">Book Management</a></li>
        <li><a href="reservationManagement.php">Reservation Management</a></li>
        <li><a href="loanManagement.php">Loan Management</a></li>
        <li><a href="adminAccount.php">Loan Management</a></li>
    </ul>
    </nav>
<h2> Users List </h2>
    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th> <!-- Optional actions for Delete/Edit -->
            </tr>
        </thead>

        <tbody>

            <?php
            require_once '../../Database/database.php';
            require_once '../../Database/crud.php';
            
            

            $database = new Database();
            $db = $database->getConnect();

            $user = new Users($db);
            $stmt = $user->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" . (isset($row['id']) ? htmlspecialchars($row['id']) : '') . "</td>";
                    echo "<td>" . (isset($row['username']) ? htmlspecialchars($row['username']) : '') . "</td>";
                    echo "<td>" . (isset($row['first_name']) ? htmlspecialchars($row['first_name']) : '') . "</td>";
                    echo "<td>" . (isset($row['last_name']) ? htmlspecialchars($row['last_name']) : '') . "</td>";
                    echo "<td>" . (isset($row['email']) ? htmlspecialchars($row['email']) : '') . "</td>";
                    echo "<td>" . (isset($row['address']) ? htmlspecialchars($row['address']) : '') . "</td>";
                    echo "<td>" . (isset($row['phone_number']) ? htmlspecialchars($row['phone_number']) : '') . "</td>";
                    echo "<td>" . (isset($row['roles']) ? htmlspecialchars($row['roles']) : '') . "</td>";                    
                    echo "<td>" . (isset($row['status']) ? htmlspecialchars($row['status']) : '') . "</td>";
                    echo "<td><a href='updateUser.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a></td>";
                    echo "</tr>";
                }
            }
            
            ?>
   
        </tbody>
    </table>

</body>
</html>

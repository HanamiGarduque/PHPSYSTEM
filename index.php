<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Library Management System</title>
    
</head>
<body>
<h2>Register</h2>
    <form method="POST" action="register.php">
        Username: <input type="text" name="username" required>
        <br><br>
        First Name: <input type="text" name="first_name" required>
        <br><br>
        Last Name: <input type="text" name="last_name" required>
        <br><br>
        Email: <input type="email" name="email" required>
        <br><br>
        Address: <input type="text" name="address" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        <input type="submit" value="Register">
    </form>


    <h2>Users List</h2>
    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>

            <?php
            require_once 'database.php';
            require_once 'crud.php';

            $database = new Database();
            $db = $database->getConnect();

            $user = new Users($db);
            $stmt = $user->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    var_dump($row); 
                    echo "<tr>";
                    echo "<td>" . (isset($row['Id']) ? htmlspecialchars($row['Id']) : '') . "</td>";
                    echo "<td>" . (isset($row['Username']) ? htmlspecialchars($row['Username']) : '') . "</td>";
                    echo "<td>" . (isset($row['First_Name']) ? htmlspecialchars($row['First_Name']) : '') . "</td>";
                    echo "<td>" . (isset($row['Last_Name']) ? htmlspecialchars($row['Last_Name']) : '') . "</td>";
                    echo "<td>" . (isset($row['Email']) ? htmlspecialchars($row['Email']) : '') . "</td>";
                    echo "<td>" . (isset($row['Address']) ? htmlspecialchars($row['Address']) : '') . "</td>";
                    echo "<td>" . (isset($row['Password']) ? htmlspecialchars($row['Password']) : '') . "</td>";
                    echo "<td>Delete/Edit</td>";  // Optional actions for Delete/Edit
                    echo "</tr>";
                }
            }

            ?>
   
        </tbody>
    </table>

</body>

</html>
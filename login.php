
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>
<body>

<h2>Login</h2>
<form method="POST" action="">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <input type="submit" value="Login">
</form>

<p>Don't have an account? <a href="registration.php">Register Now</a></p>
<?php
    require_once './Database/database.php';
    require_once './Database/crud.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];        

        
        $database = new Database();
        $db = $database->getConnect();

        $user = new Users($db);

        $query = "SELECT password FROM users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $inputUsername);
        $stmt->execute();
       
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];

           
            if (password_verify($inputPassword, $hashedPassword)) {
                
                header("Location: Home.php");
                exit();
            } else {
                echo 'Incorrect password.';
            }
        } else {
            echo 'Incorrect username.';
        }

        // Close statement
        $stmt->closeCursor();
    }
?>


</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

    </head>
<body>

<?php   
     require_once './Database/database.php';
     require_once './Database/crud.php';

     if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Initialize database connection
      $database = new Database();
      $db = $database->getConnect();
  
      // Initialize the Users object
      $user = new Users($db);
  
      // Sanitize and assign form data to the user object
      $user->username = htmlspecialchars(trim($_POST['username']));
      $user->first_name = htmlspecialchars(trim($_POST['first_name']));
      $user->last_name = htmlspecialchars(trim($_POST['last_name']));
      $user->email = htmlspecialchars(trim($_POST['email']));
      $user->address = htmlspecialchars(trim($_POST['address']));
      $user->password = htmlspecialchars(trim(password_hash($_POST['password'], PASSWORD_BCRYPT))); // Hash the password
  
      if ($user->checkDuplicateAcc()) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'duplicate';
    } else {
        if ($user->create()) {
            $_SESSION['status'] = 'success';
        } else {
            $_SESSION['status'] = 'error';
        }
    }
    // // Redirect to registration.php
    header("Location: registration.php");
    exit();
    }
?> 

</body>
</html>

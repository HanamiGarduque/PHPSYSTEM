<?php
     require_once 'database.php';
     require_once 'index.php';

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
  
      // Attempt to create the user
      if ($user->create()) { // add animation
          echo "User created successfully!";
      } else {
          echo "Error creating user.";
      }
  
    }
?>
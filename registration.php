<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Library Management System</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<h2>Register</h2>
    <form method="POST" action="registerDB.php">
        <fieldset>
            <legend>Register</legend>
        Username: <br> <input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
        <br>
        First Name: <br> <input type="text" name="first_name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>" required>
        <br>
        Last Name: <br> <input type="text" name="last_name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>" required>
        <br>
        Email: <br> <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
        <br>
        Address: <br> <input type="text" name="address" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" required>
        <br>
        Phone Number: <i>11 digits</i>  <br> <input type="text" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : ''; ?>" required>
        <br>
        Password: <i>minimum of 8 characters, at least 1 uppercase and a number</i> <br> 
        <input type="password" name="password" required><br>
        Confirm Password: <br> 
        <input type="password" name="confirm_password" required><br>
        <input type="submit" value="Register">
        </fieldset>
    </form>

    <p>Already have an account? <a href="login.php">Sign in.</a></p>

<?php
    // Check for session variables and trigger SweetAlert based on status
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'success') {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'User created successfully!',
                    icon: 'success',
                    confirmButtonText: 'Okay',
                    background: '#fff',
                    backdrop: true
                });
              </script>";
        // Clear session data after success
        session_unset();
        session_destroy();
    } elseif (isset($_SESSION['status']) && $_SESSION['status'] == 'error') {
        $message = (isset($_SESSION['message']) && $_SESSION['message'] == 'duplicate') 
                   ? 'Username or Email already exists!' 
                   : 'There was an error creating the user. Please review the entered information and try again';
        
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: '$message',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    background: '#fff',
                    backdrop: true
                });
              </script>";

        
        // Clear session data after showing error
        session_unset();
        session_destroy();
    }
?>
</body>
</html>

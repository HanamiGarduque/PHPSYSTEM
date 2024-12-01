<?php
// Start the session to handle status messages
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    // Check if a status is set in the session
    if (isset($_SESSION['status'])) {
        $status = $_SESSION['status'];
        $message = $_SESSION['message'];

        // If registration is successful, redirect to login.php
        if ($status === 'success') {
            echo "<script>
                Swal.fire({
                    icon: '$status',
                    title: 'Success',
                    text: '$message',
                    confirmButtonText: 'Go to Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php';
                    }
                });
            </script>";
        } else {
            // Display error message for other statuses
            echo "<script>
                Swal.fire({
                    icon: '$status',
                    title: 'Error',
                    text: '$message'
                });
            </script>";
        }

        // Clear session messages after displaying
        unset($_SESSION['status']);
        unset($_SESSION['message']);
    }
    ?>

    <h2>Register</h2>
    <form method="POST" action="registerDB.php">
        Username: <input type="text" name="username" required>
        <br><br>
        First Name: <input type="text" name="first_name" required>
        <br><br>
        Last Name: <input type="text" name="last_name" required>
        <br><br>
        Email: <input type="email" name="email" required>
        <br><br>
        Address: <textarea name="address" required></textarea>
        <br><br>
        Phone Number: <input type="text" name="phone_number" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        Confirm Password: <input type="password" name="confirm_password" required>
        <br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

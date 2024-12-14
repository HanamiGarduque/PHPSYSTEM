<?php
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

    <link rel="stylesheet" href="css/registration.css">
</head>

<body>
    <?php
    if (isset($_SESSION['status'])) {
        $status = $_SESSION['status'];
        $message = $_SESSION['message'];

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
            echo "<script>
                Swal.fire({
                    icon: '$status',
                    title: 'Error',
                    text: '$message'
                });
            </script>";
        }

        unset($_SESSION['status']);
        unset($_SESSION['message']);
    }
    ?>

<div class="container">
        <div class="left-side"></div>
        <div class="right-side">
        <div class="logo"></div>
            <h1>Register</h1>
            <p>Please complete the form to create your account and enjoy access to our collection.</p>
            <form method="POST" action="registerDB.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Sign Up</button>
            </form>
                <div class="signin">
                    Already have an account? <a href="login.php">Login</a>
                </div>
        </div>
    </div>

</body>

</html>
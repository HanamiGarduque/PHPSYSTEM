<?php
session_start();  // Keep this at the top
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- SweetAlert CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    $database = new Database();
    $db = $database->getConnect();

    $query = "SELECT id, first_name, last_name, password, roles, status FROM users WHERE username = :username LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $inputUsername);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $row['password'];
        $role = $row['roles'];
        $status = $row['status'];

        // Check account status
        if ($status === 'suspended') {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Account Suspended",
                    text: "Your account is suspended. Please contact the admin.",
                });
                </script>';
            exit;
        }

        // Verify password
        if (password_verify($inputPassword, $hashedPassword)) {
            // Store the user data in the session
            $_SESSION['id'] = $row['id'];  // Store the user ID in the session
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role === 'Admin') {
                header("Location: Admin/BookManagement/bookManagement.php");
            } else {
                header("Location: homepage.php");
            }
            exit;
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Invalid Credentials",
                    text: "Incorrect password. Please try again."
                });
                </script>';
        }
    } else {
        echo '<script>
        Swal.fire({
            icon: "warning",
            title: "No Account Found",
            text: "It looks like you don\'t have an account yet.",
            showCancelButton: true,
            confirmButtonText: "Register Now",
            cancelButtonText: "Close",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "registration.php";
            }
        });
        </script>';
    }
}
?>

</body>
</html>
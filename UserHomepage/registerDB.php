<?php
session_start();

require_once '../Database/database.php';
require_once '../Database/crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Initialize database connection
$database = new Database();
$db = $database->getConnect();
// Initialize the Users object
$user = new Users($db);


//sanitize form data
$username = htmlspecialchars(trim($_POST['username']));
$first_name = htmlspecialchars(trim($_POST['first_name']));
$last_name = htmlspecialchars(trim($_POST['last_name']));
$email = htmlspecialchars(trim($_POST['email']));
$address = htmlspecialchars(trim($_POST['address']));
$phone_number = htmlspecialchars(trim($_POST['phone_number']));
$password = htmlspecialchars(trim($_POST['password']));
$confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
    


// Check if the password and confirm password match
if ($password !== $confirm_password) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Passwords do not match. Please try again.';
    header("Location: registration.php");
    exit();
}
    
//checks if there are empty fields
if (empty($username) || empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($phone_number) || empty($password)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'All fields are required.';
    header("Location: registration.php");
    exit();
}
//checks if email format is correct
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid email format.';
    header("Location: registration.php");
    exit();
}
//checks password input
if (!preg_match("/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Password must be at least 8 characters long, contain at least one uppercase letter and one number.';
    header("Location: registration.php");
    exit();
}
//checks if the phone number entered contains 11 digits
if (!preg_match("/^\d{11}$/", $phone_number)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Phone number must be 11 digits.';
    header("Location: registration.php");
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$user->password = $hashed_password;
$user->username = $username;
$user->first_name = $first_name;
$user->last_name = $last_name;
$user->email = $email;
$user->address = $address;
$user->phone_number = $phone_number;


if ($user->checkDuplicateAcc()) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'duplicate';
} else {
    if ($user->create()) {
        $_SESSION['status'] = 'success';
        unset($_SESSION['form_data']);
    } else {
        $_SESSION['status'] = 'error';
    }
}


header("Location: registration.php");
exit();
}
?>  


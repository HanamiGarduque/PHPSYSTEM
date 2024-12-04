<?php
require_once './Database/database.php';
require_once './Database/crud.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $database = new Database();
    $db = $database->getConnect();

    $user = new Users($db);

    $username = htmlspecialchars(trim($_POST['username']));
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Validation: Check for empty fields
    if (empty($username) || empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($phone_number) || empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'All fields are required.';
        header("Location: registration.php");
        exit();
    }

    // Validation: Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid email format.';
        header("Location: registration.php");
        exit();
    }

    // Validation: Check password strength
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Password must be at least 8 characters long, contain at least one uppercase letter and one number.';
        header("Location: registration.php");
        exit();
    }

    // Validation: Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Passwords do not match.';
        header("Location: registration.php");
        exit();
    }

    // Validation: Check phone number format
    if (!preg_match("/^\d{11}$/", $phone_number)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Phone number must be 11 digits.';
        header("Location: registration.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $user->username = $username;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->email = $email;
    $user->address = $address;
    $user->phone_number = $phone_number;
    $user->password = $hashed_password;

    // Check for duplicate accounts
    if ($user->checkDuplicateAcc()) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Username or email already exists.';
    } else {
        // Create a new user
        if ($user->create()) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Your account has been created successfully.';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'There was an error creating your account. Please try again.';
        }
    }

    // Redirect back to the registration page
    header("Location: registration.php");
    exit();
}
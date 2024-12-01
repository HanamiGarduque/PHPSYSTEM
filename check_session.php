<?php
// auth_check.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['id']);
}

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>
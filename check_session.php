<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

function ensureAdminAccess() {
    if (!isAdmin()) { 
        header('Location: /PHPSYSTEM/homepage.php');
        exit();
    }
}
?>

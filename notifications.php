<?php

require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();
$user = new Users($db);
$notification = new Notifications($db);
// Function to format and display the notification message

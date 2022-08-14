<?php
require 'config/config.php';

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
} else {
    header("Location: register.php");
}
?>
<html>
    <head>
        <title>Social Media</title>
    </head>
    <body>
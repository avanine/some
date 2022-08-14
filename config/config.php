<?php
ob_start(); //turns on output buffering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$timezone = date_default_timezone_set("Europe/Helsinki");

$con = mysqli_connect("localhost", "root", "", "social"); //connection variable

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}

?>
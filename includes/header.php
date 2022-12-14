<?php
require 'config/config.php';

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("Location: register.php");
}
?>
<html>
    <head>
        <title>Social Media</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    </head>
    <body>

        <div class="top_bar">
            <div class="logo">
                <a href="index.php">SoMe</a>
            </div>

            <nav>
                <a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name']; ?></a>
                <a href="#"><i class="fa-lg fa-solid fa-house"></i></a>
                <a href="#"><i class="fa-lg fa-solid fa-envelope"></i></a>
                <a href="#"><i class="fa-lg fa-solid fa-bell"></i></a>
                <a href="requests.php"><i class="fa-lg fa-solid fa-users"></i></a>
                <a href="#"><i class="fa-lg fa-solid fa-gear"></i></a>
                <a href="includes/handlers/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </nav>
        </div>

        <div class="wrapper">
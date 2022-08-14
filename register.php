<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$con = mysqli_connect("localhost", "root", "", "social");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}

//declaring variables to prevent errors
$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = ""; //sign up date
$error_array = array(); //holds error messages

if (isset($_POST['register_button'])) {
    //registration form values
    $fname = strip_tags($_POST['reg_fname']); //remove html tags
    $fname = str_replace(' ', '', $fname); //remove spaces
    $fname = ucfirst(strtolower($fname)); //uppercase first letter
    $_SESSION['reg_fname'] = $fname; //stores first name into session variable

    $lname = strip_tags($_POST['reg_lname']); //remove html tags
    $lname = str_replace(' ', '', $lname); //remove spaces
    $lname = ucfirst(strtolower($lname)); //uppercase first letter
    $_SESSION['reg_lname'] = $lname; //stores last name into session variable

    $em = strip_tags($_POST['reg_email']); //remove html tags
    $em = str_replace(' ', '', $em); //remove spaces
    $em = ucfirst(strtolower($em)); //uppercase first letter
    $_SESSION['reg_email'] = $em; //stores email into session variable

    $em2 = strip_tags($_POST['reg_email2']); //remove html tags
    $em2 = str_replace(' ', '', $em2); //remove spaces
    $em2 = ucfirst(strtolower($em2)); //uppercase first letter
    $_SESSION['reg_email2'] = $em2; //stores email2 into session variable

    $password = strip_tags($_POST['reg_password']); //remove html tags
    $password2 = strip_tags($_POST['reg_password2']); //remove html tags

    $date = date("Y-m-d");

    if ($em == $em2) {
        //check if email is in valid format
        if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if ($num_rows > 0) {
                array_push($error_array, "Email already in use<br>");
            }


        } else {
            array_push($error_array, "Invalid email format<br>");
        }
    } else {
        array_push($error_array, "Emails don't match<br>");
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }
    if (strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }
    if ($password != $password2) {
        array_push($error_array, "Your passwords do not match<br>");
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }
    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }
    if (empty($error_array)) {
        $password = md5($password); //encrypt password before sending to database

        //generate username by concatenating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        //if username exists, add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . " " . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }

        //assign profile picture
        $rand = rand(1, 2);

        if ($rand == 1) {
            $profile_pic = "assets/images/profile_pics/defaults/pink-default.jpeg";
        } else {
            $profile_pic = "assets/images/profile_pics/defaults/black-default.jpeg";
        }

        $query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: #14C800'>You're all set! Go ahead and log in</span><br>");

        //clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}

?>

<html>
    <head>
        <title>Welcome</title>
    </head>
    <body>

        <form action="register.php" method="POST">

            <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
            if (isset($_SESSION['reg_fname'])) {
                echo $_SESSION['reg_fname'];
            } ?>" required>
            <br>

            <?php if (in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>

            <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
            if (isset($_SESSION['reg_lname'])) {
                echo $_SESSION['reg_lname'];
            } ?>" required>
            <br>

            <?php if (in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

            <input type="email" name="reg_email" placeholder="Email" value="<?php 
            if (isset($_SESSION['reg_email'])) {
                echo $_SESSION['reg_email'];
            } ?>" required>
            <br>

            <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
            if (isset($_SESSION['reg_email2'])) {
                echo $_SESSION['reg_email2'];
            } ?>" required>
            <br>

            <?php if (in_array("Email already in use<br>", $error_array)) {
                echo "Email already in use<br>";
            } else if (in_array("Invalid email format<br>", $error_array)) {
                echo "Invalid email format<br>";
            } else if (in_array("Emails don't match<br>", $error_array)) {
                echo "Emails don't match<br>";
             } ?>

            <input type="password" name="reg_password" placeholder="Password" required>
            <br>
            <input type="password" name="reg_password2" placeholder="Confirm Password" required>
            <br>

            <?php if (in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>";
            else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
            else if (in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>"; ?>

            <input type="submit" name="register_button" value="Register" />
             <br>
            <?php if (in_array("<span style='color: #14C800'>You're all set! Go ahead and log in</span><br>", $error_array)) echo "<span style='color: #14C800'>You're all set! Go ahead and log in</span><br>"; ?>
        </form>

    </body>
</html>
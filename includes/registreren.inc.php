<?php
//start session
session_start();

//load config file
require "config.inc.php";

//vars
$error = 0;
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$name = $_POST['name'];
$name = htmlspecialchars($name);
$name = stripslashes($name);
$name = trim($name);
$name = strip_tags($name);
$name = htmlentities($name);
$email = $_POST['email'];
$email = htmlspecialchars($email);
$email = stripslashes($email);
$email = trim($email);
$email = strip_tags($email);
$email = htmlentities($email);
$pswUnencrypted = $_POST['psw'];
$psw = password_hash($pswUnencrypted, PASSWORD_BCRYPT, array("cost" => 12));
$captchaInput = $_POST['captcha'];
$captchaAnswer = $_SESSION['captcha'];

//check if csrf token is valid
if ($csrfTokenInput != $csrfTokenSession) {
    $error++;
}

//check if all fields are filled
if (empty($name) OR empty($email) OR empty($psw)) {
    $error++;
}

//check if email input has correct pattern
if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
    $error++;
}

//check if captcha is entered correct
if ($captchaInput != $captchaAnswer) {
    $error++;
}

//if there are no errors add data to db and send verification email otherwise show error
if ($error == 0) {
    //execute query and fetch result to check if email exists
    $resultEmail = mysqli_query($mysqli, "SELECT COUNT(Email) AS num FROM Users WHERE Email = '$email'");
    $rowEmail = mysqli_fetch_array($resultEmail);

    //if email not exists add data to db and send verification email otherwise show error
    if ($rowEmail['num'] == 0) {
        $result = mysqli_query($mysqli, "INSERT INTO Users (User_ID, Naam, Wachtwoord, Email, verified, verificationToken, residence, postalcode, address, bank, paymentMethod) VALUES (NULL, '$name', '$psw', '$email', 1, '', '', '', '', '', '')");
        
        //if de query is succesful executed send email to user for email verification and redirect
        if ($result) {
            header("location: ../registreren.php?status=success");
        } else {
            header("location: ../registreren.php?status=failed");
        }
    } else {
        header("location: ../registreren.php?status=failed");
    }
} else {
    header("location: ../registreren.php?status=failed");
}
<?php
//start session
session_start();

//load config file
require "config.inc.php";

//vars
$error = 0;
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$email = $_POST['email'];
$email = htmlspecialchars($email);
$email = stripslashes($email);
$email = trim($email);
$email = strip_tags($email);
$email = htmlentities($email);
$psw = $_POST['psw'];

//check if csrf token is valid
if ($csrfTokenInput != $csrfTokenSession) {
    $error++;
}

//check if all fields are filled
if (empty($email) OR empty($psw)) {
    $error++;
}

//check if email input has correct pattern.
if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
    $error++;
}

//if there are no errors verify password
if ($error == 0) {
    $result = mysqli_query($mysqli, "SELECT * FROM `Users` WHERE Email = '$email'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        $validPsw = password_verify($psw, $row['Wachtwoord']);

        if ($validPsw) {
            $_SESSION['userId'] = $row['User_ID'];
            header("location: ../winkel.php");
        } else {
            header("location: ../inloggen.php?status=failed");
        }
    } else {
        header("location: ../inloggen.php?status=failed");
    }
} else {
    header("location: ../inloggen.php?status=failed");
}
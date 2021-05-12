<?php
if (!isset($_SESSION['userId'])) {
    header('location: ../inloggen.php');
}
//load config file
require "config.inc.php";
    
//vars
$error = 0;
$orderId = $_GET['orderId'];
$orderId = strip_tags($orderId);
$orderId = htmlspecialchars($orderId);
$orderId = stripslashes($orderId);
$orderId = trim($orderId);
$orderId = htmlentities($orderId);
$userId = $_SESSION['userId'];

//check if pattern of user id is correct
$orderIdPattern = "/[0-9]*/";
if (!preg_match($orderIdPattern, $orderId)) {
    $error++;
}

//run query
$resultOrderVerify = mysqli_query($mysqli, "SELECT * FROM `Bestelling` WHERE 1=1 AND Bestellings_ID = '$orderId' AND User_ID = '$userId'");

//check if the order is from the logged in user
if (mysqli_num_rows($resultOrderVerify) == 0) {
    header("location: ../{$redirectFrom}.php?statusPayment=failed");
}

if ($error == 0) {
    $result = mysqli_query($mysqli, "UPDATE `Bestelling` SET status = 7 WHERE bestellings_ID = '$orderId'");

    if ($result) {
        header("location: ../dashboard.php?cancel=successful");
    }
} else {
    header("location: ../dashboard.php?cancel=failed");
}
<?php
//start session
session_start();

//load config file
require "config.inc.php";

//check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("location: ../inloggen.php");
}

//vars
$error = 0;
$userId = $_SESSION['userId'];
$orderId = $_GET['orderId'];
$orderId = htmlspecialchars($orderId);
$orderId = stripslashes($orderId);
$orderId = trim($orderId);
$orderId = strip_tags($orderId);
$orderId = htmlentities($orderId);
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$residence = $_POST['residence'];
$residence = htmlspecialchars($residence);
$residence = stripslashes($residence);
$residence = trim($residence);
$residence = strip_tags($residence);
$residence = htmlentities($residence);
$postalCode = $_POST['postalCode'];
$postalCode = htmlspecialchars($postalCode);
$postalCode = stripslashes($postalCode);
$postalCode = trim($postalCode);
$postalCode = strip_tags($postalCode);
$postalCode = htmlentities($postalCode);
$address = $_POST['address'];
$address = htmlspecialchars($address);
$address = stripslashes($address);
$address = trim($address);
$address = strip_tags($address);
$address = htmlentities($address);
$bank = $_POST['bank'];
$bank = htmlspecialchars($bank);
$bank = stripslashes($bank);
$bank = trim($bank);
$bank = strip_tags($bank);
$bank = htmlentities($bank);
$paymentMethod = $_POST['paymentMethod'];
$paymentMethod = htmlspecialchars($paymentMethod);
$paymentMethod = stripslashes($paymentMethod);
$paymentMethod = trim($paymentMethod);
$paymentMethod = strip_tags($paymentMethod);
$paymentMethod = htmlentities($paymentMethod);

//check if csrf token is valid
if ($csrfTokenInput != $csrfTokenSession) {
    $error++;
}

//check if pattern of all fields is correct
$orderIdPattern = "/[0-9]*/";
if (!preg_match($orderIdPattern, $orderId)) {
    $error++;
}

$postalCode = str_replace(" ", "", $postalCode);
$postalCodePattern = "/[0-9]{4}[a-zA-Z]{2}/";
if (!preg_match($postalCodePattern, $postalCode)) {
    $error++;
}

if ($bank == "") {
    $error++;
}

if ($paymentMethod == "") {
    $error++;
}

//run query
$resultOrderVerify = mysqli_query($mysqli, "SELECT * FROM `Bestelling` WHERE 1=1 AND Bestellings_ID = '$orderId' AND User_ID = '$userId'");

//check if the order is from the logged in user
if (mysqli_num_rows($resultOrderVerify) == 0) {
    header("location: ../{$redirectFrom}.php?statusPayment=failed");
}

if ($error == 0) {
    //query
    $resultUser = mysqli_query($mysqli, "UPDATE `Users` SET residence = '$residence', postalcode = '$postalCode', address = '$address', bank = '$bank', paymentMethod = '$paymentMethod' WHERE User_ID = '$userId'");
    
    //execute query and if successful send mail, if not successful show error
    if ($resultUser) {
        $resultOrder = mysqli_query($mysqli, "UPDATE `Bestelling` SET status = 1 WHERE User_ID = '$userId'");
        
        if ($resultOrder) {
            $resultUsers = mysqli_query($mysqli, "SELECT * FROM `Users` WHERE User_ID = '$userId'");
            $rowUsers = mysqli_fetch_array($resultUsers);
            $email = $rowUsers['Email'];

            $subject = "Bedankt voor je bestelling bij de Snoepkoning";
            $headers = "From: noReply@snoepkoning.nl";
            $headers = "MIME-Version: 1.0\r\n";
            $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $body = "
            <!docytype html>
            <html>
                <body width='600px'>
                    <h1>Bedankt voor je bestelling bij de Snoepkoning!</h1>
                    <p>Als de bestelling verzonden is ontvangt u van ons de track & trace code van de bezorg dienst.</p>
                    <p>Via de link hieronder kun je je bestelling volgen.</p>
                    <a href='https://rubenderuijter.nl/portfolioItem?uuid=3aa1aaed-0a0c-4681-a1f65329d29f&redirectTo=dashboard.php'>Volg mijn bestelling</a><br /><br />
                    <p>Deze e-mail is automatisch verzonden, antwoorden op deze e-mail worden niet beantwoord.</p>
                </body>
            </html>
            ";
            mail($email, $subject, $body, $headers);

            header("location: ../winkel.php?statusPayment=success");
        } else {
            header("location: ../winkel.php?statusPayment=failed");
        }
    } else {
        header("location: ../winkel.php?statusPayment=failed");
    }
} else {
    header("location: ../winkel.php?statusPayment=failed");
}
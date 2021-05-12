<?php
require 'config.inc.php';

//start session
session_start();

//haal de user-id uit de url
$error = 0;
$userIdSession = $_SESSION['userId'];
$User_ID = $_GET['userId'];
$User_ID = strip_tags($User_ID);
$User_ID = htmlspecialchars($User_ID);
$User_ID = stripslashes($User_ID);
$User_ID = trim($User_ID);
$User_ID = htmlentities($User_ID);

//check if pattern of user id is correct
$User_IDPattern = "/[0-9]*/";
if (!preg_match($User_IDPattern, $User_ID)) {
    $error++;
}

if ($User_ID != $userIdSession) {
    header("location: ../dashboard.php");
}

//maak de query
$query = "DELETE FROM `Custom` WHERE userId = '$User_ID'";

//vang het resultaat op
$resultaat = mysqli_query($mysqli, $query);
if ($resultaat && $error == 0) {
    $query2 = "DELETE FROM `Bestelling` WHERE User_ID = '$User_ID'";

    $resultaat2 = mysqli_query($mysqli, $query2);

    if ($resultaat2) {
        $query3 = "DELETE FROM `Users` WHERE User_ID = '$User_ID'";

        $resultaat3 = mysqli_query($mysqli, $query3);

        if ($resultaat3) {
            //unset and destroy session
            session_unset();
            session_destroy();

            //redirect user
            header("location: ../index.php");
        } else {
            echo "Account is niet verwijderd";
        }
    
    } else {
        echo "Account is niet verwijderd";
    }
} else {
    echo "Account is niet verwijderd";
}
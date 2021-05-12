<?php
//start session
session_start();

if (isset($_SESSION['userId'])) {
    //unset and destroy session
    session_unset();
    session_destroy();
    
    //redirect user
    header("location: ../index.php");
}
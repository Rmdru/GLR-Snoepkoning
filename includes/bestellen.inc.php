<?php
//start session
session_start();

if (isset($_SESSION['userId'])) {
    //load config file
    require "config.inc.php";

    //vars
    $error = 0;
    $redirectFrom = $_GET['redirectFrom'];
    $redirectFrom = htmlspecialchars($redirectFrom);
    $redirectFrom = stripslashes($redirectFrom);
    $redirectFrom = trim($redirectFrom);
    $redirectFrom = strip_tags($redirectFrom);
    $redirectFrom = htmlentities($redirectFrom);
    $productId = $_GET['productId'];
    $productId = htmlspecialchars($productId);
    $productId = stripslashes($productId);
    $productId = trim($productId);
    $productId = strip_tags($productId);
    $productId = htmlentities($productId);
    $amount = $_POST['amount'];
    $amount = htmlspecialchars($amount);
    $amount = stripslashes($amount);
    $amount = trim($amount);
    $amount = strip_tags($amount);
    $amount = htmlentities($amount);
    $color = $_POST['color'];
    $color = htmlspecialchars($color);
    $color = stripslashes($color);
    $color = trim($color);
    $color = strip_tags($color);
    $color = htmlentities($color);
    $shape = $_POST['shape'];
    $shape = htmlspecialchars($shape);
    $shape = stripslashes($shape);
    $shape = trim($shape);
    $shape = strip_tags($shape);
    $shape = htmlentities($shape);
    $packaging = $_POST['packaging'];
    $packaging = htmlspecialchars($packaging);
    $packaging = stripslashes($packaging);
    $packaging = trim($packaging);
    $packaging = strip_tags($packaging);
    $packaging = htmlentities($packaging);
    $taste = $_POST['taste'];
    $taste = htmlspecialchars($taste);
    $taste = stripslashes($taste);
    $taste = trim($taste);
    $taste = strip_tags($taste);
    $taste = htmlentities($taste);
    $logo = $_FILES['logo'];
    $errorLogo = $logo['error'];
    $filetype = $logo['type'];
    $destinationFolder = __DIR__ . "/../logos/";
    $tmpLocation = $logo['tmp_name'];
    $discountCodeInput = $_POST['discountCode'];
    $discountCodeInput = htmlspecialchars($discountCodeInput);
    $discountCodeInput = stripslashes($discountCodeInput);
    $discountCodeInput = trim($discountCodeInput);
    $discountCodeInput = strip_tags($discountCodeInput);
    $discountCodeInput = htmlentities($discountCodeInput);
    $userId = $_SESSION['userId'];
    $dateOrderPlaced = date('Y-m-d');
    $csrfTokenInput = $_POST['csrfToken'];
    $csrfTokenSession = $_SESSION['csrfToken'];

    //check if csrf token is valid
    if ($csrfTokenInput != $csrfTokenSession) {
        $error++;
    }

    //check if pattern of all fields is correct
    $productIdPattern = "/[0-9]*/";
    if (!preg_match($productIdPattern, $productId)) {
        $error++;
    }

    $amountPattern = "/[0-9]*/";
    if (!preg_match($amountPattern, $amount)) {
        $error++;
    }

    if ($error == 0) {
        //query
        $resultProduct = mysqli_query($mysqli, "SELECT * FROM `Product` WHERE 1=1 AND Product_ID = '$productId'");
        $rowProduct = mysqli_fetch_array($resultProduct);
        //vars
        $price = $rowProduct['Prijs'];
        $discountRate = $rowProduct['discountRate'];
        $discountCode = $rowProduct['discountCode'];

        //calculate price
        $price = $price * $amount;

        if (is_uploaded_file($tmpLocation)) {
            $price = $price + 5;
        }
        
        if ($discountRate > 0 AND $discountCodeInput == $discountCode) {
            $oldPrice = $price;
            $discountPriceDecimal = $discountRate / 100;
            $discountPriceDecimal = 1 - $discountPriceDecimal;
            $price = $discountPriceDecimal * $oldPrice;
            $discountRateInputCorrect = $discountRate;
        } else {
            $discountRateInputCorrect = 0;
        }
        
        //run first query
        $resultCustom = mysqli_query($mysqli, "INSERT INTO Custom (userId, Custom_ID, Custom_Kleur, Custom_Vorm, Custom_Pakking, Custom_Smaak) VALUES ('$userId', NULL, '$color', '$shape', '$packaging', '$taste')");
        $customId = mysqli_insert_id($mysqli);

        //if the first query was succesful run the second one
        if ($resultCustom) {
            $resultOrder = mysqli_query($mysqli, "INSERT INTO Bestelling (Bestellings_ID, User_ID, Product_ID, Custom_ID, amount, Logo, Prijs, discountRate, dateOrderPlaced, status) VALUES (NULL, '$userId', '$productId', '$customId', '$amount', '', '$price', '$discountRateInputCorrect', '$dateOrderPlaced', 0)");
            $orderId = mysqli_insert_id($mysqli);
            //create filename for the logo
            if (is_uploaded_file($tmpLocation)) {
                $fileName = "{$orderId}.jpg";
            } else {
                $fileName = "";
            }
            //run the third query
            if ($resultOrder) {
                $resultOrderLogo = mysqli_query($mysqli, "UPDATE `Bestelling` SET Logo = '$fileName' WHERE Bestellings_ID = '$orderId'");

                //if the third query ran succesful, redirect the user otherwise show a error
                if ($resultOrderLogo) {
                    if (is_uploaded_file($tmpLocation)) {
                        if ($filetype == "image/jpg" OR $filetype == "image/jpeg" OR $filetype == "image/pjpeg" OR $filetype == "image/pjp" OR $filetype == "image/jfif") {
                            move_uploaded_file($tmpLocation, $destinationFolder.$fileName);
                        }
                    }                    

                    header("location: ../betalen.php?redirectFrom={$redirectFrom}&orderId={$orderId}");
                } else {
                    header("location: ../winkel.php?status=failed");
                }
            } else {
                header("location: ../winkel.php?status=failed");
            }
        } else {
            header("location: ../winkel.php?status=failed");
        }
    } else {
        header("location: ../winkel.php?status=failed");
    }
} else {
    header("location: ../inloggen.php");
}
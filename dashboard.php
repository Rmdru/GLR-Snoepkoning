<?php
//start session
session_start();

//redirect if user is not logged in
if (!isset($_SESSION['userId'])) {
    header("location: inloggen.php");
}
?>
<!doctype html>
<html>
<!--head-->
<head>
    <meta charset="utf-8" />
    <title>Account dashboard - Snoepkoning</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>

</head>
<body class="text-center">
     <nav class="navbar navbar-expand-md navbar-ligth bg-warning sticky-top">
    <div class="container-fluid">
    <img src="img/logo.png" style="width:100px; height:100px;">
    <h1 class="display-4">De Snoep Koning</h1>
    <div class="cikkaose navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="navbar-item mr-3 ml-3 mb-1">
                <a href="index.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Home
                </a>
            </li>
            <li class="navbar-item mr-3 ml-3 mb-1">
                <a href="winkel.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Winkel
                </a>
            </li>
        </ul>

    </div>
</div>
</nav>
    <?php
    //load config file
    require "includes/config.inc.php";
    
    //vars
    $userId = $_SESSION['userId'];

    //query to get the name of the user
    $resultUsers = mysqli_query($mysqli, "SELECT * FROM `Users` WHERE 1=1 AND User_ID = '$userId'");
    $rowUsers = mysqli_fetch_array($resultUsers);
    $name = $rowUsers['Naam'];
    $email = $rowUsers['Email'];
    $residence = $rowUsers['residence'];
    $postalCode = $rowUsers['postalcode'];
    $address = $rowUsers['address'];
    $bank = $rowUsers['bank'];
    $paymentMethod = $rowUsers['paymentMethod'];

    $currentTime = date("H");

	if ($currentTime >= 0 && $currentTime < 12) {
		echo "<h1>Goedemorgen {$name}</h1>";
	} else if ($currentTime >= 12 && $currentTime <= 17) {
		echo "<h1>Goedemiddag {$name}</h1>";
	} else if ($currentTime >= 18 && $currentTime <= 24) {
		echo "<h1>Goedenavond {$name}</h1>";
    }

    echo "<a href='includes/uitloggen.inc.php'>Uitloggen</a>";

    echo "<h3>Bestellingen:</h3>";
    
    if ($_GET['cancel'] == "successful") {
        echo "Bestelling succesvol geannuleerd";
    } else if ($_GET['cancel'] == "failed") {
        echo "Bestelling annuleren mislukt, probeer het opnieuw";
    } else if ($_GET['statusPayment'] == "failed") {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Bestelling afronden mislukt. Probeer de bestelling opnieuw te plaatsen.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    }
    
    //query
    $result = mysqli_query($mysqli, "SELECT * FROM `Bestelling` INNER JOIN `Custom` ON Bestelling.Custom_ID = Custom.Custom_ID AND Bestelling.User_ID = '$userId' ORDER BY dateOrderPlaced DESC");

    //if the user has orders show them in a table, otherwise show a msg that there are no orders found
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
            echo "<tr>";
                echo "<th>Ordernummer</th>";
                echo "<th>Product</th>";
                echo "<th>Aantal</th>";
                echo "<th>Logo</th>";
                echo "<th>Totaal prijs</th>";
                echo "<th>Datum bestelling geplaatst</th>";
                echo "<th>Bezorgstatus</th>";
                echo "<th>Kleur</th>";
                echo "<th>Vorm</th>";
                echo "<th>Verpakking</th>";
                echo "<th>Smaak</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                    $orderId = $row['Bestellings_ID'];
                    echo "<td>{$orderId}</td>";
                    $productId = $row['Product_ID'];
                    echo "<td>";
                    $resultProductName = mysqli_query($mysqli, "SELECT * FROM `Product` WHERE Product_ID = '$productId'");
                    $rowProductName = mysqli_fetch_array($resultProductName);
                    $productName = $rowProductName['Product_Naam'];
                    echo $productName;
                    echo "</td>";
                    $amount = $row['amount'];
                    echo "<td>{$amount}</td>";
                    $logo = $row['Logo'];
                    echo "<td>";
                    if (!empty($logo)) {
                        echo "<a rel='noopener' target='_blank' href='logos/{$logo}'>Bekijk het logo</a>";
                    } else {
                        echo "Geen logo";
                    }
                    echo "</td>";
                    $price = $row['Prijs'];
                    echo "<td>€{$price}</td>";
                    $dateOrderPlaced = $row['dateOrderPlaced'];
                    echo "<td>{$dateOrderPlaced}</td>";-
                    $status = $row['status'];
                    echo "<td>";
                    if ($status == 0) {
                        echo "In afwachting van betaling<br /><a href='betalen.php?redirectFrom=dashboard&orderId={$orderId}'>Bestelling afronden</a><br /><a href='includes/cancelOrder.inc.php?orderId={$orderId}'>Bestelling annuleren</a>";
                    } else if ($status == 1) {
                        echo "In behandeling<br /><a href='includes/cancelOrder.inc.php?orderId={$orderId}'>Bestelling annuleren</a>";
                    } else if ($status == 2) {
                        echo "Aangemeld bij bezorgdienst";
                    } else if ($status == 3) {
                        echo "Onderweg naar het depot";
                    } else if ($status == 4) {
                        echo "In het depot";
                    } else if ($status == 5) {
                        echo "Bezorger is onderweg";
                    } else if ($status == 6) {
                        echo "Bezorgd";
                    } else if ($status == 7) {
                        echo "Geannuleerd";
                    }
                    echo "</td>";
                    $color = $row['Custom_Kleur'];
                    echo "<td>{$color}</td>";
                    $shape = $row['Custom_Vorm'];
                    echo "<td>{$shape}</td>";
                    $packaging = $row['Custom_Pakking'];
                    echo "<td>{$packaging}</td>";
                    $taste = $row['Custom_Smaak'];
                    echo "<td>{$taste}</td>";
                echo "</tr>";
            }
        echo "</table><br /><br />";
    } else {
        echo "Er zijn nog geen bestellingen geplaatst<br />";
    }
    echo "<h3>Account gegevens:</h3>";
    echo "Naam: {$name}<br />";
    echo "E-mailadres: {$email}<br />";
    echo "Woonplaats: {$residence}<br />";
    echo "Postcode: {$postalCode}<br />";
    echo "Straat en huisnummer: {$address}<br />";
    echo "Bank: {$bank}<br />";
    echo "Betaalmethode: {$paymentMethod}<br />";
    echo "<h3>Account verwijderen:</h3>";
    echo "<a href='includes/deleteData.inc.php?userId={$userId}'>Ja, ik weet zeker dat ik mijn account en alle bijbehorende gegevens wil verwijderen</a>";
    ?>
</body>
</html>
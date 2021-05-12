<?php
//start session
session_start();

//load config file
require "includes/config.inc.php";

//check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("location: inloggen.php");
}

//vars
$error = 0;
$orderId = $_GET['orderId'];
$orderId = htmlspecialchars($orderId);
$orderId = stripslashes($orderId);
$orderId = trim($orderId);
$orderId = strip_tags($orderId);
$orderId = htmlentities($orderId);
$userId = $_SESSION['userId'];
$redirectFrom = $_GET['redirectFrom'];

//check if pattern of all fields is correct
$orderIdPattern = "/[0-9]*/";
if (!preg_match($orderIdPattern, $orderId)) {
    $error++;
}

//run query
$resultOrder = mysqli_query($mysqli, "SELECT * FROM `Bestelling` WHERE 1=1 AND Bestellings_ID = '$orderId' AND User_ID = '$userId'");

//check if the order is from the logged in user
if (mysqli_num_rows($resultOrder) == 0) {
    header("location: {$redirectFrom}.php?statusPayment=failed");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bestelling afronden - Snoepkoning</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<body>
<!-- Navigation -->
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
			</i>
            <li class="navbar-item mr-3 ml-3 mb-1">
				<a href="winkel.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
					Winkel
				</a>
			</i>
			<li class="navbar-item mr-3 ml-3 mb-1">
				<a href="dashboard.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
					Account dashboard
				</a>
			</i>
			<li class="navbar-item mr-3 ml-3 mb-1">
				<a href="includes/uitloggen.inc.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
					Uitloggen
				</a>
			</li>
		</ul>
	</div>
</div>
</nav>
<div class="container my-3">
    <h1 class="text-center">Bestelling afronden door te betalen</h1><br />
    <div class="table-responsive-sm">
        <h3 class="text-center">Product(en)</h3><br />
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Subtotaal</th>
                </tr>
            </thead>
            <?php
            //create table with order
            while ($rowOrder = mysqli_fetch_array($resultOrder)) {
                //show product in table
                echo "<tr>";
                    $productId = $rowOrder['Product_ID'];
                    $resultProduct = mysqli_query($mysqli, "SELECT * FROM `Product` WHERE Product_ID = '$productId'");
                    $rowProduct = mysqli_fetch_array($resultProduct);
                    $productName = $rowProduct['Product_Naam'];
                    echo "<td>{$productName}</td>";
                    $amount = $rowOrder['amount'];
                    echo "<td>{$amount}</td>";
                    $price = $rowProduct['Prijs'];
                    $price = $price * $amount;
                    echo "<td>€{$price}</td>";
                echo "</tr>";
                //show logo in table if added
                $logo = $rowOrder['Logo'];
                if ($logo != "") {
                    echo "<tr>";
                        echo "<td>Logo</td>";
                        echo "<td>1</td>";
                        $price = $price + 5;
                        echo "<td>€5</td>";
                    echo "</tr>";
                }
                //calculate total price
                echo "<tr>";
                    echo "<td><b>Totaal</b></td>";
                    echo "<td></td>";
                    $discountRate = $rowOrder['discountRate'];
                    //calculate discount if user has entered discount code and product has discount
                    if ($discountRate > 0) {                        
                        $oldPrice = $price;
                        $discountPriceDecimal = $discountRate / 100;
                        $discountPriceDecimal = 1 - $discountPriceDecimal;
                        $price = $discountPriceDecimal * $oldPrice;
                        $price = str_replace(".", ",", $price);
                        echo "<td>€{$price} (met {$discountRate}% korting)</td>";
                    } else {
                        $price = str_replace(".", ",", $price);
                        echo "<td>€{$price}</td>";
                    }
                echo "</tr>";
            }
            ?>
        </table>
        <h3 class="text-center">Adres & betaalgegevens</h3><br />
        <form action="includes/betalen.inc.php?orderId=<?php echo $orderId; ?>" method="POST" onsubmit="return startPayment();">
            <?php
            $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;

            $resultUser = mysqli_query($mysqli, "SELECT * FROM `Users` WHERE 1=1 AND User_ID = '$userId'");
            $rowUser = mysqli_fetch_array($resultUser);
            $residence = $rowUser['residence'];
            $postalCode = $rowUser['postalcode'];
            $address = $rowUser['address'];
            $bank = $rowUser['bank'];
            $paymentMethod = $rowUser['paymentMethod'];
            ?>
            <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
            <p>Woonplaats:</p>
            <input type="text" name="residence" class="form-control" value="<?php echo $residence; ?>" /><br />
            <p>Postcode:</p>
            <input type="text" name="postalCode" class="form-control" value="<?php echo $postalCode; ?>" /><br />
            <p>Straat en huisnummer:</p>
            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>" /><br />
            <p>Bank:</p>
			<select class="form-control form-control" name="bank">
                <option value="">Kies een bank</option>
                <option value="ABN AMRO" <?php if ($bank == "ABN AMRO") { echo "selected='selected'"; } ?>>ABN AMRO</option>
                <option value="ASN Bank" <?php if ($bank == "ASN Bank") { echo "selected='selected'"; } ?>>ASN Bank</option>
                <option value="ASR Bank" <?php if ($bank == "ASR Bank") { echo "selected='selected'"; } ?>>ASR Bank</option>
                <option value="BinckBank" <?php if ($bank == "BinckBank") { echo "selected='selected'"; } ?>>BinckBank</option>
                <option value="Rabobank" <?php if ($bank == "Rabobank") { echo "selected='selected'"; } ?>>Rabobank</option>
                <option value="Delta Lloyd" <?php if ($bank == "Delta Lloyd") { echo "selected='selected'"; } ?>>Delta Lloyd</option>
                <option value="ING Bank" <?php if ($bank == "ING Bank") { echo "selected='selected'"; } ?>>ING Bank</option>
                <option value="Nationale-Nederlanden" <?php if ($bank == "Nationale-Nederlanden") { echo "selected='selected'"; } ?>>Nationale-Nederlanden</option>
                <option value="RegioBank" <?php if ($bank == "RegioBank") { echo "selected='selected'"; } ?>>RegioBank</option>
                <option value="SNS Bank" <?php if ($bank == "SNS Bank") { echo "selected='selected'"; } ?>>SNS Bank</option>
                <option value="Triodos Bank" <?php if ($bank == "Triodos Bank") { echo "selected='selected'"; } ?>>Triodos Bank</option>
			</select><br />
            <p>Betaalmethode:</p>
			<select class="form-control form-control" name="paymentMethod">
                <option value="">Kies een betaalmethode</option>
                <option value="iDEAL" <?php if ($paymentMethod == "iDEAL") { echo "selected='selected'"; } ?>>iDEAL</option>
		        <option value="Visa" <?php if ($paymentMethod == "Visa") { echo "selected='selected'"; } ?>>Visa</option>
		        <option value="Mastercard" <?php if ($paymentMethod == "Mastercard") { echo "selected='selected'"; } ?>>Mastercard</option>
		        <option value="PayPal" <?php if ($paymentMethod == "PayPal") { echo "selected='selected'"; } ?>>PayPal</option>
		        <option value="Afterpay" <?php if ($paymentMethod == "Afterpay") { echo "selected='selected'"; } ?>>Afterpay</option>
			</select><br />
            <div class="clearfix">
                <a type="button" href="<?php echo "{$redirectFrom}.php"; ?>" class="btn btn-outline-secondary btn-lg float-left">Terug</a>
                <button type="submit" class="btn btn-outline-primary btn-lg float-right">Bestelling afronden</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
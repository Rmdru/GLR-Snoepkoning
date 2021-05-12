<?php
//start session
session_start();

//redirect if user is not logged in
if (!isset($_SESSION['userId'])) {
    header("location: inloggen.php");
}

$csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['csrfToken'] = $csrfToken;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Winkel - Snoepkoning</title>
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
	<?php
    //error msg
    if ($_GET['status'] == "failed") {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Bestellen mislukt. Probeer het opnieuw.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    } else if ($_GET['statusPayment'] == "failed") {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Bestelling afronden mislukt. Probeer de bestelling opnieuw te plaatsen.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    } else if ($_GET['statusPayment'] == "success") {
        echo "<div class='alert alert-success' role='alert'><i class='material-icons align-middle'>check</i> Bestelling succesvol afgerond. Winkel op deze pagina verder of <a class='link' href='dashboard.php'>bekijk hier je bestelling</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    }
    ?>
	<div class="row m-2 mb-5">
		<?php
		//load config file
		require "includes/config.inc.php";

		 //query
		 $result = mysqli_query($mysqli, "SELECT * FROM `Product` WHERE 1=1");

		//show all products
		while ($row = mysqli_fetch_array($result)) {
			//vars
			$productId = $row['Product_ID'];
			$name = $row['Product_Naam'];
			$price = $row['Prijs'];
			$img = $row['img'];
			$discountRate = $row['discountRate'];
			$discountCode = $row['discountCode'];
			$resultPrice = str_replace(".", ",", $price);
			echo "<div class='card col-md-3 p-3 m-3' style='background-color:#7deb34;'>";
				echo "<img src='img/{$img}' style='width:100%;' class='border border-dark Card image cap mt-1 mb-1 rounded' />";
				echo "<p>{$name}</p>";
				echo "<p>€{$resultPrice} per snoepje</p>";
				if ($discountRate > 0) {
					echo "<p style='color: red;'>Gebruik de kortingscode {$discountCode} voor {$discountRate}% korting op de totaalprijs!</p>";
				}
				?>
				<!--form fields-->
				<form action="includes/bestellen.inc.php?redirectFrom=winkel&productId=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<p>Aantal snoepjes:</p>
					<input type="number" value="1" min="1" step="1" name="amount" class="form-control"/><br />
					<p>Kleur:</p>
					<select class="form-control form-control" name="color">
						<option value="Standaard (verassing)">Standaard (verassing)</option>
						<option value="Rood">Rood</option>
						<option value="Groen">Groen</option>
						<option value="Blauw">Blauw</option>
						<option value="Oranje">Oranje</option>
						<option value="Geel">Geel</option>
						<option value="Paars">Paars</option>
						<option value="Roze">Roze</option>
					</select><br />
					<p>Vorm:</p>
					<select class="form-control form-control" name="shape">
						<option value="Standaard (rond)">Standaard (rond)</option>
						<option value="Rood">Vierkant</option>
						<option value="Driehoek">Driehoek</option>
					</select><br />
					<p>Verpakking:</p>
					<select class="form-control form-control" name="packaging">
						<option value="Standaard (zak)">Standaard (zak)</option>
						<option value="Pot">Pot</option>
					</select><br />
					<p>Smaak:</p>
					<select class="form-control form-control" name="taste">
						<option value="Standaard (zoet)">Standaard (zoet)</option>
						<option value="Zuur">Zuur</option>
						<option value="Zout">Zout</option>
						<option value="Pittig">Pittig</option>
						<option value="Bitter">Bitter</option>
					</select><br />
					<p>Eigen logo uploaden en printen (kost €5 extra):</p>
					<div class="custom-file">
						<input type="file" name="logo" class="custom-file-input" id="customFile" accept="image/jpeg" />
						<label class="custom-file-label" for="customFile">Kies een logo</label>
					</div><br /><br />
					<p>Kortingscode:</p>
					<input type="text" name="discountCode" class="form-control" />
					<input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
				</div>
				<?php
				echo "<button type='submit' class='btn btn-outline-primary btn-lg btn-block'>Bestellen</button>";
				echo "</form>";
			echo "</div>";
		}
		?>
		</div>
	</div>
	<br /><br /><footer class="page-footer bg-dark sticky-bottom">
		<div class="bg-dark text-white">
			<div class="container">
				<div class="row py-4 d-flex align-items-center">
					<div class="col-md-12 text-center">
					<p>In entirely be to at settling felicity. Fruit two match men you seven share. Needed as or is enough points. Miles at smart ﻿no marry whole linen mr. Income joy nor can</p>
					</div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>

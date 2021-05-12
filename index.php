<?php
//start session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-ligth bg-warning sticky-top">
<div class="container-fluid">
	<img src="img/logo.png" style="width:100px; height:100px;">
	<h1 class="display-4">De Snoep Koning</h1>
	<div class="cikkaose navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav ml-auto">
			<?php
			if (isset($_SESSION['userId'])) {
			?>
				<li class="navbar-item mr-3 ml-3 mb-1">
					<a href="winkel.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
						Winkel
					</a>
				</li>
				<li class="navbar-item mr-3 ml-3">
					<a href="dashboard.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
						Account dashboard
					</a>
				</li>
				<li class="navbar-item mr-3 ml-3">
					<a href="includes/uitloggen.inc.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
						Uitloggen
					</a>
				</li>
			<?php
			} else {
			?>
				<li class="navbar-item mr-3 ml-3 mb-1">
					<a href="inloggen.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
						Inloggen
					</a>
				</li>
				<li class="navbar-item mr-3 ml-3">
					<a href="registreren.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
						Registreren
					</a>
				</li>
			<?php
			}
			?>
			</ul>
	</div>
</div>
</nav>
	<div class="row m-2 mb-5">
		<div class="card col-md-3 mt-2 mb-2" style="background-color:#7deb34;">
			<img src="img/Snoep.png" style="width:100%; " class=" border border-dark Card image cap mt-1 mb-1 rounded">

				<p style="background-color:#7deb34;">
					<a href="winkel.php" type="button" class="btn btn-outline-warning btn-lg btn-block">
						Bekijk de snoep
					</a>
				De Snoep Koning heeft veel versillende lekkere snoep voor jong en oud. de winkel bestaat al 6 jaar en is een klanten favorite. van zacht naar hard snoep verkopen wij het allemaal.</p>

		</div>
		<div class="card col-md-3 mt-2 mb-2" style="background-color:#7deb34;">
			<img src="img/winkel.png" style="width:100%;" class=" border border-dark Card image cap mt-1 mb-1 rounded">

				<p style="background-color:#7deb34;">
					<button type="button" class="btn btn-outline-warning btn-lg btn-block">
						De Winkel
					</button>
				Onze winkel is een mooie leuke snoep winkel met allemaal snoep die je
			hier kan kopen. alles staat mooi bij elkaar en elke maand hebben we weer
		nieuwe snoep. ook op feest dagen hebben we speciaal snoep.</p>

		</div>
		<div class="card col-md-3 mt-2 mb-2" style="background-color:#7deb34;">
			<img src="img/Snoep.png" style="width:100%;" class=" border border-dark Card image cap mt-1 mb-1 rounded">

				<p style="background-color:#7deb34;">
					<button type="button" class="btn btn-outline-warning btn-lg btn-block">
						Over ons
					</button>
				De snoep koning is gemaakt door Jan van der Man en staat in Rotterdam.
				hij begon in 1987 en gooide de deur open voor alle Nederlanders die van
				snoep houden. nu staat hij hier al 44 jaar en we werken hard door om
				uw snoep te brengen</p>

		</div>
		<div class="card col-md-3 mt-2 mb-2" style="background-color:#7deb34;">
			<img src="img/Snoep.png" style="width:100%;" class=" border border-dark Card image cap mt-1 mb-1 rounded">

				<p style="background-color:#7deb34;">
					<button type="button" class="btn btn-outline-warning btn-lg btn-block">
						Corona
					</button>
				Door corona hebben wij het best zwaar nu maar u kan de snoep komen ophalen bij ons. houd u aan de regels en heeft u geen sintomen dan kan u makkelijk langs komen na een bestelling.</p>

		</div>
	</div>	
	<footer class="page-footer bg-dark sticky-bottom">
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

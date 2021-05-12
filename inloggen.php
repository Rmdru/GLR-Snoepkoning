<?php
//start session
session_start();

if (isset($_SESSION['userId'])) {
    header("location: winkel.php");
}
?>
<!doctype html>
<html>
<!--head-->
<head>
    <meta charset="utf-8" />
    <title>Inloggen - Snoepkoning</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<!--body-->
<body class="text-center">
    <!--header-->
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
                <a href="registreren.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Registreren
                </a>
            </li>
        </ul>

    </div>
</div>
</nav>
    <!--login form-->
    <div class="container my-3">
    <?php
    //error msg
    if ($_GET['status'] == "failed") {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Inloggen mislukt, zorg dat je de juiste inloggegevens gebruikt. Als je nog geen account hebt, <a href='registreren.php'>registreer je dan hier</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div><br /><br />";
    }
    ?>
    </div>
<div class="d-flex justify-content-center h-100 mt-8">
    <div class="card p-3 mb-2 bg-secondary text-white">
        <form action="includes/inloggen.inc.php" method="post" class="form-signin">
            <?php
            //create csrf token
            $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;
            ?>
            <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
            <h1 class="h2 mb-3">Emailadres</h1>
            <input class="form-control" type="email" name="email" placeholder="E-mailadres" /><br /><br />
            <h1 class="h2 mb-3">Wachtwoord</h1>
            <input class="form-control" type="password" name="psw" placeholder="Wachtwoord" id="psw" /><br />
            <button onclick="showPswToggle();" class="btn btn-outline-warning btn-lg" type="button"><i class="material-icons align-middle">remove_red_eye</i> Toon wachtwoord</button><br /><br />
            <input class="btn btn-outline-warning btn-lg btn-block" type="submit" value="Inloggen" />
        </form>
    </div>
</div>
    <!--load js files-->
    <script type="text/javascript" src="js/inloggen.js"></script>
</body>
</html>
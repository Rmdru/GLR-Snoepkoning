<?php
//start session
session_start();

if (isset($_SESSION['userId'])) {
    header("location: winkel.php");
}

//load captcha file
require "includes/captcha.inc.php";
?>
<!doctype html>
<html>
<!--head-->
<head>
    <meta charset="utf-8" />
    <title>Registreren - Snoepkoning</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<!--body-->
<body>
    <!--header-->
    <nav class="navbar navbar-expand-md navbar-ligth bg-warning sticky-top">
    <div class="container-fluid">
    <img src="img/logo.png" style="width:100px; height:100px;">
    <h1 class="display-4">De Snoep Koning</h1>
    <div class="cikkaose navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="navbar-item mr-3 ml-3">
                <a href="index.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Home
                </a>
            </li>
            <li class="navbar-item mr-3 ml-3">
                <a href="inloggen.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Inloggen
                </a>
            </li>
        </ul>

    </div>
</div>
</nav>
    <!--register form-->
    <div class="container my-3">
    <?php
    //error or success msg
    if ($_GET['status'] == "failed") {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Registreren mislukt, zorg dat je alle velden correct invult en als je al een account hebt, <a href='inloggen.php'>log dan hier in</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div><br /><br />";
    } else if ($_GET['status'] == "success") {
        echo "<div class='alert alert-success' role='alert'><i class='material-icons align-middle'>check</i> Registreren succesvol, <a class='link' href='inloggen.php'>klik hier om in te loggen</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div><br /><br />";
    }
    ?>
    </div>
    <div class="d-flex justify-content-center h-100 mt-8">
        <div class="card p-3 mb-2 bg-secondary text-white">
            
            <form action="includes/registreren.inc.php" method="post" class="form-signin">
                <?php
                //create csrf token
                $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
                $_SESSION['csrfToken'] = $csrfToken;
                ?>
                <!--form fields-->
                <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <h1 class="h2 mb-3">Naam</h1>
                <input class="form-control" type="text" name="name" placeholder="Naam" /><br />
                <h1 class="h2 mb-3">Emailadres</h1>
                <input class="form-control" type="email" name="email" placeholder="E-mailadres" /><br />
                <h1 class="h2 mb-3">Wachtwoord</h1>
                <input class="form-control" type="password" name="psw" placeholder="Wachtwoord" id="psw" /><br />
                <div class="text-center"> 
                    <button onclick="showPswToggle();" class="btn btn-outline-warning btn-lg" type="button"><i class="material-icons align-middle">remove_red_eye</i> Toon wachtwoord</button><br /><br />
                    <button onclick="generateRandomPsw();" class="btn btn-outline-warning btn-lg" type="button">Genereer een random wachtwoord</button><br /><br />
                    <img src="img/captchaImg.php" id="captchaImg" />
                    <button onclick="reloadCaptcha()" class="btn btn-outline-warning btn-lg" type="button"><i class="material-icons align-text-top">refresh</i></button><br /><br />
                </div>
                <input class="form-control" type="text" placeholder="CAPTCHA" name="captcha" /><br /><br />
                <input type="submit" value="Registreren" class="btn btn-outline-warning btn-lg btn-block" />
            </form>
        </div>
    </div>
    <!--load js files-->
    <script type="text/javascript" src="js/registreren.js"></script>
</body>
</html>
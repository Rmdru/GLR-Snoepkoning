<!doctype html>
<html>
<!--head-->
<head>
    <meta charset="utf-8" />
    <title>Verifeer je e-mailadres - Snoepkoning</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>
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
            <li class="navbar-item mr-3 ml-3">
                <a href="registreren.php" type="button" class="btn btn-outline-primary btn-lg btn-block">
                    Registreren
                </a>
            </li>
        </ul>
    </div>
    </div>
    </nav>
    <div class="container my-3">
    <?php
    //load config file
    require "includes/config.inc.php";

    //vars
    $error = 0;
    $verificationToken = $_GET['verificationToken'];
    $verificationToken = htmlspecialchars($verificationToken);
    $verificationToken = stripslashes($verificationToken);
    $verificationToken = trim($verificationToken);
    $verificationToken = strip_tags($verificationToken);
    $verificationToken = htmlentities($verificationToken);
    $userId = $_GET['userId'];
    $userId = htmlspecialchars($userId);
    $userId = stripslashes($userId);
    $userId = trim($userId);
    $userId = strip_tags($userId);
    $userId = htmlentities($userId);

    //check if pattern of verification token is correct
    $verificationTokenPattern = "/[a-zA-Z0-9]{32}/";
    if (!preg_match($verificationTokenPattern, $verificationToken)) {
        $error++;
    }

    //check if pattern of user id is correct
    $userIdPattern = "/[0-9]*/";
    if (!preg_match($userIdPattern, $userId)) {
        $error++;
    }

    //if there are no errors verify email otherwise show error
    if ($error == 0) {
        $resultVerify = mysqli_query($mysqli, "SELECT COUNT(Email) AS num FROM Users WHERE 1=1 AND verificationToken = '$verificationToken' AND User_ID = '$userId'");
        $rowVerify = mysqli_fetch_array($resultVerify);

        if ($rowVerify['num'] == 1) {
            $result = mysqli_query($mysqli, "UPDATE `Users` SET verified = 1, verificationToken = '' WHERE User_ID = '$userId'");
            
            if ($result) {
                echo "<div class='alert alert-success' role='alert'><i class='material-icons align-middle'>check</i> Je e-mailadres is succesvol geverifieërd. Je kunt nu gebruik maken van je nieuwe account door <a href='inloggen.php'>hier in te loggen</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Er is een fout opgetreden bij het verifiëren van het e-mailadres. Probeer je <a href='registreren.php'>hier opnieuw te registreren</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Er is een fout opgetreden bij het verifiëren van het e-mailadres. Probeer je <a href='registreren.php'>hier opnieuw te registreren</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'><i class='material-icons align-middle'>close</i> Er is een fout opgetreden bij het verifiëren van het e-mailadres. Probeer je <a href='registreren.php'>hier opnieuw te registreren</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div></div>";
    }
    ?>
    </div>
</body>
</html>
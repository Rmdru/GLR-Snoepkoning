"use strict";

//show psw toggle
function showPswToggle() {
    var pswField = document.getElementById("psw");
    if (pswField.type == "text") {
        pswField.type = "password";
    } else {
        pswField.type = "text";
    }
}

//generate random psw
function generateRandomPsw() {
    var pswField = document.getElementById("psw");
    pswField.value = "";
    var randomLength = Math.floor(Math.random() * (32 - 8 + 1) ) + 8;
    var characters = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0","!","@","#","$","%","^","&","*","(",")","{","}","|","[","]",";","'",":","<",">","?","/"];
    for (var i = 0; i < randomLength; i++) {
        var randomPsw = characters[Math.floor(Math.random()*characters.length)];
        pswField.value += randomPsw;
    }
}

//reload captcha
function reloadCaptcha() {
	var xmlHttp = new XMLHttpRequest();

  	xmlHttp.open("GET", "includes/captcha.inc.php", true);
  	xmlHttp.send(null);
	
	document.getElementById("captchaImg").src = "img/captchaImg.php";
}
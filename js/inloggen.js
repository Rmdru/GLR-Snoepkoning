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
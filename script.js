
function checkForBlankFields(emailFieldID, passwordFieldID) {

    var emailValue = document.getElementById(emailFieldID).value;
    var passwordValue = document.getElementById(passwordFieldID).value;
    var getEmailErrorSpanID = document.getElementById(emailFieldID+'ErrorSpan');
    var getPasswordErrorSpanID = document.getElementById(passwordFieldID+'ErrorSpan');
    var valid = true;

    if (emailValue === "" || emailValue === null) {
        getEmailErrorSpanID.innerHTML = "Email field can't be blank.";
        valid = false;
    }
    else {
        getEmailErrorSpanID.innerHTML = "";
    }

    if (passwordValue === "" || passwordValue === null) {
        getPasswordErrorSpanID.innerHTML = "Password field can't be blank.";
        valid = false;
    }
    else {
        getPasswordErrorSpanID.innerHTML = "";
    }

    return valid;

}

function checkForBlankRegister(emailFieldID, userNameFieldID, passwordFieldID) {
    var emailValue = document.getElementById(emailFieldID).value;
    var userNameValue = document.getElementById(userNameFieldID).value;
    var passwordValue = document.getElementById(passwordFieldID).value;
    var getEmailErrorSpanID = document.getElementById(emailFieldID+'ErrorSpan');
    var getUserNameErrorSpanID = document.getElementById(userNameFieldID+'ErrorSpan');
    var getPasswordErrorSpanID = document.getElementById(passwordFieldID+'ErrorSpan');
    var valid = true;

    if (emailValue === "" || emailValue === null) {
        getEmailErrorSpanID.innerHTML = "Email field can't be blank.";
        valid = false;
    }
    else {
        getEmailErrorSpanID.innerHTML = "";
    }

    if (userNameValue === "" || userNameValue === null) {
        getUserNameErrorSpanID.innerHTML = "Username field can't be blank.";
        valid = false;
    }
    else {
        getUserNameErrorSpanID.innerHTML = "";
    }

    if (passwordValue === "" || passwordValue === null) {
        getPasswordErrorSpanID.innerHTML = "Password field can't be blank.";
        valid = false;
    }
    else {
        getPasswordErrorSpanID.innerHTML = "";
    }

    return valid;
}
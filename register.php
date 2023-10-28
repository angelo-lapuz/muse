<?php
session_start();

$errorMessage = "";

if (isset($_SESSION['errors'])) {
    $errorMessage = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src='./script.js'></script>
    <link rel="stylesheet" href="src/style.css">
    <title>Register</title>
</head>
<body id="register-body">
    <section id="register-container">
        <form action="postValidation" method="post" onsubmit="return checkForBlankRegister('register-email','register-user-name','register-password')" id="register-form">
            <label for="register-email">Email:</label>
            <input type="email" id="register-email" name="register_email">
            <span id="register-emailErrorSpan"></span>
            <label for="register-user-name">Username:</label>
            <input type="text" id="register-user-name" name="register_username">
            <span id="register-user-nameErrorSpan"></span>
            <label for="register-password">Password:</label>
            <input type="password" id="register-password" name="register_password">
            <span id="register-passwordErrorSpan"></span>
            <input type="submit" name="submit-type" value="Register">
            <a href="log_in" id="log-out-button">Cancel</a>
        </form>
        <span id="registerErrorMessage"><?php echo $errorMessage;?></span>
    </section>
</body>
</html>

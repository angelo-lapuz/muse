<?php
session_start();
require_once ("post_validation.php");
if (isset($_SESSION['user'])) {
    header("Location: index.php");
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
    <title>Log In</title>
</head>
<body>
    <main>
        <div id="login-form-container">
            <form action="post_validation.php" method="post" onsubmit="return checkForBlankFields('email','password')">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <span id="emailErrorSpan"></span>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span id="passwordErrorSpan"></span>
                <input type="submit" value="Login">
            </form>
            <span id="loginErrorMessage"><?php ?></span>
        </div>
    </main>
</body>
</html>

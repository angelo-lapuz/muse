<?php
session_start();
//require("index.php");
//require_once("postValidation.php");
$errorMessage = "";
//print_r($_SESSION['user']);
//print_r(@parse_url($_SERVER['REQUEST_URI'])['path']);
// delete this later
//if (isset($_SESSION['user'])) {
//    unset($_SESSION['user']);
//}
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
    <title>Log In</title>
</head>
<body>
    <main>
        <div id="login-form-container">
            <form action="postValidation" method="post" onsubmit="return checkForBlankFields('email','password')">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email">
                <span id="emailErrorSpan"></span>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span id="passwordErrorSpan"></span>
                <input type="submit" name="submit-type" value="Login">
            </form>
            <span id="loginErrorMessage"><?php echo $errorMessage;?></span>
        </div>
    </main>
</body>
</html>

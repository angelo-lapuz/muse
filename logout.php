<?php
session_start();
unset($_SESSION['user_name']);
unset($_SESSION['email']);
session_destroy();

header("Location: log_in");

exit;

?>

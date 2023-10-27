<?php
session_start();

require 'vendor/autoload.php';
require_once('databaseQuery.php');

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


$sdk = new Aws\Sdk([
    'profile' => 'default',
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$database = new DatabaseQuery();



if (!empty($_POST)) {
    // store error message in $_SESSION['errors']
    // and in log in page get $_SESSION['errors']
    // to show on errormsg span if there is an error
    // don't forget to unset $_SESSION['errors']
    // after

    $errorMessage = "";
    $result = "";
    if ($_POST['submit-type'] === 'Login') {
        $result = $database->logInQuery();

        // make another query to get info from other tables
        // of same id for subscriptions etc
        // or u can just query subscriptions from main page so
        // you dont need to change user session variables alot
        if (isset($result['Item'])) {
            $_SESSION['user_name'] = $result['Item']['user_name']['S'];
            $_SESSION['email'] = $result['Item']['email']['S'];
            echo $_SESSION['email']. 'from postvalidation';
            header("Location: main");
        }
        else {
            $_SESSION['errors'] = "Email / Password invalid.";
            header("Location: log_in");
        }
    }
    else if ($_POST['submit-type'] === 'Remove') {
        //write something here
        // create a databaseQuery function that removes
        // an item
//        echo $_POST['song-title'];
        $removeSongResult = $database->removeSubscribedSong($_POST['song-title']);
        if ($removeSongResult === true) {
            header("Location: main");
        }
        else if ($removeSongResult === false) {
            $_SESSION['errors'] = "Could not remove song from subscribed list. Try again.";
            header("Location: main");
        }
    }

    else if ($_POST['submit-type'] === 'Query') {

//        $_SESSION['errors'] = 'working elseif.';

        if (!empty($_POST['query-song-title']) and $_POST['query-song-title'] != "") {
            $_SESSION['query-song']['title'] = $_POST['query-song-title'];

        }

        if (isset($_POST['query-song-artist']) and $_POST['query-song-artist'] != "") {
            $_SESSION['query-song']['artist'] = $_POST['query-song-artist'];

        }

        if (isset($_POST['query-song-year']) and $_POST['query-song-year'] != "") {
            $_SESSION['query-song']['year'] = $_POST['query-song-year'];

        }

        if (!isset($_SESSION['query-song'])) {
            $_SESSION['errors'] = 'Query cannot be blank';
        }

        header("Location: main");

    }

    else if ($_POST['submit-type'] === 'Subscribe') {
        if(!empty($_POST['song-title']) and !empty($_POST['song-artist'])) {
            $database->addSongToSubscribed($_SESSION['email'], $_POST['song-title'], $_POST['song-artist']);
            header("Location: main");
        }
    }

    else if ($_POST['submit-type'] === 'Register') {
        $email = $_POST['register_email'];
        $result = $database->findEmail($email);
        if (empty($result['Items'])) {
            $userName = $_POST['register_username'];
            $password = $_POST['register_password'];
            $database->addUser($email, $userName, $password);
            header("Location: log_in");
        }
        else {
            $_SESSION['errors'] = "The email already exists";
            header("Location: register");
        }

    }

}
?>
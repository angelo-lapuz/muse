<?php


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
?>
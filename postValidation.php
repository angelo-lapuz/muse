<?php
session_start();

require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class postValidation {

    private $sdk;

    function __construct() {

        $this->sdk = new Aws\Sdk([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => 'latest'
        ]);
    }

    function validateLogIn() {
        $loginErrorMessage = "";
        if (!empty($_POST)) {
           //$result = log in query result
        }
    }
}

if (!empty($_POST)) {
    // store error message in $_SESSION['errors']
    // and in log in page get $_SESSION['errors']
    // to show on errormsg span if there is an error
    // don't forget to unset $_SESSION['errors']
    // after

    $errorMessage = "";

}
?>
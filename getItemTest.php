<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$sdk = new Aws\Sdk([
    'profile' => 'default',
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'login';

$email = "s39143781@student.rmit.edu.au";
$password = '123456';

$key = $marshaler->marshalJson('
    {
        "email": "' . $email . '",
        "password": "' . $password . '"
    }
');

//$key = array(
//    "email" => array('S' => $email),
//    "password" => array('S' => $password)
//);

$params = [
    'TableName' => $tableName,
    'Key' => $key
];

try {
    $result = $dynamodb->getItem($params);
//    print_r($result["Item"]);
//    echo $result["Item"]["user_name"]["S"];
    echo $result->get("Count");


} catch (DynamoDbException $e) {
    echo "Unable to get item:\n";
    echo $e->getMessage() . "\n";
}

?>



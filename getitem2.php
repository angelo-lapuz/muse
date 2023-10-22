<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception;

$sdk = new Aws\Sdk([
    'profile' => 'default',
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();

$tableName = 'login';

$email = "s39143788@student.rmit.edu.au";
$password = '8901230';

$params = [
    'TableName' => $tableName,
    'Key' => [
        'email' => [
            'S' => $email,
        ],
        'password' => [
            'S' => $password,
        ],
    ],
];

try {
    $result = $dynamodb->getItem($params);

    if (isset($result['Item'])) {
        // Item found, you can access its attributes here
        $item = $result['Item'];
        echo "Item found:\n";
//        print_r($item);
        echo $item['user_name']["S"];
    } else {
        echo "Item not found.\n";
    }
} catch (DynamoDbException $e) {
    echo "Unable to get item:\n";
    echo $e->getMessage() . "\n";
}
?>

<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;

$sdk = new Aws\Sdk([
    'profile' => 'default',
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();

$params = [
    'TableName' => 'music',
    'KeySchema' => [
        [
            'AttributeName' => 'title',
            'KeyType' => 'HASH'  //Partition key
        ],
        [
            'AttributeName' => 'year',
            'KeyType' => 'RANGE'  //Sort key
        ]
    ],
    'AttributeDefinitions' => [
        [
            'AttributeName' => 'title',
            'AttributeType' => 'S'
        ],
        [
            'AttributeName' => 'year',
            'AttributeType' => 'S'
        ],

    ],
    'ProvisionedThroughput' => [
        'ReadCapacityUnits' => 10,
        'WriteCapacityUnits' => 10
    ]
];

try {
    $result = $dynamodb->createTable($params);
    echo 'Table has been created.'."\n".'Table Status: ' .
        $result['TableDescription']['TableStatus'] ."\n";

} catch (DynamoDbException $e) {
    echo "Could not create table:\n";
    echo $e->getMessage() . "\n";
}
?>
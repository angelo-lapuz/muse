<?php

require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class database {
    private $sdk;

    function __construct() {

        $this->sdk = new Aws\Sdk([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => 'latest'
        ]);
    }

    function logInQuery() {
        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'login';

        $eav = $marshaler->marshalJson('
    {
        ":yyyy":1992,
        ":letter1": "A",
        ":letter2": "L"
    }
');
    }

    function createTable() {
        $dynamodb = $this->sdk->createDynamoDb();

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
echo 'Created table.  Status: ' .
$result['TableDescription']['TableStatus'] ."\n";

} catch (DynamoDbException $e) {
    echo "Unable to create table:\n";
    echo $e->getMessage() . "\n";
}
    }

    // delete table for testing
    function deleteTable() {
        $dynamodb = $this->sdk->createDynamoDb();

        $params = [
            'TableName' => 'music'
        ];

        try {
            $result = $dynamodb->deleteTable($params);
            echo "Deleted table.\n";

        } catch (DynamoDbException $e) {
            echo "Unable to delete table:\n";
            echo $e->getMessage() . "\n";
        }
    }

    function populateTable() {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'music';
        $songs = json_decode(file_get_contents('a2.json'), true);
        // get each song in $songs array
        foreach ($songs as $song) {

            foreach ($song as $value) {

                $title = $value['title'];
                $artist = $value['artist'];
                $year = $value['year'];
                $web_url = $value['web_url'];
                $img_url = $value['img_url'];

                $json = json_encode([
                    'title' => $title,
                    'artist' => $artist,
                    'year' => $year,
                    'web_url' => $web_url,
                    'img_url' => $img_url
                ]);

                $params = [
                    'TableName' => $tableName,
                    'Item' => $marshaler->marshalJson($json)
                ];

                try {
                    $result = $dynamodb->putItem($params);
                    echo "Song added: " . $value['title'] . " by " . $value['artist'] . "\n";
                } catch (DynamoDbException $e) {
                    echo "Unable to add movie:\n";
                    echo $e->getMessage() . "\n";
                    break;
                }
            }

        }
    }
}
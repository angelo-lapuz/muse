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
            echo "Table could not be populated:\n";
            echo $e->getMessage() . "\n";
            break;
        }
    }

}
?>

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
//Expression attribute values
//$eav = $marshaler->marshalJson('
// {
// ":artist": "Taylor Swift"
// }
//');

//$title = 'I Knew You Were Trouble';
//$yr = '2008';
//$artist = 'Taylor Swift';



$paramsAll = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'artist = :artist AND #yr = :released_yr AND title = :title',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [
        ':artist' => ['S' => $artist],
        ':released_yr' => ['S' => $yr],
        ':title' => ['S' => $title]],
];

$paramsTitle = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'title = :title',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':title' => ['S' => $title]],
];

$paramsArtist = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'artist = :artist',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':artist' => ['S' => $artist]],
];

$paramsYear = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => '#yr = :released_yr',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':released_yr' => ['S' => $yr]],
];

$paramsTitleArtist = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'title = :title and artist = :artist',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':title' => ['S' => $title],
        ':artist' => ['S' => $artist]],
];

$paramsTitleYear = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'title = :title and #yr = :released_yr',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':title' => ['S' => $title],
        ':released_yr' => ['S' => $yr]],
];

$paramsArtistYear = [
    'TableName' => 'music',
    'ProjectionExpression' => 'artist, title, #yr, img_url',
    'FilterExpression' => 'artist = :artist and #yr = :released_yr',
    'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
    'ExpressionAttributeValues' => [':artist' => ['S' => $artist],
        ':released_yr' => ['S' => $yr]],
];

echo "Scanning Movies table.\n";

// differnet to the foreach
$result = $dynamodb->scan($paramsArtistYear);
if(!empty($result['Items'])) {
    echo 'somehting inside';
} else {
    echo 'nothing';
}

try {
    while (true) {
        $result = $dynamodb->scan($paramsArtistYear);

        foreach ($result['Items'] as $i) {
            $song = $marshaler->unmarshalItem($i);
            echo $song['title'] . ': ' . $song['artist']. "\n";
        }
        if (isset($result['LastEvaluatedKey'])) {
            $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
        } else {
            break;
        }
    }
} catch (DynamoDbException $e) {
    echo "Unable to scan:\n";
    echo $e->getMessage() . "\n";
}
?>

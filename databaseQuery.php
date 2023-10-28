<?php

require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class DatabaseQuery {
    private $sdk;

    function __construct() {

        $this->sdk = new Aws\Sdk([
//            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => 'latest'
        ]);
    }

    function logInQuery() {
        $dynamodb = $this->sdk->createDynamoDb();
        $tableName = 'login';

        $email = $_POST['email'];
        $password = $_POST['password'];

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
        return $dynamodb->getItem($params);

    }

    function getSubscribedSongs() {
        $dynamodb = $this->sdk->createDynamoDb();
        $tableName = 'subscribed';
        $email = $_SESSION['email'];


        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'email = :emailValue',
            'ExpressionAttributeValues'=> [':emailValue' => ['S' => $email],
                ],
        ];


            return $dynamodb->query($params);

    }

    function getSong($title, $artist) {
        $dynamodb = $this->sdk->createDynamoDb();
        $tableName = 'music';

        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'title = :titleValue AND artist = :artistValue',
            'ExpressionAttributeValues'=> [':titleValue' => ['S' => $title],
                ':artistValue' => ['S' => $artist],
            ],
        ];


        return $dynamodb->query($params);
    }

    function removeSubscribedSong($title) {
        $songRemoved = false;
        $email = $_SESSION['email'];
        $dynamodb = $this->sdk->createDynamoDb();

        $tableName = 'subscribed';


        $params = [
            'TableName' => $tableName,
            'Key' => [
                'email' => [
                    'S' => $email,
                ],
                'title' => [
                    'S' => $title,
                ],
            ],
        ];

        try {
            $result = $dynamodb->deleteItem($params);
            echo "Deleted item.\n";
            $songRemoved = true;

        } catch (DynamoDbException $e) {
            echo "Unable to delete item:\n";
            echo $e->getMessage() . "\n";
        }
        return $songRemoved;
    }

    function getImageURL($title, $artist) {

        $songResult = $this->getSong($title, $artist);


        foreach ($songResult['Items'] as $song) {
            $url = $song['img_url']['S'];
            $splitString = explode("/",$url);

            $bucket = 's3914378-assignment2-bucket';
            $keyName = end($splitString);


            $s3 = new S3Client([
//                'profile' => 'default',
                'region' => 'us-east-1',
                'version' => 'latest',
            ]);
            return $s3->getObjectUrl(
                $bucket,
                $keyName
            );
        }
    }

    function querySong($searchType) {
        $dynamodb = $this->sdk->createDynamoDb();

        $params = '';
        $result = '';
        if ($searchType === 'all') {
            $artist = $_SESSION['query-song']['artist'];
            $yr = $_SESSION['query-song']['year'];
            $title = $_SESSION['query-song']['title'];

            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'artist = :artist AND #yr = :released_yr AND title = :title',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [
                    ':artist' => ['S' => $artist],
                    ':released_yr' => ['S' => $yr],
                    ':title' => ['S' => $title]],
            ];

        }
        else if($searchType === 'title') {

            $title = $_SESSION['query-song']['title'];
            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'title = :title',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':title' => ['S' => $title]],
            ];

        }
        else if($searchType === 'artist') {

            $artist = $_SESSION['query-song']['artist'];

            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'artist = :artist',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':artist' => ['S' => $artist]],
            ];

        }
        else if($searchType === 'year') {

            $yr = $_SESSION['query-song']['year'];

            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => '#yr = :released_yr',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':released_yr' => ['S' => $yr]],
            ];

        }
        else if ($searchType === 'title-artist') {
            $artist = $_SESSION['query-song']['artist'];
            $title = $_SESSION['query-song']['title'];
            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'title = :title and artist = :artist',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':title' => ['S' => $title],
                    ':artist' => ['S' => $artist]],
            ];

        }
        else if($searchType == 'title-year') {
            $yr = $_SESSION['query-song']['year'];
            $title = $_SESSION['query-song']['title'];
            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'title = :title and #yr = :released_yr',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':title' => ['S' => $title],
                    ':released_yr' => ['S' => $yr]],
            ];

        }
        else if ($searchType === 'artist-year') {
            $artist = $_SESSION['query-song']['artist'];
            $yr = $_SESSION['query-song']['year'];

            $params = [
                'TableName' => 'music',
                'ProjectionExpression' => 'artist, title, #yr, img_url',
                'FilterExpression' => 'artist = :artist and #yr = :released_yr',
                'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
                'ExpressionAttributeValues' => [':artist' => ['S' => $artist],
                    ':released_yr' => ['S' => $yr]],
            ];

        }

        $marshaler = new Marshaler();
        $result = $dynamodb->scan($params);

        if(!empty($result['Items'])) {
            foreach ($result['Items'] as $i) {
                $song = $marshaler->unmarshalItem($i);
                $title = $song['title'];
                $year = $song['year'];
                $artist = $song['artist'];
                $songImageArtwork = $this->getImageURL($title, $artist);
//                    echo 'Title = '. $song['title']."\n".'Artist: '.$song['artist']."\n"
//                        ."Year: ".$song['year']."\n";

                $searchedMusic = <<<"MUSIC"
                        <article id="subscribed-song">
                            <form action="postValidation" method="post" id="subscribed-song-form">
                                <input type="hidden" name="song-title" value="$title">
                                <input type="hidden" name="song-year" value="$year">
                                <input type="hidden" name="song-artist" value="$artist">
                                <div id="searched-song-detail-holder">
                                    <span id="subscribed-song-title-span">$title</span>
                                    <span id="subscribed-song-artist-span">$artist</span>  
                                    <span id="subscribed-song-year-span">$year</span>
                                    <span id="subscribed-song-artwork-span"><img src=$songImageArtwork alt="$artist artwork" id="artwork-img"></span>
                                    <input type="submit" name="submit-type" value="Subscribe" id="subscribe-button">
                                </div>
                            </form>
                        </article>
MUSIC;
                echo $searchedMusic;
            }

            if (isset($result['LastEvaluatedKey'])) {
                $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
            }
        } else {
            $_SESSION['errors'] = 'No result retrieved. Please query again';
        }

    }

    function addSongToSubscribed($email, $title, $artist) {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'subscribed';


        $item = json_encode([
            'email' => $email,
            'title' => $title,
            'artist' => $artist

        ]);
        $params = [
            'TableName' => $tableName,
            'Item' => $marshaler->marshalJson($item)
        ];


        try {
            $dynamodb->putItem($params);

        } catch (DynamoDbException $e) {
            echo "Unable to add item:\n";
            echo $e->getMessage() . "\n";
            $_SESSION['errors'] = "Could not add song to subscribed list";
        }
    }

    function findEmail($email) {
        $dynamodb = $this->sdk->createDynamoDb();
        $tableName = 'login';


        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'email = :emailValue',
            'ExpressionAttributeValues' => [':emailValue' => ['S' => $email],
            ],
        ];


        return $dynamodb->query($params);
    }

    function addUser($email, $userName, $password) {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'login';


        $item = json_encode([
            'email' => $email,
            'user_name' => $userName,
            'password' => $password

        ]);
        $params = [
            'TableName' => $tableName,
            'Item' => $marshaler->marshalJson($item)
        ];


        try {
            $dynamodb->putItem($params);

        } catch (DynamoDbException $e) {
            echo "Unable to add item:\n";
            echo $e->getMessage() . "\n";
            $_SESSION['errors'] = "Could not add song to subscribed list";
        }
    }
}
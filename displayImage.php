<?php
require 'vendor/autoload.php';
require_once('databaseQuery.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$database = new DatabaseQuery();
$songResult = $database->getSong("Hey Soul Sister", "Train");
//print_r($songResult);

foreach ($songResult['Items'] as $song) {
    $url = $song['img_url']['S'];
    $splitString = explode("/",$url);

    $bucket = 's3914378-assignment2-bucket';
    $keyName = end($splitString);
//    echo $keyName;

    $s3 = new S3Client([
        'profile' => 'default',
        'region' => 'us-east-1',
        'version' => 'latest',
    ]);
    try {
// Get the object.
        $result = $s3->getObjectUrl(
            $bucket,
            $keyName
        );
//        print_r($result['Body']);
//        header("Content-Type: {$result['ContentType']}");
        echo $result;
    } catch (S3Exception $e) {
        echo $e->getMessage() . PHP_EOL;

    }
}



?>

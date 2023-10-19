<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// getting contents from json file a2.json
$songs = json_decode(file_get_contents('a2.json'), true);

foreach ($songs as $song) {

    foreach($song as $value) {
//        $url = 'https://raw.githubusercontent.com/davidpots/songnotes_cms/master/public/images/artists/TaylorSwift.jpg';
        // getting the value of the image_url key
        $url = $value['img_url'];

        //splitting the string using the delimiter "/"
        $splitString = explode("/",$url);

        /* the end() function gets the last element of the array
        which in this case is the name of image file */
        $fileName = end($splitString);
        $filePath = './images/'.$fileName;

//        echo $fileName."\n";
        $tempFile = fopen($filePath,"w");

        // turning the contents of the file into a string
        $fileContents = file_get_contents($url);

        // placing content into file path.
        file_put_contents($filePath, $fileContents);
        echo "Downloaded: ". $fileName . "\n";
    }
}

foreach ($songs as $song) {
    foreach ($song as $value) {
        $bucket = 's3914378-a2-bucket';
        $url = $value['img_url'];
        $splitString = explode("/",$url);
        $keyName = end($splitString);
        $filePath = './images/'.$keyName;

        try {
            //Create a S3Client
            $s3 = new S3Client([
                'profile' => 'default',
                'region' => 'us-east-1',
                'version' => 'latest',
            ]);
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key' => $keyName,
                'SourceFile' => $filePath,
            ]);
            echo "Uploaded: ". $keyName . "\n";
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
//$splitString = explode("/",$url);
//$fileName = end($splitString);
//$filePath = './images/'.$fileName;
//echo $fileName;
//$tempFile = fopen($filePath,"w");
//$fileContents = file_get_contents($url);
//file_put_contents($filePath, $fileContents);
?>
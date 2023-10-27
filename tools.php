<?php

require_once('databaseQuery.php');

use Aws\DynamoDb\Marshaler;

function displaySubscribedMusic() {
    $marshaler = new Marshaler();
    $database = new DatabaseQuery();
    $result = $database->getSubscribedSongs();

    if (isset($result['Items'])) {

        foreach ($result['Items'] as $subscribedSong) {
            $subscribedSongTitle = $marshaler->unmarshalValue($subscribedSong['title']);
//            $songYear = $marshaler->unmarshalValue($song['year']);
            $subscribedSongArtist = $marshaler->unmarshalValue($subscribedSong['artist']);

            // create another query in database that gets songs from song table
            $songResult = $database->getSong($subscribedSongTitle, $subscribedSongArtist);
            foreach ($songResult['Items'] as $song) {
                // call query that gets subscribed music of user and display results
                // using for each

                $songTitle = $marshaler->unmarshalValue($song['title']);
                $songYear = $marshaler->unmarshalValue($song['year']);
                $songArtist = $marshaler->unmarshalValue($song['artist']);
                $songImageArtwork = $database->getImageURL($songTitle, $songArtist);
                $subscribedMusic = <<<"MUSIC"
                <article id="subscribed-song">
                    <form action="postValidation" method="post" id="subscribed-song-form">
                        <input type="hidden" name="song-title" value="$songTitle">
                        <input type="hidden" name="song-year" value="$songYear">
                        <input type="hidden" name="song-artist" value="$songArtist">
                        <div id="subscribed-song-detail-holder">
                            <span id="subscribed-song-title-span">$songTitle</span>
                            <span id="subscribed-song-artist-span">$songArtist</span>  
                            <span id="subscribed-song-year-span">$songYear</span>
                            <span id="subscribed-song-artwork-span"><img src=$songImageArtwork alt="$songArtist artwork" id="artwork-img"></span>
                            <input type="submit" name="submit-type" value="Remove" id="remove-button">
                        </div>
                    </form>
                </article>
MUSIC;
                echo $subscribedMusic;
            }
        }

    }
    else {
        echo "couldnt ge tanything";
    }

}

function displaySearchedSong() {
    $database = new DatabaseQuery();
    if (isset($_SESSION['query-song'])) {


        if (count($_SESSION['query-song']) === 3) {
            $database->querySong('all');

        }

        if (count($_SESSION['query-song']) === 2) {
            // find which indexes are set
            if (isset($_SESSION['query-song']['title']) && isset($_SESSION['query-song']['artist'])) {
                $database->querySong('title-artist');

            }
            else if (isset($_SESSION['query-song']['title']) && isset($_SESSION['query-song']['year'])) {

                $database->querySong('title-year');

            }
            else if (isset($_SESSION['query-song']['artist']) && isset($_SESSION['query-song']['year'])) {

                $database->querySong('artist-year');

            }
        }

        if (count($_SESSION['query-song']) === 1) {
            if (isset($_SESSION['query-song']['title'])) {

                $database->querySong('title');

            }
            else if (isset($_SESSION['query-song']['artist'])) {

                $database->querySong('artist');

            }
            else if (isset($_SESSION['query-song']['year'])) {

                $database->querySong('year');

            }
        }

        unset($_SESSION['query-song']);
    }
}



?>
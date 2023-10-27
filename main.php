<?php
session_start();

require_once ('databaseQuery.php');
require_once ('tools.php');

$database = new DatabaseQuery();
$errorMessage = "";

if (isset($_SESSION['errors'])) {
    $errorMessage = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="src/style.css">
    <title>Muse</title>
</head>
<body id="main-body">
    <header id="logo-header"><span id="logo-span">MUSE.</span></header>
    <section id="user-section">
        <span id="username-span">Welcome <?php echo $_SESSION['user_name']; ?></span>
        <a href="logout" id="log-out-button">Logout</a>
    </section>
    <section id="subscription-query-section">
        <section id="subscription-section">
            <section id="subscribed-music-container">
                <div id="subscribed-heading-container">
                    <span>Your Subscribed Songs</span>
                </div>
                <div id="subscribed-music-heading-container">
                    <span id="song-title-header">Title</span>
                    <span id="song-artist-header">Artist</span>
                    <span id="song-year-header">Year</span>
                    <span id="song-artwork-header">Artwork</span>
                </div>
                <span id="subscribed-error-message"></span>
                <?php displaySubscribedMusic();?>
            </section>
        </section>
        <section id="query-section">
            <div id="query-heading">
                <span>Find a song</span>
            </div>
            <section id="query-container">
                <form action="postValidation" method="post" id="form-query-container">
                    <label for="query-song-title">Title:</label>
                    <input type="text" id="query-song-title" name="query-song-title">
                    <br><br>
                    <label for="query-song-artist">Artist:</label>
                    <input type="text" id="query-song-artist" name="query-song-artist">
                    <br><br>
                    <label for="query-song-year">Year:</label>
                    <input type="text" id="query-song-year" name="query-song-year">
                    <br><br>
                    <span id="query-error-message"><?php echo $errorMessage; ?></span>
                    <input type="submit" name="submit-type" value="Query" id="query-button">
                </form>
            </section>
            <section id="query-results-display">
                <div id="subscribed-heading-container">
                    <span>Searched Songs</span>
                </div>
                <div id="subscribed-music-heading-container">
                    <span id="song-title-header">Title</span>
                    <span id="song-artist-header">Artist</span>
                    <span id="song-year-header">Year</span>
                    <span id="song-artwork-header">Artwork</span>
                </div>
                <?php displaySearchedSong(); ?>
            </section>
        </section>
    </section>
</body>
</html>

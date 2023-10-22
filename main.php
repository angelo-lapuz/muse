<?php

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
<body>
<!--    <h1>muse</h1>-->
<!--    <br><br>-->
<!--    <h1>--><?php //echo $_SESSION['user_name']; ?><!--</h1>-->
    <header id="logo-header"><span id="logo-span">MUSE.</span></header>
    <section id="user-query-section">
        <span id="username-span">Welcome <?php echo $_SESSION['user_name']; ?></span>
        <div id="query-container">
            <label for="song-title-input">Title:</label>
            <input type="text" id="song-title-input" name="songTitleInput">
            <label for="song-year-input">Year:</label>
            <input type="text" id="song-year-input" name="songYearInput">
            <label for="song-artist-input">Title:</label>
            <input type="text" id="song-artist-input" name="songArtistInput">
        </div>
    </section>
    <section id="subscription-section"></section>
    <section id="query-section"></section>
</body>
</html>

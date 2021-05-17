<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/heure_video.css">
    <title>Video | Fessebouc</title>
</head>
<body>
    <?php
        require 'connection_bdd.php';

        require 'elements/nav_bar.php';
    ?>
    <h1>Je t'avais prevenu ...</h1>
    <video id="vid_win" src="vid/nian_cat.mp4" autoplay="true" type="video/mp4" preload="auto" controls width="800px"></video><br>
</body>
</html>
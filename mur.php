<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Fessebouc</title>
</head>
<body>

    <?php
        if (isset($_SESSION['id'])) {

            require 'connection_bdd.php';

            require 'elements/nav_bar.php';

            ?>
            <div id="page">
                <?php

                require 'elements/div_gauche.php';

                require 'elements/div_principal.php';

                require 'elements/div_droite.php';

                ?>
            </div>
            <?php
        }
        else {
            echo 'Vous devez avoir un compte pour accéder a cette page...';
        }
    ?>
    
</body>
</html>
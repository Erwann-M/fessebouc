<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Chargement | Fessebouc</title>
</head>
<body>

<?php
    require 'connection_bdd.php';

    require 'elements/nav_bar.php';

    require 'elements/convert_sec_time.php';

    
    if (isset($_GET['ok'])) {
        $temps_total = convert_sec_in_time($_GET['ok']);
        ?>
            <div id="conteneur_temps">
                <p>Tu a tenu :</p>
                <h1><?php echo $temps_total ?></h1><br>
                <a id="lien_a" href="mur.php">Retourner au mur</a>
                <a id="lien_b" href="chargement.php">Recommencer<br> (j'aime perdre mon temps)</a>
            </div>
        <?php
    }
    else {

        ?>
        <div id="conteneur_chargement">
            <div id="xbs_loader">
            &nbsp;
            </div>
            
        </div>
        
        <?php
        
            $debut = time();
            echo '<a href="elements/calcul_tmp_chargement.php?debut='. $debut .'" id="fin_chargement">STOP ! C\'est bon j\'en ai marre...</a>';
    }
?>
    
</body>
</html>
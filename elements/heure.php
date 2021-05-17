<?php
    session_start();

    require '../connection_bdd.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/heure_video.css">
    <title>Winner | Fessebouc</title>
</head>
<body>
    <?php
    $prenom = $bdd->query('SELECT prenom FROM membres WHERE id = '. $_SESSION['id'] .'');
    $prenom_u = $prenom->fetch();
    ?>
    <h1>Bravo <?php echo $prenom_u['prenom'] ?> !!! </h1>
    <p>Tu as tenu plus d'une heure !<br>Soit tu n'a rien a faire de ta vie, soit tu a trafiquer l'url. Dans les deux cas je te félicite !<br>Voici une petite récompence vidéo rien que pour toi !</p>
    <?php 
    $monfichier = fopen('compteur.txt', 'r+');

    $pages_vue = fgets($monfichier); //On lit la premiere ligne
    $pages_vue += 1;//On ajoute 1 au nombre de pages vues
    fseek($monfichier, 0);//On remet le curseur au debut du fichier (ce qui ecrasera l'ancienne ligne)
    fputs($monfichier, $pages_vue);//On écrit le nouveau nombre de pages vues

    fclose($monfichier);
    if ($pages_vue == 1) {
        echo '<p>Tu es le '. $pages_vue .'er visiteur de cette page !!! </p>';
    }
    else {
        echo '<p>Tu es le ' . $pages_vue . 'éme visiteur de cette page !</p>';
    }
    ?>
    <video id="vid_win" src="../vid/rick_roll.mp4" autoplay="true" type="video/mp4" preload="auto" controls></video><br>
    <a href="../mur.php">Quitter cette page de m$#*%</a>


</body>
</html>
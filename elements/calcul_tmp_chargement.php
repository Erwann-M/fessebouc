<?php
session_start();

require '../connection_bdd.php';

if (isset($_GET['debut'])) {
    $fin = time();
    $result = $fin - $_GET['debut'];

    $nbr_sec_exist = $bdd->query('SELECT nbr_sec_charge FROM membres WHERE id = '. $_SESSION['id'] .'');
    $nbr_seconde_exist = $nbr_sec_exist->fetch();

    if ($result > $nbr_seconde_exist['nbr_sec_charge']) {

        $ajout_secondes = $bdd->prepare('UPDATE membres SET nbr_sec_charge = :resultat WHERE id = '. $_SESSION['id'] .'');
        $ajout_secondes_bdd = $ajout_secondes->execute(array(
            'resultat' => $result
        ));
    }
    if ($result >= 3600) {
        header('Location:heure.php?ok='. $result .'');
    }
    else {
        header('Location:../chargement.php?ok='. $result .'');
    }
    
}


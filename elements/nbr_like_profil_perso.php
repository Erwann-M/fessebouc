<?php
session_start();
require '../connection_bdd.php';


if (isset($_GET['id_post'])) {
    if ($_COOKIE[$_GET['id_post']] == $_SESSION['id']) {
        $bdd->exec('UPDATE post SET nbr_like = nbr_like-1 WHERE id='. $_GET['id_post'] .'');

        setcookie($_GET['id_post']);

        header('Location:../profil.php?page='. $_GET['page_actuelle'] .'&utilisateur='. htmlspecialchars($_GET['utilisateur']) .'');
    }
    else {
        $bdd->exec('UPDATE post SET nbr_like = nbr_like+1 WHERE id='. $_GET['id_post'] .'');

        setcookie($_GET['id_post'], $_SESSION['id'], time() + 365*24*3600, null, null, false, true);
        
        header('Location:../profil.php?page='. $_GET['page_actuelle'] .'&utilisateur='. htmlspecialchars($_GET['utilisateur']) .'');
    }
}
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/page_profil.css">
    <?php
    if ($_GET['utilisateur'] !== $_SESSION['id']) {
        echo '<link rel="stylesheet" href="style/visit_profil.css">';
    }
    ?>
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Profil |Fessebouc</title>
</head>
<body>

<?php
require 'connection_bdd.php';
// ------------------------------------------------------------traitement du formulaire ----------------------------------------------------------------------------------------
            // Sauvegarde des photos posté dans le dossier "image_post"
    if (isset($_FILES['photo']['tmp_name']) && isset($_POST['texte_post'])) {
        // Définition des constantes :
        define('TARGET', 'image_post/'); // Repertoire cible
        define('MAX_SIZE', 1000000); // Taille max en octets
        define('WIDTH_MAX', 2800); // Largeur max en pixels
        define('HEIGHT_MAX', 2800); // Hauteur max en pixels

        // Tableau de données
        $tab_ext = array('jpg', 'gif', 'png', 'jpeg'); // Extentions autorisées
        $info_img = array();

        // Variables qui seront remplies plus tard
        $extention = '';
        $message = '';
        $nom_image = '';


        if (!empty($_FILES['photo']['name'])) {
        
            $liaison_post = $bdd->query('SELECT id FROM post ORDER BY id DESC LIMIT 0,1');
            $num_post = $liaison_post->fetch();
        
            // Recupération de l'extention du fichier
            $extention = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
            // Vérification de l'extention du fichier 
            if (in_array(strtolower($extention), $tab_ext)) {
        
                // Récupération des dimensions de l'image
                $info_img = getimagesize($_FILES['photo']['tmp_name']);
        
                // Vérification du type de l'image 
                if ($info_img[2] >= 1 && $info_img[2] <= 14) {
        
                    // Verification de la taille et des dimensions de l'image
                    if (($info_img[0] <= WIDTH_MAX) && ($info_img[1] <= HEIGHT_MAX) && (filesize($_FILES['photo']['tmp_name'])) <= MAX_SIZE) {
                        
                        // Parcours du tableau d'érreur
                        if (isset($_FILES['photo']['error']) && UPLOAD_ERR_OK === $_FILES['photo']['error']) {
                            
                            // on renomme l'image
                            $nom_image = ($num_post['id'] + 1). '.' . $extention;
                            $insert_post = $bdd->prepare('INSERT INTO post (id_utilisateur, contenu, date_creation, num_image) VALUES (:id_utilisateur, :contenu, CURDATE(), :num_image)');
                            $insert_post->execute(array(
                                'id_utilisateur' => $_SESSION['id'],
                                'contenu' => htmlspecialchars($_POST['texte_post']),
                                'num_image'=> $nom_image
                            ));
        
                            // si c'est ok, on test l'upload
                            if (move_uploaded_file($_FILES['photo']['tmp_name'], TARGET.$nom_image)) {
                                
                                $message = '<p id="image_ok">Upload de l\'image réussi !</p>';
                            }
                            else {
                                $message = '<p class="erreur_image">Problème lors de l\'upload !</p>';
                            }
                        }
                        else {
                            $message = '<p class="erreur_image">Il y as eu une erreur interne lors de l\'upload.</p>';
                        }
                    }
                    else {
                        $message = '<p class="erreur_image">l\'image est trop grande.</p>';
                    }
                }
                else {
                    $message = '<p class="erreur_image">Le fichier uploader n\'est pas une image.</p>';
                }
            }
            else {
                $message = '<p class="erreur_image">l\'extention n\'est pas bonne.</p>';
            }
        }
        elseif (isset($_POST['texte_post'])) {
            $insert_post = $bdd->prepare('INSERT INTO post (id_utilisateur, contenu, date_creation) VALUES (:id_utilisateur, :contenu, CURDATE())');
            $insert_post->execute(array(
                'id_utilisateur' => $_SESSION['id'],
                'contenu' => $_POST['texte_post']
            ));
        }
    }

        if (isset($_SESSION['id']) && isset($_SESSION['prenom'])) {


            require 'elements/nav_bar.php';

            ?>

                <!-- recuperation de toutes les données, nom, prenoms photos etc et les afficher. -->
                <div id="photo_nom_couv_bg">
                    
                    <div id="photo_nom_couv">
                        <?php // Chargement de la photo de profil et de la couverture
                            $profil_pic = $bdd->query('SELECT profil_pic, photo_couv FROM membres WHERE id = '. htmlspecialchars($_GET['utilisateur']) .'');
                            $profil_pic_u = $profil_pic->fetch();
                        ?>
                        <img src="photo_couv/<?php echo $profil_pic_u['photo_couv'] ?>" id="photo_couv">
                        <a href="changer_photo_couv.php?utilisateur=<?php echo $_SESSION['id'] ?>" id="changer_photo_couv_btn"><img src="img/appareil_photo.png" id="appareil_photo"> Changer la photo de couverture</a>

                        <div id="conteneur_photo_profil_perso">
                            <img src="profil_pic/<?php echo $profil_pic_u['profil_pic'] ?>" id="photo_profil_perso">
                            <a href="changer_photo_profil.php?utilisateur=<?php echo $_SESSION['id'] ?>" id="changer_photo_profil_btn"><img src="img/appareil_photo.png"></a>
                        </div>
                        <?php
                            $nom_prenom = $bdd->query('SELECT prenom, nom, description_u FROM membres WHERE id='. htmlspecialchars($_GET['utilisateur']) .'');
                            $nom_prenom_profil = $nom_prenom->fetch();
                        ?>
                        <h1><?php echo htmlspecialchars($nom_prenom_profil['prenom']) . ' ' . htmlspecialchars($nom_prenom_profil['nom']) ?></h1>
                        <a href="modifier_profil.php?utilisateur=<?php echo $_SESSION['id'] ?>" id="btn_modif_profil"><img src="img/crayon.png" >Modifier le profil</a>
                    </div>

                </div>
                <div id="principal_profil">
                    <div id="perso">
                        <div id="intro">
                            <?php
                                $info = $bdd->query('SELECT nbr_sec_charge, etudes, habitation, genre, metier, DATE_FORMAT (date_inscription, \'%d/%m/%Y\') AS date_inscription_fr, DATE_FORMAT (date_naissance, \'%d/%m/%Y\') AS date_naissance_fr FROM membres WHERE id = '. htmlspecialchars($_GET['utilisateur']) .'');
                                $info_intro = $info->fetch();
                            ?>
                            <h2>Intro</h2>
                            <div class="line_intro">
                                <p><img src="img/date_inscription.png" alt="">  Membre depuis le : <strong><?php echo htmlspecialchars($info_intro['date_inscription_fr']) ?></strong></p>
                            </div>
                            <div class="line_intro">
                                <p><img src="img/date_anniversaire.png" alt="">  Né le : <strong><?php echo htmlspecialchars($info_intro['date_naissance_fr']) ?></strong></p>
                            </div>
                            <div class="line_intro">
                                <p><img src="img/etudes.png" alt="">  Niveau d'étude : <strong><?php echo htmlspecialchars($info_intro['etudes']) ?></strong></p>
                            </div>
                            <div class="line_intro">
                                <p><img src="img/habitation.png" alt="">  Habite : <strong><?php echo htmlspecialchars($info_intro['habitation']) ?></strong></p>
                            </div>
                            <div class="line_intro">
                                <p><img src="img/genre.png" alt="">  Genre : <strong><?php echo htmlspecialchars($info_intro['genre']) ?></strong></p>
                            </div>
                            <div class="line_intro">
                                <p><img src="img/metier.png" alt="">  Métier : <strong><?php echo htmlspecialchars($info_intro['metier']) ?></strong></p>
                            </div>
                            <?php
                                if (!empty($info_intro['nbr_sec_charge'])) {
                                    require 'elements/convert_sec_time.php';
                                    ?>
                                        <div class="line_intro">
                                            <p><img src="img/time.png" alt="">  Record chargement : <strong><?php echo convert_sec_in_time($info_intro['nbr_sec_charge']) ?></strong></p>
                                        </div>
                                    <?php
                                }
                            ?>
                        </div>
                        <div id="description">
                            <h2>Qui est <?php echo htmlspecialchars($nom_prenom_profil['prenom']) . ' ' . htmlspecialchars($nom_prenom_profil['nom']) ?> ?</h2>
                            <p><?php echo htmlspecialchars($nom_prenom_profil['description_u']) ?></p>
                        </div>
                    </div>
                    <?php
                    if ($_GET['utilisateur'] == $_SESSION['id']) {
                    ?>
                    <div id="post_perso">
                        <div id="post">
                            <form method="post" name="formulaire" enctype="multipart/form-data">
                                <div id="img_text_post">
                                    <img src="profil_pic/<?php echo $profil_pic_u['profil_pic']?>" id="img_profil_post">
                                    <textarea name="texte_post" id="texte_post" cols="50" rows="3" placeholder="Raconte ta vie ici <?php echo $_SESSION['prenom']?>."></textarea>
                                </div>
                                <div id="insert_image">
                                    <p>insérer une image :</p>
                                    <?php
                                        if( !empty($message) ) 
                                        {
                                        echo $message;
                                        }
                                    ?>
                                    <input type="file" name="photo" id="photo">
                                    <input type="submit" value="Publier" id="publier">
                                </div>
                            </form>
                        </div>
                    <?php
                    }
                    ?>

                        <div id="dernier_post">
                            <?php
                                // systeme de pagination -----------------------------------------------------------------------
                                $post_par_pages = 5;
                                $nbr_post = $bdd->query('SELECT id FROM post WHERE id_utilisateur='. htmlspecialchars($_GET['utilisateur']) .'');
                                $nbr_total_post = $nbr_post->rowCount();
                                $nbr_page_totale = ceil($nbr_total_post/$post_par_pages);

                                if (isset($_GET['page']) and !empty($_GET['page']) and $_GET['page'] > 0 and $_GET['page'] <= $nbr_page_totale) {
                                    // on securise le $_GET['page'] pour que meme si une chaine de caractère est rentré, 
                                    // il le converti en entier
                                    $_GET['page'] = intval($_GET['page']);
                                    $page_courrante = $_GET['page'];
                                } else {
                                    $page_courrante = 1;
                                }

                                $page_de_depart = ($page_courrante - 1) * $post_par_pages;
                                
                                // systeme d'affichage des post en boucles------------------------------------------------------
                                $recup_post = $bdd->query('SELECT id, id_utilisateur, contenu, num_image, nbr_like, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_creation_fr FROM post WHERE id_utilisateur='. htmlspecialchars($_GET['utilisateur']) .' ORDER BY id DESC LIMIT '. $page_de_depart .','. $post_par_pages .'');

                                while ($donnees = $recup_post->fetch()) {
                                    ?>
                                    <div id="post_amis">
                                        <?php
                                        $nom_amis = $bdd->query('SELECT prenom, nom, profil_pic FROM membres WHERE id='. $donnees['id_utilisateur'] .'');
                                        $nom_amis_post = $nom_amis->fetch();
                                        ?>
                                            <div id="pic_nom_prenom">
                                                <?php
                                                echo '<img src="profil_pic/'. $nom_amis_post['profil_pic'] .'">';
                                                echo '<h2>'. $nom_amis_post['prenom'] .' '. $nom_amis_post['nom'] .'</h2>';
                                                
                                        echo '<p id="date_post">Le : ' . $donnees['date_creation_fr'] . '</p>';
                                        ?>
                                            </div>
                                        <?php
                                        echo '<p id="contenu_post_text">' . $donnees['contenu'] . '</p>';

                                        if (!empty($donnees['num_image'])) {
                                            echo '<img src="image_post/'. $donnees['num_image'] .'" id="img_post_amis"><br>';
                                        }
                                        ?>
                                        <div id="like_comment">
                                            <a href="elements/nbr_like_profil_perso.php?id_post=<?php echo $donnees['id']?>&page_actuelle=<?php echo $page_courrante?>&utilisateur=<?php echo htmlspecialchars($_GET['utilisateur']) ?>" id="like_btn"><?php echo $donnees['nbr_like'] ?> J'aime</a>

                                            <?php
                                                $nbr_commentaire = $bdd->prepare('SELECT COUNT(id_post) AS nbr_commentaire FROM commentaires WHERE id_post=?');
                                                $nbr_commentaire->execute(array(
                                                    $donnees['id']
                                                ));
                                                $nbre_commentaire = $nbr_commentaire->fetch()
                                            ?>

                                            <a href="commentaires.php?id_post=<?php echo $donnees['id']?>&page_actuelle=<?php echo $page_courrante?>&retour=profil&utilisateur=<?php echo htmlspecialchars($_GET['utilisateur']) ?>" id="commentaire"><?php echo $nbre_commentaire['nbr_commentaire'] ?> Commentaires</a>
                                        </div>
                                        <?php
                                        
                                        ?>
                                    </div>
                                    <?php
                                }

                                    ?>
                                    <div id="conteur_page">
                                        <?php
                                        for ($i = 1; $i <= $nbr_page_totale; $i ++) {
                                            if ($i == $page_courrante) {
                                                echo $i;
                                            }
                                            else {
                                                echo '<a href="profil.php?page='. $i .'&utilisateur='. htmlspecialchars($_GET['utilisateur']) .'">'. $i .'</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                        </div>
                    </div>
                </div>
            <?php

        }
        else {
            echo 'Vous devez avoir un compte pour accéder a cette page...';
        }

            ?>
    
</body>
</html>
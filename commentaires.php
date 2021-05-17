<?php
    session_start();
    require 'connection_bdd.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Fessebouc | Commentaires</title>
</head>
<body>
    <?php
        require 'elements/nav_bar.php';
    ?>
    <div id="page">

        <?php
            require 'elements/div_gauche.php';
        ?>

        <div id="principal">

            <div id="post_amis">
                <?php
                $recup_post = $bdd->query('SELECT id, id_utilisateur, contenu, num_image, nbr_like, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_creation_fr FROM post WHERE id='. $_GET['id_post'] .'');

                $donnees = $recup_post->fetch();

                $nom_amis = $bdd->query('SELECT id, prenom, nom, profil_pic FROM membres WHERE id='. $donnees['id_utilisateur'] .'');
                $nom_amis_post = $nom_amis->fetch();
                ?>
                    <div id="pic_nom_prenom">
                        <?php
                        echo '<img src="profil_pic/'. $nom_amis_post['profil_pic'] .'">';
                        echo '<h2><a href="profil.php?utilisateur='. $nom_amis_post['id'] .'">'. $nom_amis_post['prenom'] .' '. $nom_amis_post['nom'] .'</a></h2>';
                        
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
                    <a href="<?php echo $_GET['retour'] ?>.php?page=<?php echo $_GET['page_actuelle']?>&utilisateur=<?php echo htmlspecialchars($_GET['utilisateur']) ?>" id="commentaire">Retourner sur le <?php echo $_GET['retour'] ?></a>
                </div>
            </div>
                <?php
                    if (isset($_POST['texte_comment'])) {
                        $insert_comment = $bdd->prepare('INSERT INTO commentaires (id_post, id_utilisateur, contenu, date_commentaire) VALUES (:id_post, :id_utilisateur, :contenu, CURDATE())');
                        $insert_comment->execute(array(
                            'id_post' => $_GET['id_post'],
                            'id_utilisateur' => $_SESSION['id'],
                            'contenu' => $_POST['texte_comment']
                        ));
                    }
                ?>
            <div id="post">
                <form method="post">
                    <div id="img_text_post">
                        <?php
                            $profil_pic = $bdd->query('SELECT profil_pic FROM membres WHERE id = '. $_SESSION['id'] .'');
                            $profil_pic_u = $profil_pic->fetch();
                        ?>
                        <img src="profil_pic/<?php echo $profil_pic_u['profil_pic'] ?>" id="img_profil_post">
                        <textarea name="texte_comment" id="texte_post" cols="65" rows="3" placeholder="C'est pas gentil d'etre méchant..."></textarea>
                    </div>

                    <div id="insert_image">
                        <p>Vas y fait le ...</p>
                        <input type="submit" value="Critiquer" id="publier">
                    </div>
                </form>
            </div>

            <div id="dernier_post">
                <?php

                    $recup_commentaires = $bdd->query('SELECT id, id_utilisateur, contenu, id_post, nbr_like, DATE_FORMAT(date_commentaire, \'%d/%m/%Y\') AS date_commentaire_fr FROM commentaires WHERE id_post='. $_GET['id_post'] .' ORDER BY id DESC ');

                    while ($donnees = $recup_commentaires->fetch()) {
                        ?>
                        <div id="post_amis">
                            <?php
                            $nom_amis = $bdd->query('SELECT id, prenom, nom, profil_pic FROM membres WHERE id='. $donnees['id_utilisateur'] .'');
                            $nom_amis_post = $nom_amis->fetch();
                            ?>
                                <div id="pic_nom_prenom">
                                    <?php
                                    echo '<img src="profil_pic/'. $nom_amis_post['profil_pic'] .'">';
                                    echo '<h2><a href="profil.php?utilisateur='. $nom_amis_post['id'] .'">'. $nom_amis_post['prenom'] .' '. $nom_amis_post['nom'] .'</a></h2>';
                                    
                            echo '<p id="date_post">Le : ' . $donnees['date_commentaire_fr'] . '</p>';
                            ?>
                                </div>
                            <?php
                            echo '<p id="contenu_post_text">' . $donnees['contenu'] . '</p>';
                            
                            ?>
                            
                        </div>
                        <?php
                    }
                        
                            require 'elements/div_droite.php';
                        ?>
            </div>

        </div>


    </div>
    
</body>
</html>
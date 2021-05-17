<?php
session_start();

require 'connection_bdd.php';
    

    // Sauvegarde des photos posté dans le dossier "image_post"
    if (isset($_FILES['photo']['tmp_name'])) {
        // Définition des constantes :
        define('TARGET', 'profil_pic/'); // Repertoire cible
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
                            $nom_image = md5(uniqid()). '.' . $extention;
                            $insert_post = $bdd->prepare('UPDATE membres SET profil_pic = :num_image WHERE id = :id');
                            $insert_post->execute(array(
                                'id' => $_SESSION['id'],
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
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/changement_photo_couv_profil.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>changer photo de couverture</title>
</head>
<body>

<?php
    require 'elements/nav_bar.php';
?>

<div id="post">
    <h1 id="titre_photo_couv">Change ta photo de profil</h1>
        <?php
            $change_couv = $bdd->query('SELECT profil_pic FROM membres WHERE id = '. $_SESSION['id'] .'');
            $change_couv_pic = $change_couv->fetch();
        ?>
    <img src="profil_pic/<?php echo $change_couv_pic['profil_pic'] ?>" id="change_photo_profil">
    <form method="post" name="formulaire" enctype="multipart/form-data">
        
        <div id="insert_image">
            <p>insérer une image :</p>
            <?php
                if( !empty($message) ) 
                {
                    echo $message;
                }
            ?>
            <input type="file" name="photo" id="photo">
            <input type="submit" value="Publier" id="publier"><br>
            <a id="retour_profil" href="profil.php?utilisateur=<?php echo $_SESSION['id'] ?>">Retourner au profil</a>
        </div>
    </form>
</div>
</body>
</html>
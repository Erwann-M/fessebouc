
<?php
    // Définition des constantes :
    define('TARGET', 'image_post/'); // Repertoire cible
    define('MAX_SIZE', 100000); // Taille max en octets
    define('WIDTH_MAX', 800); // Largeur max en pixels
    define('HEIGHT_MAX', 800); // Hauteur max en pixels

    // Tableau de données
    $tab_ext = array('jpg', 'gif', 'png', 'jpeg'); // Extentions autorisées
    $info_img = array();

    // Variables qui seront remplies plus tard
    $extention = '';
    $message = '';
    $nom_image = '';

    if (!empty($_FILES['photo']['name'])) {

        $liaison_post = $bdd->query('SELECT TOP 1 id FROM post ORDER BY id DESC');
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
                        $nom_image = $num_post['id']. '.' . $extention;

                        // si c'est ok, on test l'upload
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], TARGET.$nom_image)) {
                            $message = '<p id="image_ok">Upload de l\'image réussi !</p>';
                        }
                        else {
                            $message = '<p class="erreur_image">Problème lors de l\'upload !';
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/inscription.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Inscription |Fessebouc</title>
</head>
<body>

    <div id="conteneur_inscription">
        <form method="post">

            <div id="entete_formulaire">
                <div id="text_entete_formulaire">
                    <h1>S'inscrire</h1><br>
                    <p>C'est rapide et facile.</p>
                </div>
                <a href="index.php" id="croix" title="La petite croix c'est pour quitter en général..."><img src="img/croix.png"></a>
            </div>
    <?php

    if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['pass2']) && isset($_POST['date_naissance'])) {
        if (isset($_POST['valid_donnee'])) {

            // on verifie la correspondance des mots de passes
            if ($_POST['pass'] == $_POST['pass2']) {
    
                // on test le format du mail.
                $_POST['email'] = htmlspecialchars($_POST['email']);
                if (preg_match('#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#', $_POST['email'])) {
                    
                    require 'connection_bdd.php';
    
                    $_POST['email'] = strtolower($_POST['email']);
    
                    $email = $bdd->query('SELECT email FROM membres WHERE email="'. $_POST['email'] .'"');
                    $email_exist = $email->fetch();
    
                    // on verifi si le mail existe déja.
                    if (isset($email_exist['email'])) {
                        echo '<p class="erreur_formulaire">L\'e-mail : '. $_POST['email'] .' est déja utilisé!</p>';
                        $email->closeCursor();
                    }
                    else {
                        // on crypte le mot de passe.
                        $mdp_crypt = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
                        $registration = $bdd->prepare('INSERT INTO membres (prenom, nom, email, pass, date_naissance, date_inscription) VALUES (:prenom, :nom, :email, :pass, :date_naissance, CURDATE())');
                        $registration->execute(array(
                            'prenom' => $_POST['prenom'],
                            'nom' => $_POST['nom'],
                            'email' => $_POST['email'],
                            'pass' => $mdp_crypt,
                            'date_naissance' => $_POST['date_naissance']
                        ));
                        echo '<p id="inscription_valide">Compte créé avec succès! Bienvenu dans la secte!<br>
                        Tu peut te <a href="index.php" id="connection_inscription">Connecter</a></p>';
                        $registration->closeCursor();
                    }
                }
                else {
                    echo '<p class="erreur_formulaire">L\'e-mail n\'est pas valide...</p> ';
                }
            }
            else {
                echo '<p class="erreur_formulaire">Tes mots de passes ne correspondent pas... Concentre toi!</p>';
            }
        }
        else {
            echo '<p class="erreur_formulaire">Tu n\'a pas cocher la case politique d\'utilisation des données!</p>';
        }
    }
    ?>


            <input type="text" name="prenom" id="prenom" placeholder="Prénom">
            <input type="text" name="nom" id="nom" placeholder="Nom de famille"><br>
            <input type="text" name="email" id="email" placeholder="Entre ton e-mail"><br>
            <input type="password" name="pass" class="pass" placeholder="Nouveau mot de passe"><br>
            <input type="password" name="pass2" class="pass" placeholder="Retape ton mot de passe"><br>
            <label for="date_naissance" id="date">Date de naissance <img src="img/interogation.png" title="Ta date de naissance quoi ! Fait pas le crétin..." id="img_date"></label><br>
            <input type="date" name="date_naissance" id="date_naissance"><br>
            <p id="conditions_formulaire">
            En cliquant sur S'inscrire, vous acceptez nos <a href="conditions/conditions_generales.html">Conditions générales</a>. Découvrez comment nous recueillons, utilisons et partageons vos données en lisant notre <a href="conditions/politique_utilisation_donnee.html">Politique d'utilisation des données</a>.<br>
            Biensur, et je tiens à le préciser au cas où... Ce site (dans son intégralité), ces conditions générales et sa politique d'utilisation des données sont a but purement humoristique. Je ne vais pas revendre vos données. Vos mots de passes sont crypter avant d'être stocker. <br>
            Vous pouvez poster ce que vous voulez a condition de respecter les autres utilisateurs. Voila, amusez vous bien et n'hésitez pas a fouiller un peut partout, plusieurs easter eggs se cache sur le site.
            </p><br>
            <label for=""></label>
            <input type="checkbox" name="valid_donnee" id="conditions_formulaire"><label for="valid_donnee" id="conditions_formulaire">en cochant cette case vous acceptez notre <a href="conditions/politique_utilisation_donnee.html">Politique d'utilisation des données</label><br>
            <input type="submit" value="S'inscrire" id="btn_inscription">

        </form>
    </div>
    
</body>
</html>
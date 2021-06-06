<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/connection.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Fessebouc - Connexion ou inscription</title>
</head>
<body>
    <div id="page">
        <div id="conteneur_connection">
            <div id="conteneur_presentation">
                <h1>fessebouc</h1>
                <p>Avec fessebouc c'est gratuit, car c'est vous le produit.</p>
            </div>
            <div id="conteneur_formulaire">
        <?php
            require 'connection_bdd.php';

            if (isset($_COOKIE['email']) && $_COOKIE['pass']) {
                $pass_query = $bdd->query('SELECT id, pass, prenom FROM membres WHERE email="'. $_COOKIE['email'] .'"');
                $pass = $pass_query->fetch();

                if (!$pass) {
                    echo 'Mauvais e-mail.';
                }
                else {
                    if ($_COOKIE['pass'] == $pass['pass']) {
                        session_start();
                        $_SESSION['id'] = $pass['id'];
                        header('Location: mur.php');
                    }
                    $pass_query->closeCursor();
                }
            }
            else {

                if (isset($_POST['email']) && isset($_POST['pass'])) {
        
                    $pass_query = $bdd->query('SELECT id, pass, prenom FROM membres WHERE email="'. $_POST['email'] .'"');
                    $pass = $pass_query->fetch();
        
                    if (!$pass) {
                        echo '<p class="erreur">Mauvais e-mail.</p>';
                    }
                    else {
                        $pass_correct = password_verify($_POST['pass'], $pass['pass']);
                        if ($pass_correct) {
                            session_start();
                            $_SESSION['id'] = $pass['id'];
                            if (isset($_POST['connection_auto'])) {
                                setcookie('email', $_POST['email'], time() + 365*24*3600, null, null, false, true);
                                setcookie('pass', $pass['pass'], time() + 365*24*3600, null, null, false, true);
                            }
                            header('Location: mur.php');
                            $pass_query->closeCursor();
                        }
                        else {
                            echo '<p class="erreur">Mauvais mot de passe.</p>';
                        }
                    }
                }
            }
        ?>
                <div id="connection">
                    <form method="post">
                        <input type="text" name="email" id="email" placeholder="Entre ton e-mail"><br>
                        <input type="password" name="pass" id="pass" placeholder="Mot de passe"><br>
                        <input type="submit" value="Connexion" id="btn_connection"><br>
                        <label for="connection_auto" id="connection_auto">Connexion automatique</label>
                        <input type="checkbox" name="connection_auto" id="case_connect_auto"><br>
                        <p id="pass_oubli_conteneur"><a href="conditions/oublie_mdp.html" id="pass_oubli">Mot de passe oublié ?</a></p><br>
                    </form>
                </div>
                <div id="inscription">
                    <p id="btn_compte"><a href="inscription.php" id="btn_creer_compte">Créer un compte</a><p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
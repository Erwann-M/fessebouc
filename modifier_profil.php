<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/modif_profil.css">
    <link rel="icon" type="image/png" href="img/fessebouc_logo2.png" size="20x20">
    <title>Modifier le profil | Fessebouc</title>
    <script type="text/javascript">
        
        // creation d'une fonction pour compter le nombre de caractères restant dans un textarea afin de limité le nombre de caracteres max.
            function maxlength_textarea(id, crid, max)
            {
                var txtarea = document.getElementById(id);
                document.getElementById(crid).innerHTML=max-txtarea.value.length;
                txtarea.onkeypress=function(){eval('v_maxlength("'+id+'","'+crid+'",'+max+');')};
                txtarea.onblur=function(){eval('v_maxlength("'+id+'","'+crid+'",'+max+');')};
                txtarea.onkeyup=function(){eval('v_maxlength("'+id+'","'+crid+'",'+max+');')};
                txtarea.onkeydown=function(){eval('v_maxlength("'+id+'","'+crid+'",'+max+');')};
            }
            function v_maxlength(id, crid, max)
            {
                var txtarea = document.getElementById(id);
                var crreste = document.getElementById(crid);
                var len = txtarea.value.length;
                if(len>max)
                {
                    txtarea.value=txtarea.value.substr(0,max);
                }
                len = txtarea.value.length;
                crreste.innerHTML=max-len;
            }
        
        </script>
</head>
<body>

    <?php
    require 'connection_bdd.php';

    require 'elements/nav_bar.php';
    ?>

    <div id="modif_profil">
        <h1>Modification du profil</h1>
            <?php
            $profil_pic = $bdd->query('SELECT profil_pic FROM membres WHERE id = '. $_SESSION['id'] .'');
            $profil_pic_u = $profil_pic->fetch();
            ?>
        <img src="profil_pic/<?php echo $profil_pic_u['profil_pic'] ?>" id="profile">
        <?php
            $nom_prenom = $bdd->query('SELECT prenom, nom FROM membres WHERE id='. $_SESSION['id'] .'');
            $nom_prenom_membre = $nom_prenom->fetch();
        ?>
        <h2><?php echo $nom_prenom_membre['prenom'] . ' ' . $nom_prenom_membre['nom'] ?></h2>

        <?php
            if (isset($_POST['description']) || isset($_POST['etudes']) || isset($_POST['habitation']) || isset($_POST['genre']) || isset($_POST['metier'])) {

                if (isset($_POST['description']) && !empty($_POST['description'])) {
                    $modif_description = $bdd->prepare('UPDATE membres SET description_u = :description_u WHERE id = :id');
                    $modif_description->execute(array(
                        'description_u' => $_POST['description'],
                        'id' => $_SESSION['id']
                    ));
                }
                if (isset($_POST['etudes']) && !empty($_POST['etudes'])){
                    $modif_etudes = $bdd->prepare('UPDATE membres SET etudes = :etudes WHERE id = :id');
                    $modif_etudes->execute(array(
                        'etudes' => $_POST['etudes'],
                        'id' => $_SESSION['id']
                    ));
                }
                if (isset($_POST['habitation']) && !empty($_POST['habitation'])) {
                    $modif_habitation = $bdd->prepare('UPDATE membres SET habitation = :habitation WHERE id = :id');
                    $modif_habitation->execute(array(
                        'habitation' => $_POST['habitation'],
                        'id' => $_SESSION['id']
                    ));
                }
                if (isset($_POST['genre']) && !empty($_POST['genre'])) {
                    $modif_genre = $bdd->prepare('UPDATE membres SET genre = :genre WHERE id = :id');
                    $modif_genre->execute(array(
                        'genre' => $_POST['genre'],
                        'id' => $_SESSION['id']
                    ));
                }
                if (isset($_POST['metier']) && !empty($_POST['metier'])) {
                    $modif_metier = $bdd->prepare('UPDATE membres SET metier = :metier WHERE id = :id');
                    $modif_metier->execute(array(
                        'metier' => $_POST['metier'],
                        'id' => $_SESSION['id']
                    ));
                }
                $verification = $bdd->query('SELECT description_u, etudes, habitation, genre, metier FROM membres WHERE id = '. $_SESSION['id'] .'');
                $verification_ok = $verification->fetch();
    
                if ($_POST['description'] == $verification_ok['description_u'] || 
                $_POST['etudes'] == $verification_ok['etudes'] || 
                $_POST['habitation'] == $verification_ok['habitation'] ||
                $_POST['metier'] == $verification_ok['metier']) {
                    
                    echo '<p id="valide">Vos informations ont bien été mise a jour !</p>';
                }
                elseif (isset($_POST['genre'])) {
                    echo '<p id="valide">Vos informations ont bien été mise a jour !</p>';
                }
                else {
                    echo '<p id="invalide">Il y as eu une erreur !</p>';
                }
            }

        ?>

        <form method="post">
            
            <textarea name="description" id="description" cols="50" rows="5" placeholder="Une petite description de toi"></textarea><br>
            <p id="compteur_text_area">Il vous reste <span id="carac_reste_textarea_1"></span> caractères.</p><br>
            <script type="text/javascript">
                
                    maxlength_textarea('description','carac_reste_textarea_1',255);
                
            </script>
            
            <input type="text" name="etudes" id="etudes" placeholder="Tu as fait des études ou tu es un naze ?"><br>

            <input type="text" name="habitation" id="habitation" placeholder="Dit nous où tu habite"><br>
            <p id="fbi">(Pour savoir ou envoyer le FBI en cas de problèmes)</p><br>

            <div id="deco_genre">

                <label for="genre" id="titre_genre">Ton genre :</label><br>
                <div id="arrangement">
                    <div class="colonnes">
                        <label for="genre" class="valeur_genre">Homme</label>
                        <input type="radio" name="genre" class="genre" value="Homme"><br>
            
                        <label for="genre" class="valeur_genre">Femme</label>
                        <input type="radio" name="genre" class="genre" value="Femme"><br>
            
                        <label for="genre" class="valeur_genre">Non défini</label>
                        <input type="radio" name="genre" class="genre" value="Non défini"><br>
            
                        <label for="genre" class="valeur_genre">Alien</label>
                        <input type="radio" name="genre" class="genre" value="Alien">
                    </div>
                    <div class="colonnes">
                        <label for="genre" class="valeur_genre">Troll</label>
                        <input type="radio" name="genre" class="genre" value="Troll"><br>
            
                        <label for="genre" class="valeur_genre">Elfe</label>
                        <input type="radio" name="genre" class="genre" value="Elfe"><br>
            
                        <label for="genre" class="valeur_genre">Nain</label>
                        <input type="radio" name="genre" class="genre" value="Nain"><br>
            
                        <label for="genre" class="valeur_genre">Gobelin</label>
                        <input type="radio" name="genre" class="genre" value="Gobelin">
                    </div>
                    <div class="colonnes">
                        <label for="genre" class="valeur_genre">Mage</label>
                        <input type="radio" name="genre" class="genre" value="Mage"><br>
            
                        <label for="genre" class="valeur_genre">Hobbit</label>
                        <input type="radio" name="genre" class="genre" value="Hobbit"><br>
            
                        <label for="genre" class="valeur_genre">Nazgûl</label>
                        <input type="radio" name="genre" class="genre" value="Nazgûl"><br>
            
                        <label for="genre" class="valeur_genre">Demi-dieu</label>
                        <input type="radio" name="genre" class="genre" value="Demi-dieu">
                    </div>
                    <div class="colonnes">
                        <label for="genre" class="valeur_genre">Jedi</label>
                        <input type="radio" name="genre" class="genre" value="Jedi"><br>
            
                        <label for="genre" class="valeur_genre">Robot</label>
                        <input type="radio" name="genre" class="genre" value="Robot"><br>
            
                        <label for="genre" class="valeur_genre">Je sais pas</label>
                        <input type="radio" name="genre" class="genre" value="Je sais pas">
                    </div>
                </div>
            </div>

            <input type="text" name="metier" id="metier" placeholder="Dit nous ton métier"><br>
            <p id="chomeur">(Tu as le droit d'écrire chomeur, n'ai pas honte, on s'en fou ici ! Mais on pensera quand même que tu est un feignant.)</p><br>

            <input type="submit" value="Valider ces informations" id="btn_valider"><br>
            <a id="retour_profil" href="profil.php?utilisateur=<?php echo $_SESSION['id'] ?>">Retour au profil</a>
        </form>
    </div>
    
</body>
</html>
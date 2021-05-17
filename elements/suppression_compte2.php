<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression du compte</title>
    <link rel="stylesheet" href="../style/suppr_compte.css">
    <link rel="icon" type="image/png" href="../img/fessebouc_logo2.png" size="20x20">
</head>
<body>

    <div class="conteneur">

        <h1>Suppression du compte</h1>
        
        <img src="../img/pleure.png" alt="">

        <h2>Es-tu VRAIMENT sûr de vouloir supprimer ton compte ?</h2>
        <p>Tu vas nous manquer, allez steuplait reste ...</p>


        <form method="post">
            <input type="submit" value="non" name="btn2" class="btn_n">
            <input type="submit" value="oui" name="btn2" class="btn_o">
        </form>

        <?php
        if (isset($_POST['btn2'])) {
            
            if ($_POST['btn2'] == "oui") {
                
                require "../connection_bdd.php";

                

            }
            elseif ($_POST['btn2'] == "non") {
                header('Location:../modifier_profil.php');
            }
        }

        ?>
    </div>


</body>
</html>
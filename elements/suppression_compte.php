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

        <?php
            if (!isset($_POST['btn'])) {
        ?>
        <img src="../img/attention.png" alt="">

        <h2>Tu es sûr de vouloir supprimer ton compte ?</h2>
        <p>Il sera supprimé définitivement et tu ne pourra jamais le récupérer ...</p>


        <form method="post">
            <input type="submit" value="oui" name="btn" class="btn_o">
            <input type="submit" value="non" name="btn" class="btn_n">
        </form>

        <?php
        }
        ?>
        <?php 

        if (isset($_POST["btn"])) {

            if ($_POST['btn'] == "oui") {
                header('Location:suppression_compte2.php');
            }
            elseif ($_POST['btn'] == "non") {
                header('Location:../modifier_profil.php');
            }
        }


        ?>
    </div>


</body>
</html>

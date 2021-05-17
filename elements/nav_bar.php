
<div id="nav_bar_fixe">

    <div id="logo_find">
        <img id="logo_navbar" src="img/fessebouc_logo1.png">
        <form method="post">
            <input type="text" name="recherche" id="recherche" placeholder="Rechercher une personne">
        </form>
    </div>

    <div id="navigation">
        <div class="nav_centre">
            <a href="mur.php" title="Accueil | Mur"><img src="img/home_logo.png" alt=""></a>
        </div>
        <div class="nav_centre">
            <a href="dernieres_photo.php" title="Images"><img src="img/image_icon.png" alt=""></a>
        </div>
        <div class="nav_centre">
            <a href="chargement.php" title="Ne clique pas ici !"><img src="img/chargement.png" alt=""></a>
        </div>
        <div class="nav_centre">
            <a href="video.php" title="Ne clique SURTOUT PAS ici"><img src="img/camera.png" alt=""></a>
        </div>
    </div>

    <div id="option">
        <a id="profil" href="profil.php?utilisateur=<?php echo $_SESSION['id'] ?>" title="Accéder a votre profil">
            <?php // Chargement photo de profil
                $profil_pic = $bdd->query('SELECT profil_pic FROM membres WHERE id = '. $_SESSION['id'] .'');
                $profil_pic_u = $profil_pic->fetch();
            ?>
            <img id="profil_pic_navbar" src="profil_pic/<?php echo $profil_pic_u['profil_pic'] ?>" alt="">
            <?php // Chargement prenom de l'utilisateur
                $prenom_membre = $bdd->query('SELECT prenom FROM membres WHERE id="'. $_SESSION['id'] .'"');
                $prenom = $prenom_membre->fetch();
            ?>
            <p id="prenom_navbar"><?php echo $prenom['prenom']?></p>
        </a>
        <nav id="option_navbar">
            <ul id="navbar-dynamique">
                <li>
                    <a href="tchat"><img src="img/mess_icon.png"></a>
                </li>
                <li>
                    <a href="notification"><img src="img/notif_icon.png"></a>
                </li>
                <li>
                    <a href="deconnection.php" title="Deconnection"><img src="img/deconnection.png"></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php
    
    ?>

</div>
<?php
    session_start();
    require('./include/PDO_connect.dp');
    $req = $bdd->prepare('SELECT * FROM user_js WHERE ID = ?');
        $req->execute(array($_SESSION['ID']));
        $tab = $req->fetch();
        if(count($tab)>0){
                $_SESSION['ID'] = $tab['ID'];

                $_SESSION['pseudo'] = $tab['Pseudo'];

                $_SESSION['nom'] = $tab['Nom'];

                $_SESSION['prenom'] = $tab['Prenom'];

                $_SESSION['theme'] = $tab['Theme'];

                $_SESSION['dateins'] = $tab['Date_inscription'];

                $_SESSION['mdp'] = $tab['mdp'];

                $_SESSION['Statut'] = $tab['Statut'];

        }

        if($_SESSION['Statut'] == 0){

        	$_SESSION['autorisation'] = "non";

        	header('location: logout.php');

        }

        else{

    if(isset($_SESSION['autorisation']) && $_SESSION['autorisation'] == "oui"){

        if(isset($_SESSION['aut_ecrire']) && $_SESSION['aut_ecrire'] == "oui"){

           

        ?>

            <!DOCTYPE html>

            <html lang="fr">

            <head>

                <meta charset="UTF-8">

                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Mon journal Secret - Ecrire</title>

                

                <link rel="stylesheet" href="css/polices.css">

                <link rel="shortcut icon" href="images/app.png" type="image/x-icon">

                <?php

                    if(isset($_SESSION['theme']) && $_SESSION['theme'] == 1){

                        echo '<link rel="stylesheet" href="css/style1.css">';

                    }

                    else if(isset($_SESSION['theme']) && $_SESSION['theme'] == 2){

                        echo '<link rel="stylesheet" href="css/style2.css">';

                    }

                    else if(isset($_SESSION['theme']) && $_SESSION['theme'] == 3){

                        echo '<link rel="stylesheet" href="css/style3.css">';

                    }

                    else if(isset($_SESSION['theme']) && $_SESSION['theme'] == 4){

                        echo '<link rel="stylesheet" href="css/style4.css">';

                    }

                    else{

                        echo '<link rel="stylesheet" href="css/style1.css">';

                    }

                ?>

                <script src="js/dateandhours.js"></script>

                <script src="js/all.js"></script>

                <script src="js/confirm.js"></script>

            </head>

            <body>

            <header>

                <div class="logo" id="logo">

                    <div class="menu" onclick="menuu()">

                        <i class="fas fa-bars" id="menu"></i>

                    </div>

                    <div class="mnlogo">

                        <div class="div1">Mon </div>

                        <div class="div2">JS</div>

                        <div class="div3"></div>

                    </div>

                </div>

                <div class="dateheure">

                    <div class="date">Initialisation...</div>

                    <div class="heure"></div>

                </div>

            </header>

            <div class="monmenu">

                <div class="header">

                    <div class="logo" id="logo2">

                        <div class="menu" onclick="menuu()">

                            <i class="fas fa-times" id="close"></i>

                        </div>

                        <div class="mnlogo">

                            <div class="div1">Mon Journal Secret</div>

                        </div>

                    </div>

                </div>

                <div class="containns">

                <div class="compte" onclick="compte()">

                        <i class="fas fa-user-circle" id="ico1"></i><div class="div">Mon compte</div>

                </div>

                <a class="ecrire" href="lire.php">

                        <i class="fas fa-book-reader" id="ico2"></i><div class="div">Lire votre JS</div>

                </a>

                <div class="them" onclick="theme()">

                    <i class="fas fa-tshirt" id="ico3"></i><div class="div">Thèmes</div>

                </div>

                <a class="deconnex" onclick="return swal({

                            title: 'Déconnexion en cours...',

                            text: 'Voulez-vous vraiment vous déconnecter ?',

                            icon: 'warning',

                            buttons: true,

                            dangerMode: false,

                            }) .then((willDelete) => {

                            if (willDelete) {

                            document.location.href='logout.php';

                            } else {

                                return false;

                            }

                            });" title="Se déconnecter"><i class="fas fa-sign-out-alt" id="ico4"></i> <div class="div">Déconnexion</div>

                        </a>

                </div>

            </div>

                <div class="containlogin2">

                <div class="login2">

                    <h1>Ecrire un journal</h1>

                    <hr class="hr">

                    <form action="auth/config.php" method="post" class="form1">

                        <label for="ecrirejs">Titre du journal :</label><br>

                        <textarea name="titre" id="ecrirejs" cols="30" rows="10" required></textarea><br>

                        <label for="ecrirejs2">Qu'est-ce qui se passe ? Qu'est-ce qui s'est passé ?</label><br>

                        <textarea name="contenu" id="ecrirejs2" cols="30" rows="10" required ></textarea><br>

                        <button type="submit" name="savewritte">Sauvegarder</button>

                    </form>

                </div>

                </div>

<div class="choixthemes">

    <i class="fas fa-times" onclick="document.querySelector('.choixthemes').classList.toggle('active2');" id="fermertheme"></i>

    <div class="choixdutheme">

        <a href="auth/config.php?provent=ecrire&choix=1">Thème Glamorphisme</a>

        <a href="auth/config.php?provent=ecrire&choix=2">Thème Clair</a>

        <a href="auth/config.php?provent=ecrire&choix=3">Thème Sombre</a>

        <a href="auth/config.php?provent=ecrire&choix=4">Thème Doux</a>

    </div>

</div>

<div class="infoscompte">

    <i class="fas fa-times" onclick="document.querySelector('.infoscompte').classList.toggle('active3');" id="fermertheme"></i>

    <div class="div">

        <a href="index.php?edit=1"><i class="fas fa-lock" id="ico1c"></i> Changer mot de passe</a>

        <a href="index.php?edit=2"><i class="fas fa-pen" id="ico2c"></i> Modifier d'autres indormations de votre compte</a>

    </div>

</div>

                        </body>

                        </html>

            <?php

            if(isset($_SESSION['aut_ecrire'])){

                unset($_SESSION['aut_ecrire']);

            }

            

    }

    else{

        header('location: index.php?getaut');

    }

     

}

else{

    header('location: index.php');

}

}

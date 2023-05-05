<?php
    session_start();
    require('../include/PDO_connect.dp');

    if(isset($_POST['signin'])){
        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['mdp'];
        $cmdp = $_POST['cmdp'];
        if((strlen($pseudo)<4 || strlen($nom)<3 || strlen($prenom)<3 || strlen($mdp)<6 || strlen($cmdp)<6) || (empty($pseudo) || empty($nom) || empty($prenom) || empty($mdp) || empty($cmdp))){
            $_SESSION['error'] = "Veuillez respecter le format de données demander.";
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['pseudo'] = $pseudo;
            header('location: ../index.php?s=signin');
        }
        else{
            if(ctype_alpha($nom) === false || ctype_alpha($prenom) === false ){
                $_SESSION['error'] = "Les smileys (emojis) ou chiffres ne sont pas autorisés dans le nom et prénom !";
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['pseudo'] = $pseudo;
            header('location: ../index.php?s=signin');
            exit;
            }
            $carac_speciaux = array("&", "~", "#", '"', "'", "{", "(", "[", "-", "|", "`", "_", "\\", "^", "@", ")", "°", "]", "+", "=", "€", "¨", "$", "£", "¤", "%", "<", ">", ",", "?", ".", ";", ":", "/", "!", "§", "*", "µ");
            $nbre_carac_spe = count($carac_speciaux);
           /* for($i=0; $i < $nbre_carac_spe; $i++){
                if(strpos($pseudo, $carac_speciaux{$i}) !== false || strpos($nom, $carac_speciaux{$i}) !== false || strpos($prenom, $carac_speciaux{$i}) !== false){
                    $_SESSION['error'] = "Les caractères spéciaux tels que (<i>&,#,/,+,</i> etc...) ne sont pas autorisés !";
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['pseudo'] = $pseudo;
                    header('location: ../index.php?s=signin');
                    exit;
                }
                else if(strpos($pseudo, ' ') !== false){
                    $_SESSION['error'] = "Les espaces ne sont pas autorisés sur le pseudo !";
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['pseudo'] = $pseudo;
                    header('location: ../index.php?s=signin');
                    exit;
                }
            }*/
            if($mdp == $cmdp){
                $req = $bdd->prepare('SELECT id FROM user_js WHERE Pseudo=?');
                $req->execute(array($pseudo));
                $tab = $req->fetchAll();
                if(count($tab)>0){
                    $_SESSION['error'] = "Ce pseudo existe déjà !<br>Veuillez choisir un autre pseudo.";
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['pseudo'] = $pseudo;
                    header('location: ../index.php?s=signin');
                }
                else{
                    $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                    $req = $bdd->prepare('INSERT INTO user_js(Pseudo, Nom, Prenom, mdp, Date_inscription) VALUES(?, ?, ?, ?, now())');
                    $req->execute(array(
                        $pseudo, $nom, $prenom, $mdp
                    ));
                    $req -> closeCursor();
                    $_SESSION['sucess'] = "Votre compte est créer avec succès, connectez-vous à présent !";
                    header('location: ../index.php');
                }
            }
            else{
                $_SESSION['error'] = "Les mots de passe ne correspondent pas !";
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['pseudo'] = $pseudo;
                header('location: ../index.php?s=signin');
            }
        }
    }
    else if(isset($_POST['login'])){
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $req = $bdd->prepare('SELECT * FROM user_js WHERE Pseudo=?');
        $req->execute(array($pseudo));
        $tab = $req->fetch();
        if(count($tab)>0){
            $dbmdp = $tab['mdp'];
            if(password_verify($mdp, $dbmdp)){
                $_SESSION['autorisation'] = "oui";
                $_SESSION['ID'] = $tab['ID'];
                $_SESSION['pseudo'] = $tab['Pseudo'];
                $_SESSION['nom'] = $tab['Nom'];
                $_SESSION['prenom'] = $tab['Prenom'];
                $_SESSION['theme'] = $tab['Theme'];
                $_SESSION['dateins'] = $tab['Date_inscription'];
                $_SESSION['mdp'] = $tab['mdp'];
                $req2 = $bdd->prepare("UPDATE user_js SET Statut = 1 WHERE ID = ?");
                $req2->execute(array($_SESSION['ID']));
                header('location: ../lire.php');
            }
            else{
                $_SESSION['error'] = "Pseudo ou mot de passe non valide !";
                header('location: ../index.php');
            }
        }
        else{
            $_SESSION['error'] = "L'utilisateur n'existe pas !";
            header('location: ../index.php');
        }
    }
    else if(isset($_POST['getaut']) || isset($_POST['getot'])){
        if(password_verify($_POST['mdp'], $_SESSION['mdp'])){
            if(isset($_POST['getaut'])){
                $_SESSION['aut_ecrire'] = "oui";
                header('location: ../ecrire.php');
            }
            else{
                $_SESSION['aut_modif'] = "oui";
                header('location: ../update.php');
            }
        }
        else{
            $_SESSION['error'] = "Mot de passe incorrect.<br>Réésayer de nouveau !";
            if(isset($_POST['getaut'])){
                header('location: ../index.php?getaut'); 
            }
            else{
                header('location: ../index.php?getot'); 
            }
        }
    }
    else if(isset($_POST['savewritte'])){
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
                $titre = htmlspecialchars($titre);
                $contenu = htmlspecialchars($contenu);
                $req = $bdd->prepare('INSERT INTO journal_secret(Titre, Contenu, Dates, Auteur) VALUES(?, ?, now(), ?)');
                $req->execute(array(
                    $titre, $contenu, $_SESSION['ID']
                ));
                $_SESSION['sucess'] = "Sauvegardé avec succès !";
                header('location: ../lire.php');
        
    }
    else if(isset($_POST['saveupdate'])){
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
                $titre = htmlspecialchars($titre);
                $contenu = htmlspecialchars($contenu);
                $req = $bdd->prepare('UPDATE journal_secret SET Titre = ?, Contenu = ? WHERE ID = ?');
                $req->execute(array(
                    $titre, $contenu, $_SESSION['update']
                ));
                $_SESSION['sucess'] = "Journal mis à jour et sauvegardé avec succès !";
                unset($_SESSION['update']);
                header('location: ../lire.php');
        
    }
    else if(isset($_GET['delete'])){
        $_SESSION['delete'] = $_GET['delete'];
        header('location: ../index.php?delete');
    }
    else if(isset($_POST['del'])){
        if(isset($_POST['mdp'])){
            if(password_verify($_POST['mdp'], $_SESSION['mdp'])){
                $req = $bdd->prepare('DELETE FROM journal_secret WHERE ID = ?');
                $req->execute(array($_SESSION['delete']));
                $_SESSION['sucess'] = "Journal supprimé avec succès !";
                unset($_SESSION['delete']);
                header('location: ../lire.php');
            }
            else{
                $_SESSION['error'] = "Le mot de passe est incorrect.<br>Veuillez rééssayer !";
                header('location: ../index.php?delete');
            }
        }
        else{
            $_SESSION['error'] = "Veuillez renseigner un mot de passe.";
            header('location: ../index.php?delete');
        }
        
    }
    else if(isset($_GET['provent']) && isset($_GET['choix'])){
        $choix = (int) $_GET['choix'];
        if(isset($_GET['param']) && !isset($_GET['value'])){
            $provent = $_GET['provent']. '.php?' .$_GET['param'];
        }
        else if(isset($_GET['param']) && isset($_GET['value'])){
            $provent = $_GET['provent']. '.php?' .$_GET['param']. '='. $_GET['value'];
        }
        else{
            $provent = $_GET['provent']. '.php?';
        }
        if($choix>=1 && $choix <=4){
            $req = $bdd->prepare('UPDATE user_js SET Theme = ? WHERE ID = ?');
            $req->execute(array($choix, $_SESSION['ID']));
            $_SESSION['theme'] = $choix;
            header('location: ../'.$provent);
        }
        else{
            header('location: ../index.php');
        }
    }
    else if(isset($_POST['modifmdp'])){
        $mdp = $_POST['mdp'];
        $newmdp = $_POST['newmdp'];
        $newmdpc = $_POST['newmdpc'];
        if(!empty($mdp) && !empty($newmdp) && !empty($newmdpc)){
            if(password_verify($mdp, $_SESSION['mdp'])){
                if(strlen($newmdp)<6 || strlen($newmdpc)<6){
                    $_SESSION['error'] = "Les mots de passes doivent faire minimum 06 caractères !";
                    header('location: ../index.php?edit=1');
                }
                else{
                    if($newmdp == $newmdpc){
                        $newmdp = password_hash($newmdp, PASSWORD_DEFAULT);
                        $req = $bdd->prepare('UPDATE user_js SET mdp = ? WHERE ID = ?');
                        $req->execute(array(
                            $newmdp, $_SESSION['ID']
                        ));
                        $_SESSION['mdp'] = $newmdp;
                        $_SESSION['sucess'] = "Mot de passe modifié avec succès !";
                        header('location: ../lire.php');
                    }
                    else{
                        $_SESSION['error'] = "Les mots de passes saisies ne correspondent pas !<br>Veuillez réésayer.";
                        header('location: ../index.php?edit=1');
                    }
                }
            }
            else{
                $_SESSION['error'] = "Votre mot de passe est incorrect !";
                header('location: ../index.php?edit=1');
            }
        }
        else{
            $_SESSION['error'] = "Veuillez remplir tous les champs !";
            header('location: ../index.php?edit=1');
        }
    }
    else if(isset($_POST['modifnp'])){
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        if(!empty($nom) || !empty($prenom)){
                $nom = htmlspecialchars($nom);
                $nom = trim($nom);
                $nom = stripslashes($nom);
                $prenom = htmlspecialchars($prenom);
                $prenom = trim($prenom);
                $prenom = stripslashes($prenom);
                if(strlen($nom)<3 || strlen($prenom)<3){
                    $_SESSION['error'] = "Les noms et prénoms doivent avoir minimum 03 caractères !";
                    header('location: ../index.php?edit=2');
                }
                else{
                    $req = $bdd->prepare('UPDATE user_js SET Nom = ?, Prenom = ? WHERE ID = ?');
                    $req->execute(array($nom, $prenom, $_SESSION['ID']));
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['sucess'] = "Vos noms ont été changés avec succès !";
                        header('location: ../index.php?edit=2');
                }
        }
        else{
            $_SESSION['error'] = "Veuillez remplir tous les champs !";
            header('location: ../index.php?edit=2');
        }
    }
    else{
        header('location: ../index.php');
    }
    
?>
>
php?edit=2');
                }
        }
        else{
            $_SESSION['error'] = "Veuillez remplir tous les champs !";
            header('location: ../index.php?edit=2');
        }
    }
    else{
        header('location: ../index.php');
    }
    
?>    
?>
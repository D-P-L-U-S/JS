<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?PHP if (isset($_GET['getaut']) || isset($_GET['getot']) || isset($_GET['delete'])) echo "Mon journal Secret - Captcha";
		else if (isset($_GET['edit']) && $_GET['edit'] == 1) echo "Mon journal Secret - Modifier mdp";
		else if (isset($_GET['edit']) && $_GET['edit'] == 2) echo "Mon journal Secret - Modifier d'autres informations";
		else if (isset($_GET['s']) && $_GET['s'] == "signin") echo "Mon journal Secret - Inscription";
		else echo "Mon journal Secret - Connexion";
		?></title>
	<?PHP include "./include/head.php" ?>
</head>
<body>
	<header>
		<div class="logo" id="logo">
			<?PHP 
			if(isset($_GET) && !empty($_GET) && !isset($_GET['s'])){ ?>
				<div class="menu" onclick="menuu()">
				<i class="fas fa-bars" id="menu"></i>
			</div>
			<?php }
			?>
			<div class="mnlogo">
				<div class="div1">
					Mon
				</div>
				<div class="div2">
					JS
				</div>
				<div class="div3"></div>
			</div>
		</div>
		<div class="dateheure">
			<div class="date">
				Initialisation...
			</div>
			<div class="heure"></div>
		</div>
	</header>
	<?PHP if (isset($_SESSION['autorisation']) && $_SESSION['autorisation'] == "oui") {
		require "./include/data.php";
		if ($_SESSION['Statut'] == 0) {
			$_SESSION['autorisation'] = "non";
			header('location: logout.php');
			die;
		}
		?>
		<div class="monmenu">
			<div class="header">
				<div class="logo" id="logo2">
					<div class="menu" onclick="menuu()">
						<i class="fas fa-times" id="close"></i>
					</div>
					<div class="mnlogo">
						<div class="div1">
							Mon Journal Secret
						</div>
					</div>
				</div>
			</div>
			<div class="containns">
				<div class="compte" onclick="compte()">
					<i class="fas fa-user-circle" id="ico1"></i><div class="div">
						Mon compte
					</div>
				</div>
				<a class="ecrire" href="ecrire.php">
					<i class="fas fa-edit" id="ico2"></i><div class="div">
						Créer un journal
					</div>
				</a>
				<a class="ecrire" href="lire.php">
					<i class="fas fa-book-reader" id="ico2"></i><div class="div">
						Lire votre JS
					</div>
				</a>
				<div class="them" onclick="theme()">
					<i class="fas fa-tshirt" id="ico3"></i><div class="div">
						Thèmes
					</div>
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
					});" title="Se déconnecter"><i class="fas fa-sign-out-alt" id="ico4"></i> <div class="div">
						Déconnexion
					</div>
				</a>
			</div>
		</div>
		<?PHP if (isset($_GET['getaut']) || isset($_GET['getot']) || isset($_GET['delete'])) {
			?>
			<div class="contain">
				<div class="login">
					<h1>Chaptcha</h1>
					<hr class="hr">
					<div class="compte">
						<i class="fas fa-user-circle" id="ico1"></i>

						<div class="divpseudo">
							@<?php echo $_SESSION['pseudo']; ?>
						</div>
						<div class="div">
							<?php echo $_SESSION['prenom']. ' ' .$_SESSION['nom']; ?>
						</div>
					</div>
					<?php
					if (isset($_SESSION['error'])) {
						echo '<div id="error">'.$_SESSION["error"].'</div>';
					} else if (isset($_SESSION['sucess'])) {
						echo '<div id="sucess">'.$_SESSION["sucess"].'</div>';
					}

					if (isset($_GET['getaut'])) {
						echo '<div class="class">Pour des raisons de sécurité, entrer de nouveau votre mot de passe pour pouvoir <b style="color: #10ee10;">écrire</b> dans votre journal !</div>';
					} else if (isset($_GET['delete'])) {
						echo '<div class="class">Pour des raisons de sécurité, entrer de nouveau votre mot de passe pour pouvoir <b style="color: #ee1010;">supprimer</b> dans votre journal !</div>';
					} else {
						echo '<div class="class">Pour des raisons de sécurité, entrer de nouveau votre mot de passe pour pouvoir <b style="color: #1010ee;">modifier</b> dans votre journal !</div>';
					}
					?>
					<form action="./auth/config.php" method="post" class="form1">
						<label for="mdp">Mot de passe :</label><br>
						<input type="password" name="mdp" id="mdp" required><br>
						<?php
						if (isset($_GET['getaut'])) {
							echo '<button type="submit" name="getaut">Confirmer</button>';
						} else if (isset($_GET['delete'])) {
							echo '<button type="submit" name="del">Confirmer</button>';
						} else {
							echo '<button type="submit" name="getot">Confirmer</button>';
						}
						?>
					</form>
					<hr class="hr2">
				</div>
			</div>
			<?PHP
		} else if (isset($_GET['edit']) && $_GET['edit'] == 1) {
			?>
			<div class="contain">
				<div class="login">
					<h1>Modifier #MDP</h1>
					<hr class="hr">
					<?php
					if (isset($_SESSION['error'])) {
						echo '<div id="error">'.$_SESSION["error"].'</div>';
					} else if (isset($_SESSION['sucess'])) {
						echo '<div id="sucess">'.$_SESSION["sucess"].'</div>';
					}
					?>
					<form action="./auth/config.php" method="post" class="form1">
						<label for="mdp">Ancien mot de passe :</label><br>
						<input type="password" name="mdp" id="Pseudo" required><br>
						<label for="newmdp">Nouveau mot de passe :</label><br>
						<input type="password" name="newmdp" id="mdp" required><br>
						<label for="newmdpc">Confirmer le mot de passe :</label><br>
						<input type="password" name="newmdpc" id="mdp" required><br>
						<button type="submit" name="modifmdp">Valider</button>
					</form>
					<hr class="hr2">
				</div>
			</div>

			<br><br><br>
			<a class="deconnex" href="logout.php?all"><i class="fas fa-sign-out-alt" id="ico4"></i> <div class="div">
				Se déconnecter de toutes les sessions
			</div>
			</a>
			<?PHP
		} else if (isset($_GET['edit']) && $_GET['edit'] == 2) {
			?>
			<div class="contain">
				<div class="login">
					<h1>Modifier Nom...</h1>
					<hr class="hr">
					<?php
					if (isset($_SESSION['error'])) {
						echo '<div id="error">'.$_SESSION["error"].'</div>';
					} else if (isset($_SESSION['sucess'])) {
						echo '<div id="sucess">'.$_SESSION["sucess"].'</div>';
					}
					?>
					<form action="./auth/config.php" method="post" class="form1">
						<label for="nom">Modifier le nom :</label><br>
						<input type="text" name="nom" id="Pseudo" required value="<?php echo $_SESSION['nom']; ?>"><br>
						<label for="prenom">Modifier le prénom :</label><br>
						<input type="text" name="prenom" id="mdp" required value="<?php echo $_SESSION['prenom']; ?>"><br>
						<button type="submit" name="modifnp">Confirmer</button>
					</form>
					<hr class="hr2">
				</div>
			</div>
			<?PHP
		} else {
			header('location: lire.php');
		}
		?>
		<div class="choixthemes">
			<i class="fas fa-times" onclick="document.querySelector('.choixthemes').classList.toggle('active2');" id="fermertheme"></i>
			<div class="choixdutheme">
				<?php
				if (isset($_GET['getaut'])) {
					echo '<a href="./auth/config.php?provent=index&choix=1&param=getaut">Thème Glamorphisme</a>
            <a href="./auth/config.php?provent=index&choix=2&param=getaut">Thème Clair</a>
            <a href="./auth/config.php?provent=index&choix=3&param=getaut">Thème Sombre</a>
            <a href="./auth/config.php?provent=index&choix=4&param=getaut">Thème Doux</a>';
				} else if (isset($_GET['delete'])) {
					echo '<a href="./auth/config.php?provent=index&choix=1&param=delete">Thème Glamorphisme</a>
            <a href="./auth/config.php?provent=index&choix=2&param=delete">Thème Clair</a>
            <a href="./auth/config.php?provent=index&choix=3&param=delete">Thème Sombre</a>
            <a href="./auth/config.php?provent=index&choix=4&param=delete">Thème Doux</a>';
				} else if (isset($_GET['getot'])) {
					echo '<a href="./auth/config.php?provent=index&choix=1&param=getot">Thème Glamorphisme</a>
            <a href="./auth/config.php?provent=index&choix=2&param=getot">Thème Clair</a>
            <a href="./auth/config.php?provent=index&choix=3&param=getot">Thème Sombre</a>
            <a href="./auth/config.php?provent=index&choix=4&param=getot">Thème Doux</a>';
				} else {
					if (isset($_GET['edit']) && $_GET['edit'] == 1) {
						?>
						<div class="choixthemes">
							<i class="fas fa-times" onclick="document.querySelector('.choixthemes').classList.toggle('active2');" id="fermertheme"></i>
							<div class="choixdutheme">
								<a href="./auth/config.php?provent=index&choix=1&param=edit&value=1">Thème Glamorphisme</a>
								<a href="./auth/config.php?provent=index&choix=2&param=edit&value=1">Thème Clair</a>
								<a href="./auth/config.php?provent=index&choix=3&param=edit&value=1">Thème Sombre</a>
								<a href="./auth/config.php?provent=index&choix=4&param=edit&value=1">Thème Doux</a>
							</div>
						</div>
						<?php
					} else if (isset($_GET['edit']) && $_GET['edit'] == 2) {
						?>
						<div class="choixthemes">
							<i class="fas fa-times" onclick="document.querySelector('.choixthemes').classList.toggle('active2');" id="fermertheme"></i>
							<div class="choixdutheme">
								<a href="./auth/config.php?provent=index&choix=1&param=edit&value=2">Thème Glamorphisme</a>
								<a href="./auth/config.php?provent=index&choix=2&param=edit&value=2">Thème Clair</a>
								<a href="./auth/config.php?provent=index&choix=3&param=edit&value=2">Thème Sombre</a>
								<a href="./auth/config.php?provent=index&choix=4&param=edit&value=2">Thème Doux</a>
							</div>
						</div>
						<?php
					}
				}
				?>
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
if (isset($_SESSION['error'])) {
	unset($_SESSION["error"]);
}
if (isset($_SESSION['sucess'])) {
	unset($_SESSION["sucess"]);
}
} else {
if (isset($_GET['s']) && $_GET['s'] == "signin") {
	?>
	<div class="contain">
		<div class="login">
			<h1>S'inscrire</h1>
			<hr class="hr">
			<?php
			if (isset($_SESSION['error'])) {
				echo '<div id="error">'.$_SESSION["error"].'</div>';
			}
			?>
			<div id="notice">
				Tous les champs sont obligatoires !
			</div>
			<form action="./auth/config.php" method="post" class="form1">
				<label for="pseude">Créer un pseudo :</label><br>
				<input type="text" name="pseudo" id="Pseudo" required minlength="4" minlenght="4" <?php
				if (isset($_SESSION['pseudo'])) {
					echo "value=\"".$_SESSION["pseudo"]."\"";
				}
				?>><br>
				<div class="nomprenom">
					<div class="nom">
						<label for="nom">Votre nom :</label><br>
						<input type="text" name="nom" id="nom" required minlength="3" minlenght="3" <?php
						if (isset($_SESSION['nom'])) {
							echo "value=\"".$_SESSION["nom"]."\"";
						}
						?>><br>
					</div>
					<div class="prenom">
						<label for="prenom">Votre prénom :</label><br>
						<input type="text" name="prenom" id="prenom" required minlength="3" minlenght="3" <?php
						if (isset($_SESSION['prenom'])) {
							echo "value=\"".$_SESSION["prenom"]."\"";
						}
						?>><br>
					</div>
				</div>
				<label for="mdp">Mot de passe :</label><br>
				<input type="password" name="mdp" id="mdp" required minlength="6" minlenght="6" value=""><br>
				<label for="cmdp">Confirmer le mot de passe :</label><br>
				<input type="password" name="cmdp" id="cmdp" required minlength="6" minlenght="6" value=""><br>
				<button type="submit" name="signin">Créer mon compte</button>
			</form>
			<hr class="hr2">
			<form action="index.php" method="post" class="form2">
				<span>Vous avez un compte ?</span>
				<a href="index.php" name="seconnecter">Se connecter</button>
			</form>
		</div>
	</div>
	<?php
	if (isset($_SESSION['error'])) {
		unset($_SESSION["error"]);
	}
	if (isset($_SESSION['pseudo'])) {
		unset($_SESSION["pseudo"]);
	}
	if (isset($_SESSION['nom'])) {
		unset($_SESSION["nom"]);
	}
	if (isset($_SESSION['prenom'])) {
		unset($_SESSION["prenom"]);
	}
} else {
	?>
	<div class="contain">
		<div class="login">
			<h1>Se connecter comme :</h1>
			<hr class="hr">
			<?php
			if (isset($_SESSION['error'])) {
				echo '<div id="error">'.$_SESSION["error"].'</div>';
			} else if (isset($_SESSION['sucess'])) {
				echo '<div id="sucess">'.$_SESSION["sucess"].'</div>';
			}
			?>
			<form action="./auth/config.php" method="post" class="form1">
				<label for="pseude">Pseudo :</label><br>
				<input type="text" name="pseudo" id="Pseudo" required><br>
				<label for="mdp">Mot de passe :</label><br>
				<input type="password" name="mdp" id="mdp" required><br>
				<button type="submit" name="login">Connexion</button>
			</form>
			<hr class="hr2">
			<form action="index.php" method="post" class="form2">
				<span>Pas encore de compte ?</span>
				<a href="index.php?s=signin" name="sinscrire">Créer un compte</button>
			</form>
		</div>
	</div>
	<?PHP
}
if (isset($_SESSION['error'])) {
	unset($_SESSION["error"]);
} else if (isset($_SESSION['sucess'])) {
	unset($_SESSION["sucess"]); ?>
</body>
</html>
<?php
}

}
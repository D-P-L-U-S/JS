<?php
session_start();
require('./include/PDO_connect.dp');
$mois = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
function pt($jpp) {
	require('./include/PDO_connect.dp');
	$jtr = $bdd->prepare('SELECT ID FROM journal_secret WHERE Auteur = ?');
	$jtr->execute(array($_SESSION['ID']));
	$jt = $jtr->rowCount();
	$jtr-> closeCursor();
	$pt = ceil($jt/$jpp);
	if ($pt >= 9) return pt($jpp+10);
	else {
		$p = ["$jt",
			"$pt",
			"$jpp"];
		return $p;
	}
}
$req = $bdd->prepare('SELECT * FROM user_js WHERE ID = ?');
$req->execute(array($_SESSION['ID']));
$tab = $req->fetch();
if ($tab != false) {
	$_SESSION['ID'] = $tab['ID'];
	$_SESSION['pseudo'] = $tab['Pseudo'];
	$_SESSION['nom'] = $tab['Nom'];
	$_SESSION['prenom'] = $tab['Prenom'];
	$_SESSION['theme'] = $tab['Theme'];
	$_SESSION['dateins'] = $tab['Date_inscription'];
	$_SESSION['mdp'] = $tab['mdp'];
	$_SESSION['Statut'] = $tab['Statut'];
}
if ($_SESSION['Statut'] == 0) {
	$_SESSION['autorisation'] = "non";
	header('location: logout.php');
} else {
	if (isset($_SESSION['autorisation']) && $_SESSION['autorisation'] == "oui") {
		$jpp = 10;
		$t = pt($jpp);
		$jt = $t[0];
		$pt = $t[1];
		$jpp = $t[2];
		if (isset($_GET['p']) AND !empty($_GET['p'] AND $_GET['p'] > 0 AND $_GET['p'] <= $pt)) {
			$_GET['p'] == intval($_GET['p']);
			$pc = $_GET['p'];
		} else {
			$pc = 1;
		}
		$depart = (($pc-1)*$jpp);
		function searchPage($id, $pt, $jpp) {
			require('./include/PDO_connect.dp');
			for($i=1; $i<=$pt; $i++){
				$depart = (($i-1)*$jpp);
				$res = $bdd->prepare('SELECT ID FROM journal_secret WHERE Auteur = ? ORDER BY ID DESC LIMIT '.$depart.','.$jpp);
				$res->execute(array($_SESSION['ID']));
				$result = $res->fetchAll();
				foreach ($result as $idd){
					if($idd['ID'] == $id) return $i;
				}
			}
		}
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Mon journal Secret - Lire</title>

			<link rel="stylesheet" href="css/polices.css">
			<link rel="shortcut icon" href="images/app.png" type="image/x-icon">
			<?php
			if (isset($_SESSION['theme']) && $_SESSION['theme'] == 1) {
				echo '<link rel="stylesheet" href="css/style1.css">';
			} else if (isset($_SESSION['theme']) && $_SESSION['theme'] == 2) {
				echo '<link rel="stylesheet" href="css/style2.css">';
			} else if (isset($_SESSION['theme']) && $_SESSION['theme'] == 3) {
				echo '<link rel="stylesheet" href="css/style3.css">';
			} else if (isset($_SESSION['theme']) && $_SESSION['theme'] == 4) {
				echo '<link rel="stylesheet" href="css/style4.css">';
			} else {
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
			<div class="body">
				<div class="access">
					<h1 class="titre">Vos journaux supplémentaires</h1>
					<?php
					$res = $bdd->prepare('SELECT ID, Titre, Contenu, Dates FROM journal_secret WHERE Auteur = ? ORDER BY rand() LIMIT 3');
					$res->execute(array($_SESSION['ID']));
					$result = $res->rowCount();
					if ($result > 0) {
						while ($trai = $res->fetch()) {
						$p = searchPage($trai['ID'], $pt, $jpp);
							?>
							<h3 class="styleh3">
								<span class="titlej">
									<a href="?p=<?php echo $p.'#'.$trai['ID']; ?>"><?php echo htmlspecialchars($trai['Titre']); ?></a>
								</span><br>
								<span class="datpu"><?php
									$date1 = new DateTime($trai['Dates']);
									$date2 = new DateTime(date('Y-m-d H:i:s'));
									$diff = $date1->diff($date2);
									$diff_jours = $diff->days;
									if ($diff_jours == 0) $dat = date('d', strtotime($a['date_create'])) == date('d') ? "Aujourd'hui à ".date('H:i', strtotime($trai['Dates'])) : "Hier à ".date('H:i', strtotime($trai['Dates']));
									else if ($diff_jours == 1) $dat = date('d', strtotime($a['date_create'])) == date('d') - 1 ? "Hier à ".date('H:i', strtotime($trai['Dates'])) : "Avant-hier à ".date('H:i', strtotime($trai['Dates']));
									else if ($diff_jours == 2) $dat = date('d', strtotime($a['date_create'])) == date('d') - 2 ? "Avant-hier à ".date('H:i', strtotime($trai['Dates'])) : 'Le '.date('d', strtotime($trai['Dates'])).', '.$mois[intval(date('n', strtotime($trai['Dates']))-1)].' '.date('Y', strtotime($trai['Dates'])).' à '.date('H:i', strtotime($trai['Dates']));
									else $dat = 'Le '.date('d', strtotime($trai['Dates'])).', '.$mois[intval(date('n', strtotime($trai['Dates']))-1)].' '.date('Y', strtotime($trai['Dates'])).' à '.date('H:i', strtotime($trai['Dates']));
									echo htmlspecialchars($dat); ?></span></h3>
							<?php
						}
					} else {
						?>
						<h3 class="styleh3 yyyy">
							<span class="titlej">
								Vous n'avez pas encore créer de journal.
							</span>
						</h3>
						<?php
					}
					$res-> closeCursor();
					?>
				</div>
				<div class="journaux">
					<?php
					if (isset($_SESSION['error'])) {
						echo '<div id="error">'.$_SESSION["error"].'</div>';
					} else if (isset($_SESSION['sucess'])) {
						echo '<div id="sucess">'.$_SESSION["sucess"].'</div>';
					}
					$res = $bdd->prepare('SELECT ID, Titre, Contenu, Dates FROM journal_secret WHERE Auteur = ? ORDER BY ID DESC LIMIT '.$depart.','.$jpp);
					$res->execute(array($_SESSION['ID']));
					$result = $res->rowCount();
					if ($result > 0) {
						while ($trai = $res->fetch()) {
							?>
							<div class="conteneur">
								<div class="titredate" id=<?php echo $trai['ID']; ?>>
									<span id="titre">
										<?php echo nl2br(htmlspecialchars($trai['Titre'])); ?>
									</span><hr class="separer">
									<span class="datee">
										<?php
										$date1 = new DateTime($trai['Dates']);
										$date2 = new DateTime(date('Y-m-d H:i:s'));
										$diff = $date1->diff($date2);
										$diff_jours = $diff->days;
										if ($diff_jours == 0) $dat = date('d', strtotime($a['date_create'])) == date('d') ? "Aujourd'hui à ".date('H:i', strtotime($trai['Dates'])) : "Hier à ".date('H:i', strtotime($trai['Dates']));
										else if ($diff_jours == 1) $dat = date('d', strtotime($a['date_create'])) == date('d') - 1 ? "Hier à ".date('H:i', strtotime($trai['Dates'])) : "Avant-hier à ".date('H:i', strtotime($trai['Dates']));
										else if ($diff_jours == 2) $dat = date('d', strtotime($a['date_create'])) == date('d') - 2 ? "Avant-hier à ".date('H:i', strtotime($trai['Dates'])) : 'Le '.date('d', strtotime($trai['Dates'])).', '.$mois[intval(date('n', strtotime($trai['Dates']))-1)].' '.date('Y', strtotime($trai['Dates'])).' à '.date('H:i', strtotime($trai['Dates']));
										else $dat = 'le '.date('d', strtotime($trai['Dates'])).', '.$mois[intval(date('n', strtotime($trai['Dates']))-1)].' '.date('Y', strtotime($trai['Dates'])).' à '.date('H:i', strtotime($trai['Dates']));
										echo 'Ecrit et posté '.htmlspecialchars($dat); ?>
									</span>
								</div>
								<div class="contenu">
									<?php echo nl2br(htmlspecialchars($trai['Contenu'])); ?>
								</div>
								<div class="ouftils">
									<a href="./auth/config.php?delete=<?php echo $trai['ID']; ?>"><i class="far fa-trash-alt" id="delete"></i><br><span>Supprimer</span></a>
									<a href="update.php?update=<?php echo $trai['ID']; ?>"><i class="fas fa-pen" id="update"></i><br><span>Modifier</span></a>
								</div>
							</div>

							<?php
							if (isset($_SESSION['error'])) {
								unset($_SESSION["error"]);
							}
							if (isset($_SESSION['sucess'])) {
								unset($_SESSION["sucess"]);
							}
						}
					} else {
						?>
						<div class="conteneur sssss">
							<div class="titredate">
								<h2>
									Commencer maintenant en :
								</h2>
							</div>
							<div class="contenu">
								<a href="ecrire.php" id="ididid"><i class="fas fa-edit" id="edzrsf"></i>Créant un journal</a>
							</div>
						</div>
						<?php
					}
					$res-> closeCursor();
					?>
				</div>
			</div>
			<div class="pagination">
				<?php
				for ($i = 1; $i <= $pt; $i++) {
					if ($i == $pc) {
						echo '<span>'.$i. '</span>';
					} else {
						?>
						<a href="lire.php?p=<?php echo $i ?>"><?php echo $i ?></a>
						<?php
					}
				}
				?>
			</div>
			<div class="choixthemes">
				<i class="fas fa-times" onclick="document.querySelector('.choixthemes').classList.toggle('active2');" id="fermertheme"></i>
				<div class="choixdutheme">
					<a href="./auth/config.php?provent=lire&choix=1">Thème Glamorphisme</a>
					<a href="./auth/config.php?provent=lire&choix=2">Thème Clair</a>
					<a href="./auth/config.php?provent=lire&choix=3">Thème Sombre</a>
					<a href="./auth/config.php?provent=lire&choix=4">Thème Doux</a>
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
}
}
if (isset($_GET['t'])) {
session_start();
$_SESSION['theme'] = $_GET['t'];
header('location: ./');
}
?>
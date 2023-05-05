<?PHP
	require('./include/PDO_connect.dp');
	$req = $bdd->prepare('SELECT * FROM user_js WHERE ID = ?');
	$req->execute(array($_SESSION['ID']));
	$tab = $req->fetch();
	if (count($tab) > 0) {
		$_SESSION['ID'] = $tab['ID'];
		$_SESSION['pseudo'] = $tab['Pseudo'];
		$_SESSION['nom'] = $tab['Nom'];
		$_SESSION['prenom'] = $tab['Prenom'];
		$_SESSION['theme'] = $tab['Theme'];
		$_SESSION['dateins'] = $tab['Date_inscription'];
		$_SESSION['mdp'] = $tab['mdp'];
		$_SESSION['Statut'] = $tab['Statut'];
	}
?>
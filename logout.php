<?php
session_start();
if(isset($_SESSION['theme'])){
    $theme = $_SESSION['theme'];
}
else{
    $theme = 1;
}
if(isset($_SESSION['ID'])){
	if(isset($_GET['all'])){
		$_SESSION['autorisation'] = "non";
	}
	if($_SESSION['autorisation'] == "non"){
		include("PDO_connect.dp");
		$req = $bdd->prepare("UPDATE user_js SET Statut = 0 WHERE ID = ?");
		$req->execute(array($_SESSION['ID']));
	}
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, null, null, false, true);
    session_regenerate_id(true);
    header('location: lire.php?t='.$theme);
}

else{
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, null, null, false, true);
    session_regenerate_id(true);
    header('location: lire.php?t='.$theme);   
} 
?>

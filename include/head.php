<link rel="stylesheet" href="css/polices.css">
<link rel="icon" href="images/app.png" />
<link rel="manifest" href="js/manifest.json" />
<script type="text/javascript" charset="utf-8">
	window.addEventListener('load', ()=> {
		if ("serviceWorker" in navigator) {
			navigator.serviceWorker.register('./js/sw.js');
		}
	});
</script>
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
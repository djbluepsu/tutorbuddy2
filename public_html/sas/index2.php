<?php
	session_start();
	if(isset($_GET['logout'])) {
		session_unset();
		session_destroy();
	}
	if (isset($_SESSION["email"])) {
		header('location:http://tutorbuddy.net/index.php');
	}
?>
<html lang= 'en'>
	<head>
		<?php
		    $pageTitle = "Maintenance";
		    //require_once("../includes/head.php");
		    require('includes/header.php');
		?>
	</head>
	<body>
		<?php
			include('./includes/minimalnav.php');
		?>

		<div class= 'container'>
			<h3>Sorry ;(</h3>
			<p>Tutorbuddy is currently under scheduled maintenance. We are trying to improve the website and will bring it back online soon. Sit tight, and have a nice day!</p>
			<img src="../images/logo.jpg" height="480" width="480" align = "right">
		</div>

	</body>
	</html>
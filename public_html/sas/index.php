<?php
	session_start();
	if(isset($_GET['logout'])) {
		session_unset();
		session_destroy();
	}
	if (isset($_SESSION["email"])) {
		header('location:http://sas.tutorbuddy.net/dashboard/');
	}
?>
<html lang= 'en'>
	<head>
		<?php
		    $pageTitle = "Home";
		    //require_once("../includes/head.php");
		    require('includes/header.php');
		?>
	</head>
	<body>
		<?php
			if ($_SESSION["email"]) {
				include('./includes/stdnav.php');
			}
			else {
				include('./includes/nav.php');
			}
		?>

		<div class= 'container'>
			<h3>Welcome to Tutorbuddy</h3>
			<p>The place to go for all your tutoring needs.</p>
			<img src="../images/logo.jpg" height="480" width="480" align = "right">
		</div>

	</body>
	</html>
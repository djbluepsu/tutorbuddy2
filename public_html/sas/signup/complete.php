<?php session_start(); 
	require('../includes/header.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Tutorbuddy - Sign Up</title>

		<link rel="stylesheet" type="text/css" href="./stylesheet.css">

		<script type="text/javascript" src="../jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="./script.js"></script>
	</head>
	<body>
		<?php
			$pageTitle= "Confirmation";
			require('../includes/header.php');
			require('../includes/nav.php');
		?>
		<p align="center">Thank you for registering with Tutorbuddy. A confirmation message has been sent to your email address. Please follow the instructions in order to activate your account.</p>
	</body>
</html>
<?php session_start(); 
	require('../includes/header.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Quizlr - Sign Up</title>

		<link rel="stylesheet" type="text/css" href="./stylesheet.css">

		<script type="text/javascript" src="../jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="./script.js"></script>
	</head>
	<body>
		<?php
			$pageTitle= "Error";
			require('../includes/header.php');
			require('../includes/nav.php');
		?>
		<p align="center">Sorry, an error occurred, click <a href="./register.php">here</a> to return to the sign-up page to try again. If the error persists, it's probably our fault, and we'd appreciate it if you would email <span style="bold">insert email here</span> to let us know. Thanks!</p>
	</body>
</html>
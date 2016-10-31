<?php
	session_start();
	
	if ($_POST['key']) {
		
		require("../includes/dbc_connect.php");
		$email = trim($_POST['email']);
		$userPassword = trim($_POST['password']);
		$userKey = trim($_POST['key']);
		$loginError = false;
		$keyError = false;
		$success = false;

		$query = "SELECT password, activation_key FROM users WHERE email = ?";
		$stmt = $dbc->prepare($query);
		$stmt->bind_param("s", $email);
		/* $stmt->bind_result($returnedId);
		$stmt->fetch(); */
		$stmt->execute();
		$stmt->bind_result($pw, $key);

		if($stmt->fetch()) {
			if(md5($userPassword) == $pw) {
				if ($userKey == $key) {
					$success = true;
					$stmt->free_result();
					$query = "UPDATE users SET active = 1 WHERE email = ?";
					$stmt = $dbc->prepare($query);
					$stmt->bind_param("s", $email);
					$stmt->execute();
				} else {
					$keyError = true;
				}
			} else {
				$loginError = true;
			}
		} else {
			$loginError = true;
		}

		$stmt->free_result();
		$dbc->close();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			$pageTitle = "Activate Account";
		require('../includes/header.php');
		?>
		<link rel="stylesheet" type="text/css" href="./stylesheet.css">

		<script type="text/javascript" src="../jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="./script.js"></script>
	</head>
	<body>
		<?php 
		require('../includes/nav.php');
		if(!$success): ?>
			<form align="center" action="activate.php" method="post" id="activateform">
				Email: <input type="text" name="email" required >

				Password: <input type="password" name="password" required >

				Activation Key: <input type="text" name="key" required >

				<input type="submit" name="submit" value="Submit"><br>
				<br>
				<?php if($loginError): ?>
					<p class="error">Incorrect email or password</p>
				<?php elseif($keyError): ?>
					<p class="error">Incorrect activation key</p>
				<?php endif; ?>
			</form>
		<?php else: ?>
			<p>Your email was successfully verified.</p>
		<?php endif; ?>
	</body>
</html>
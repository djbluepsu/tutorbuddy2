<?php
	session_start();
	$pageTitle = "Forgot password";
	require('../includes/header.php');

	require("../includes/dbc_connect.php");

	if (isset($_SESSION['email'])) {
		header('location:http://sas.tutorbuddy.net/dashboard/');
	}

	$formStage = 1;
	$errorCode = 0;
	if (isset($_POST['email'])) {
		$formStage = 2;

		$user = $_POST['email'];

		$query = "SELECT first_name FROM users WHERE email = ?";
		$stmt = $dbc->prepare($query);
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$stmt->bind_result($fName);
		if($stmt->fetch()) {

			$key = mt_rand(100000000,999999999);

			$subject = "Reset your password";
			$message = '
			Hi ' . $fName . ',<br>
			<span style="font-weight: bold;">Your security key is: ' . $key . '</span>';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Tutorbuddy <noreply@tutorbuddy.net>' . "\r\n";

			mail($user, $subject, $message, $headers);
			$stmt->free_result();

			$_SESSION['forgotUser'] = $user;
			$_SESSION['security_key'] = $key;
			$_SESSION['attemptsLeft'] = 4;
		} else {
			$stmt->free_result();
			$formStage = 1;
			$errorCode = 1;
		}
	}
	
	if (isset($_POST['securitykey'])) {
		$formStage = 3;

		$userKey = $_POST['securitykey'];

		if($userKey != $_SESSION['security_key']) {
			$formStage = 2;
			$errorCode = 2;
		}
	}
	
	if (isset($_POST['newPw1'])) {
		$formStage = 4;

		$user = $_SESSION['forgotUser'];
		$userPw = $_POST['newPw1'];
		$userPw = md5($userPw);

		$query = "UPDATE users SET password = ? WHERE email = ?";
		$stmt = $dbc->prepare($query);
		$stmt->bind_param("ss", $userPw, $user);
		$stmt->execute();
		$stmt->free_result();
		$_SESSION['forgotUser'] = null;
	}

	if($formStage == 2) {
		$_SESSION['attemptsLeft']--;
		if($_SESSION['attemptsLeft'] < 1) {
			$reload = true;
			$_SESSION['forgotUser'] = null;
		}
	}
	$dbc->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once("../includes/header.php"); ?>
	</head>
	<body>
		<!-- Include the header Jennifer made when she's done -->
		<?php 
		require('../includes/nav.php');
		if($reload): ?>
			<script type="text/javascript">window.location.assign("http://sas.tutorbuddy.net/login/forgot.php");</script>
		<?php endif; ?>

		<?php if($formStage == 1): ?>
			<form method="post" action="./forgot.php">
				Email: <input type="text" name="email" required><br>
				<?php if($errorCode == 1): echo '<p class="error">Email is not registered.</p>'; endif; ?>

				<input type="submit" name="submit" value="Submit"><br>
			</form>
		<?php endif; ?>
		<?php if($formStage == 2): ?>
			<p>A security key has been sent to the email address associated with this account. Please enter it below. Do not close your browser or refresh the page.</p>
			<p>If you have not received an email in the next few minutes, return to the login page and try again. If the problem persists, try contacting us.</p>
			<form method="post" action="./forgot.php">
				Security key: <input type="text" name="securitykey" required><br>
				<?php if($errorCode == 2): echo '<p class="error">Incorrect security key</p>'; endif; ?>
				<p>Remaining attempts: <?php echo $_SESSION['attemptsLeft']; ?></p>

				<input type="submit" name="submit" value="Submit"><br>
			</form>
		<?php endif; ?>
		<?php if($formStage == 3): ?>
			<form action="./forgot.php" onsubmit="return validatePasswordChange()" method="post">
				New password: <input type="password" name="newPw1" id="pw1" required><br>
				Confirm new password: <input type="password" name="newPw2" id="pw2" required><br>

				<input type="submit" name="submit" value="Submit"><br>
		</form>
		<?php endif; ?>
		<?php if($formStage == 4): ?>
			<p>Your password has been successfully changed. You may now refresh the page and log in.</p>
		<?php endif; ?>
	</body>
</html>
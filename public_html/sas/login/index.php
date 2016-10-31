<?php
	session_start();
	if (isset($_SESSION['email'])) {
		header('location:http://sas.tutorbuddy.net/');
	} else if (isset($_POST['email'])) {
		
		require("../includes/dbc_connect.php");
		$email = trim($_POST['email']);
		$userPassword = trim($_POST['password']);
		$loginError = false;
		$success = false;
		$activeError = false;
		$notaccount = false;

		$query = "SELECT id, first_name, last_name, password, active FROM users WHERE email = ?";
		$stmt = $dbc->prepare($query);
		$stmt->bind_param("s", $email);
		/* $stmt->bind_result($returnedId);
		$stmt->fetch(); */
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $fName, $lName, $pw, $active);

		if($stmt->num_rows == 1) {
			$stmt->fetch();
			if(md5($userPassword) == $pw) {
				if ($active == 1) {
					$success = true;
					$_SESSION['email'] = $email;
					$_SESSION['fName'] = $fName;
					$_SESSION['lName'] = $lName;
					$_SESSION['id'] = $id;
				}
				else {
					$activeError = true;
				}
			}else{
			$loginError = true;
		}
			
		}else{
			$notaccount = true;
		} 

		$stmt->free_result();
		$dbc->close();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php 
		$pageTitle = "Login";
		require("../includes/header.php");
		?>
		<link rel="stylesheet" type="text/css" href="./stylesheet.css">
		<script type="text/javascript" src="../jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="./script.js"></script>
	</head>
	<body>
		<?php 
			include('../includes/nav.php');
		if(!$success): ?>
			<div class="container">
				<form action="<?php if(isset($_GET['loc'])): echo 'http://sas.tutorbuddy.net/login/index.php?loc='.$_GET['loc']; else: echo './index.php'; endif; ?>" method="post" role="form">
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="text" class="form-control" id="email" placeholder="Enter email" name="email" required>
					</div>
					<div class="form-group">
						<label for="pwd">Password:</label>
						<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required >
					</div>
					<input type="submit" class="btn btn-success btn-sm" name="submit" value="Log in!"><br>
					<?php if($loginError){
						echo'<p class="error">Invalid email or password.</p>';
					}
						if($activeError){
						echo '<p class="error">Your email address has not yet been verified successfully. Please resolve this issue before attempting to log in again.</p>';
							}
						if($notaccount){
						echo '<p class="error">That email has not been registered.</p>';
								}			
							?>
					<a onClick="forgotPassword()" href="#">Forgot password?</a>
				</form>
			</div>
		<?php 
			else:
				if(empty($_SESSION['returnURL'])):
		?>
					<script type="text/javascript"> window.top.location.href = "http://sas.tutorbuddy.net/"; </script>
				<?php 
					else: 
						$loc = $_SESSION['returnURL'];
						$_SESSION['returnURL'] = "";
				?>
					<script type="text/javascript"> window.top.location.href = "<?php echo 'http://sas.tutorbuddy.net/'.$loc; ?>"; </script>
				<?php endif; ?>
		<?php endif; ?>
	</body>
</html>
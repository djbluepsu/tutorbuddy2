<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	if ($_SESSION['id']!=92) {
		header("Location: http://sas.tutorbuddy.net/dashboard");
	}
	$myHonorSocieties = [];
	$stmt = $dbc->prepare("SELECT hs FROM honorsocieties WHERE id = ? AND admin = 1 AND accepted=1");
	$stmt->bind_param("i", $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($hs);
	while ($stmt->fetch()) {
		$myHonorSocieties[]= $hs;
	}
	$stmt->close();

	$myHonorSocietyRequests = [];
	$stmt = $dbc->prepare("SELECT hs FROM honorsocieties WHERE id = ? AND admin= 1 AND accepted = 0");
	$stmt->bind_param("i", $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($hs);
	while ($stmt->fetch()) {
		$myHonorSocietyRequests[]= $hs;
	}
	$stmt->close();	

?>

<!DOCTYPE html>

<html lang= 'en'>
	<head>
		<?php 
			$pageTitle = "Honor Societies";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php 
			require("../includes/stdnav.php");
			require('../includes/hsnav.php');
		  ?>
		<div class= 'container'>
				<div class= "col-lg-6 text-center border">
					<h3>Honor Societies I'm An Officer Of:</h3>
					<?php
						foreach ($myHonorSocieties as $hs) {
							require("../views/honorsocietyadminblock.php");
						}
					?>

 				</div>
				<div class= "col-lg-6 text-center border">
					<h3>Honor Society Officer Requests:</h3>
						<?php
						foreach ($myHonorSocietyRequests as $hs) {
							require("../views/honorsocietyadminrequestblock.php");
						}
						?>
				 </div>
  		</div>
	</body>
 </html>
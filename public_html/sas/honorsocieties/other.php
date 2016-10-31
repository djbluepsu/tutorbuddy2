<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	if ($_SESSION['id']!=92) {
		header("Location: http://sas.tutorbuddy.net/dashboard");
	}
	$myHonorSocieties = [];
	$stmt = $dbc->prepare("SELECT hs FROM honorsocieties WHERE id = ?");
	$stmt->bind_param("i", $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($hs);
	while ($stmt->fetch()) {
		$myHonorSocieties[]= $hs;
	}
	$stmt->close();

	$otherHonorSocieties = [];
	foreach ($honorsocieties as $hs) {
		$otherHonorSocieties[] = $hs;
	}
	$otherHonorSocieties2 = array_diff($otherHonorSocieties, $myHonorSocieties);

?>

<!DOCTYPE html>

<html lang= 'en'>
	<head>
		<?php 
			$pageTitle = "Other Honor Societies";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php 
			require("../includes/stdnav.php");
			require('../includes/hsnav.php');
		  ?>
		<div class= 'container'>
				<div class= "col-lg-12 text-center border">
					<h3>Other Honor Societies</h3>
					<?php
						foreach ($otherHonorSocieties2 as $hs) {
							require("../views/otherhonorsocietyblock.php");
						}
					?>

 				</div>
				
  		</div>
	</body>
 </html>
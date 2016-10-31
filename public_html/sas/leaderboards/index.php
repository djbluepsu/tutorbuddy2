<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");

?>
<!DOCTYPE html>

<html lang= 'en'>
	<head>
		<?php 
			$pageTitle = "Leaderboards";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php require("../includes/stdnav.php");  ?>
		<h1>Top tutors in each subject:</h1>
		<ul class="list-group">
			<?php
				$total = "Total";
				$stmt = $dbc->prepare("SELECT id, time FROM tutored_hours WHERE class = ? ORDER BY time DESC");
				$stmt->bind_param("s", $total);
				$stmt->execute();
				$stmt->bind_result($id, $minutes);
				$stmt->fetch();
				$stmt->close();
				echo '<li class="list-group-item">';
				echo '<span class="category"><h4><strong>Top Overall Tutor: </strong></h4></span>';
				if ($minutes == 0) {
					echo '<h4 align="center">There is no top tutor yet.</h4>';
				}
				else{

					require('../views/displayblock.php');
				}
				echo '</li>';


				foreach ($courses as $dept => $course) {
					$stmt = $dbc->prepare("SELECT id, time FROM tutored_hours WHERE class = ? ORDER BY time+0 DESC");
					$stmt->bind_param("s", $dept);
					$stmt->execute();
					$stmt->bind_result($id, $minutes);
					$stmt->fetch();
					$stmt->close();
					echo '<li class="list-group-item">';
					echo '<span class="category"><h4><strong>Top '.$dept.' Tutor: </strong></h4></span>';
					if ($minutes == 0) {
						echo '<h4 align="center">There is no top tutor in this subject yet.</h4>';
					}
					else{

						require('../views/displayblock.php');
					}
					echo '</li>';
				}
			?>			
			</ul>
				
				
			
		<img src="../images/logo.jpg" height="480" width="480" align = "right">
	</body>

	<?php 
		$stmt-> free_result();
		$dbc->close();
	?>
 </html>
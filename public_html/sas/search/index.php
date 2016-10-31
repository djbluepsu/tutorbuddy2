<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	
	$query = trim(htmlentities($_POST['query']));
	$query2 = strtolower($query);
	$names = explode(" ", $query2 );
	$tutors = [];
	
	foreach ($names as $name) {
		$stmt = $dbc->prepare('SELECT id from users WHERE first_name LIKE ? OR last_name LIKE ?');
		$new = "%".strtolower($name)."%";
		$stmt->bind_param('ss', $new, $new);
		$stmt->execute();
		$stmt->bind_result($id);
		while($stmt->fetch()){
			$tutors[] = $id;
		}
		$stmt->close();
	}
	if(count($tutors) < 1){

		$noresult = true;
	}
	$sorted = array_unique($tutors);



?>


<html>
	<head>
		<?php
			$pageTitle = "Find a buddy";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php 
			require('../includes/stdnav.php');
			if($noresult){
				echo "<h3 align='center'>Sorry :(</h3><br>
						<p align='center'>There were no results for '".$query."'.</p>";
			}
			else{
				echo "<h3>Your results for '".$query."':</h3>";
				$counter = 0;
				foreach ($sorted as $id) {
					include('../views/searchblock.php');
					if ($counter > 8) {
						break;
					}
					$counter++;
				}
			}
			

			
		?>
	</body>
</html>
<?php $dbc->close(); ?>
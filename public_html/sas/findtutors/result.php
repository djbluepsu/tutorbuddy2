<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	$user = $_SESSION["id"];
	$rTimes = $_POST["time"];
	$class = $_POST["class"][0];
	$noresult = false;

	$tutors = [];
	//going through and getting all the users who can tutor in that class
	$stmt = $dbc->prepare("SELECT user_id FROM classes WHERE class=? AND user_id != ?");
	$stmt->bind_param("si", $class, $user);
	$stmt->execute();
	$stmt->bind_result($user);
	while ($stmt->fetch()) {
		$tutors[] = $user;
	}
	$stmt->close();
	//gets all of the users who can tutor during at least one requested time
	$timetutors = [];
	foreach ($tutors as $tutor) {
			foreach ($rTimes as $time) {
						$stmt = $dbc->prepare("SELECT user_id FROM times WHERE user_id = ? AND free_times = ?");
						$stmt->bind_param("is", $tutor, $time);
						$stmt->execute();
						$stmt->bind_result($freetutor);
						if($stmt->fetch()){
						$timetutors[] = $freetutor;
				}
						$stmt->close();
			}
	}
	$timetutors = array_unique($timetutors);


	//gets the department that they requested
	$result = "";
	foreach ($courses as $dept => $array) {
		foreach ($array as $course) {
			if ($course == $class) {
				$result = $dept;
			}
		}
	}
	//get the tutors who have the least hours in that department
	$orderedTutors = [];
	foreach ($timetutors as $tutor) {
		$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE class = ? AND id = ?");
		$stmt->bind_param("si", $result, $tutor);
		$stmt->execute();
		$stmt->bind_result($time);
		$stmt->store_result();
		if ($stmt->num_rows ==0) {
			$orderedTutors[$tutor] = 0;
		}
		else{
			$stmt->fetch();
			$orderedTutors[$tutor] = $time;
		}
		$stmt->close();
	}

	asort($orderedTutors);



	$availableTutors = [];
	foreach ($orderedTutors as $tutor => $time) {
		$stmt = $dbc->prepare("SELECT id FROM users WHERE num_slots > 0 AND id=?");
		$stmt->bind_param("i", $tutor);
		$stmt->execute();
		$stmt->bind_result($availableTutor);
		if($stmt->fetch()){
		$availableTutors[] = $availableTutor;
	}
		$stmt->close();
	}


/*
	//Removes from the list tutors who aren't free at the right time
	$query = "SELECT * FROM times WHERE user_id=? AND free_times=?";
	foreach ($tutors as $index => $tutor) {
		$remove = true;
		foreach ($times as $time) {
			$stmt = $dbc->prepare($query);
			$stmt->bind_param("is", $tutor, $time);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$remove = false;
			}
			$stmt->close();
		}
		if ($remove) {
			array_splice($tutors, $index, 1);
		}
	}

	//Removes from the list tutors who are already tutoring too many people
	foreach ($tutors as $index => $tutor) {
		$sessions = 0;
		$query = "SELECT scheduled_times.time FROM tutors INNER JOIN scheduled_times ON tutors.id = scheduled_times.relationship_id WHERE scheduled_times.accepted = 1 AND tutors.tutor_id=?";
		$stmt = $dbc->prepare($query);
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$stmt->bind_result($time);
		while ($stmt->fetch()) {
			if ($time == 'A1' || $time == 'A2' || $time == 'A3' || $time == 'A4' || $time == 'B1' || $time == 'B2' || $time == 'B3' || $time == 'B4') {
				$sessions += 2.5;
			}
			else {
				$sessions++;
			}
		}
		$stmt->close();
		$query = "SELECT num_slots FROM users WHERE id=?";
		$stmt= $dbc->prepare($query);
		$stmt->bind_param("i", $tutor);
		$stmt->execute();
		$stmt->bind_result($num_slots);
		$stmt->fetch();
		if ($sessions >= $num_slots) {
			array_splice($tutors, $index, 1);
		}
		$stmt->close();
	}
*/
	if (count($availableTutors) == 0) {
		$noresult = true;
	}
	
?>
<html>
	<head>
		<?php
			$pageTitle = "Find tutors";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php 
			require('../includes/stdnav.php');

			echo "<p>The class you requested is ";
			echo $class;
			echo ". <a href='index.php'>Edit your query.</a></p>";
			if($noresult){
				echo '<p> Sorry, there were no tutors available for '.$class.' at your selected times.</p>';

			}
			else{
				$counter = 1;
			foreach ($availableTutors as $tutor) {
				include("../views/tutorblock.php");
				$counter++;
				if ($counter > 5) {
					break;
				}
			}
		}
		?>
	</body>
</html>
<?php $dbc->close(); ?>
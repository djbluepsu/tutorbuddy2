<?php
require("../includes/sessions.php");
require("../includes/dbc_connect.php");


if ($_POST['deleteTutor']){
	$id = $_POST['deleteTutor'];
//get the tutor id
	$stmt = $dbc->prepare("SELECT tutor_id FROM tutors WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($tutor_id);
	$stmt->fetch();
	$stmt->close();

//get the tutee id
	$stmt = $dbc->prepare("SELECT tutee_id FROM tutors WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($tutee_id);
	$stmt->fetch();
	$stmt->close();

//get the scheduled times
	$times = [];
	$stmt = $dbc->prepare("SELECT time FROM scheduled_times WHERE relationship_id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($time);
	while($stmt->fetch()){
		$times[] = $time;
	}
	$stmt->close();
//loop through all of the times and add them back in to the tutor's !(and tutee's) times
	foreach ($times as $time) {
		//for tutors
		$stmt = $dbc->prepare("INSERT INTO times (user_id, free_times) VALUES (?, ?)");
		$stmt->bind_param("is", $tutor_id, $time);
		$stmt->execute();
		$stmt->close();

		//for tutees
		//$stmt = $dbc->prepare("INSERT INTO times (user_id, free_times) VALUES (?, ?)");
		//$stmt->bind_param("is", $tutee_id, $time);
		//$stmt->execute();
		//$stmt->close();
	}

//archive the relationship from tutors
	$stmt = $dbc->prepare("UPDATE tutors SET archived = 1 WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

//delete the scheduled times for the relationship

	$stmt = $dbc->prepare("DELETE FROM scheduled_times WHERE relationship_id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	$tutorDeleted = true;

}

if ($_POST['deleteTutee']){
		$id2 = $_POST['deleteTutee'];
	//get the tutor id
		$stmt = $dbc->prepare("SELECT tutor_id FROM tutors WHERE id = ?");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->bind_result($tutor_id);
		$stmt->fetch();
		$stmt->close();

	//get the tutee id
		$stmt = $dbc->prepare("SELECT tutee_id FROM tutors WHERE id = ?");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->bind_result($tutee_id);
		$stmt->fetch();
		$stmt->close();

	//get the scheduled times
		$times = [];
		$stmt = $dbc->prepare("SELECT time FROM scheduled_times WHERE relationship_id = ?");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->bind_result($time);
		while($stmt->fetch()){
			$times[] = $time;
		}
		$stmt->close();
	//loop through all of the times and add them back in to the tutor's and tutee's times
		foreach ($times as $time) {
			//for tutors
			$stmt = $dbc->prepare("INSERT INTO times (user_id, free_times) VALUES (?, ?)");
			$stmt->bind_param("is", $tutor_id, $time);
			$stmt->execute();
			$stmt->close();

			//for tutees
			$stmt = $dbc->prepare("INSERT INTO times (user_id, free_times) VALUES (?, ?)");
			$stmt->bind_param("is", $tutee_id, $time);
			$stmt->execute();
			$stmt->close();
		}

	//archive the relationship from tutors
		$stmt = $dbc->prepare("DELETE FROM tutors WHERE id = ?");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->close();

	//delete the scheduled times for the relationship

		$stmt = $dbc->prepare("DELETE FROM scheduled_times WHERE relationship_id = ?");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->close();

		$tuteeDeleted = true;

}	
			//sending a handshake
if ($_POST['sendHandshake']){
	$id2 = $_POST['sendHandshake'];
	$length = $_POST['handshake'];

	$stmt = $dbc->prepare("INSERT INTO handshakes (rel_id, time, accepted) VALUES (?, ?, 0)");
	$stmt->bind_param("ii", $id2, $length);
	$stmt->execute();
	$stmt->close();

	$handshakeSent = true;

}	
		//receiving a handshake
if ($_POST['acceptHandshake']){
	$id2 = $_POST['acceptHandshake'];
	//get the id of the tutor and the class
	$stmt = $dbc->prepare("SELECT tutor_id, class FROM tutors WHERE id = ?");
	$stmt->bind_param("i", $id2);
	$stmt->execute();
	$stmt->bind_result($tutorid, $class);
	$stmt->fetch();
	$stmt->close();
	//get the amount of time from the handshake
	$stmt = $dbc->prepare("SELECT time FROM handshakes WHERE rel_id = ? AND accepted = 0");
	$stmt->bind_param("i", $id2);
	$stmt->execute();
	$stmt->bind_result($handshake);
	$stmt->fetch();
	$stmt->close();
	//accept the handshake
	$stmt = $dbc->prepare("UPDATE handshakes SET accepted = 1 WHERE rel_id = ?");
	$stmt->bind_param("i", $id2);
	$stmt->execute();
	$stmt->close();

	//get the class minutes for the tutor
	$insert = false;
	$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE id = ? AND class = ?");
	$stmt->bind_param("is", $tutorid, $class);
	$stmt->execute();
	$stmt->bind_result($classTime);
	$stmt->store_result();
	if ($stmt->num_rows == 0){
			$insert = true;
			$classTime = 0;
	}
	else{
	$stmt->fetch();
}
	$stmt->free_result();
	$stmt->close();
	if($insert){
		$stmt = $dbc->prepare("INSERT INTO tutored_hours (id, class, time) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $tutorid, $class);
		$stmt->execute();
		$stmt->free_result();
		$stmt->close();

	}

		//get the department to credit hours
		$result = "";
		foreach ($courses as $dept => $array) {
			foreach ($array as $course) {
				if ($course == $class) {
					$result = $dept;
				}
			}
		}

		//get the department minutes for the tutor
		$insert2 = false;
		$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE id = ? AND class = ?");
		$stmt->bind_param("is", $tutorid, $result);
		$stmt->execute();
		$stmt->bind_result($deptTime);
		$stmt->store_result();
		if ($stmt->num_rows == 0){
				$insert2 = true;
				$deptTime = 0;
		}
		else{
			$stmt->fetch();
		}	
			$stmt->free_result();
			$stmt->close();
		if($insert2){
				$stmt = $dbc->prepare("INSERT INTO tutored_hours (id, class, time) VALUES (?, ?, 0)");
				$stmt->bind_param("is", $tutorid, $result);
				$stmt->execute();
				$stmt->free_result();
				$stmt->close();

		}


		//get the total minutes for the tutor
	$total = "Total";
		$insert3 = false;
		$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE id = ? AND class = ?");
		$stmt->bind_param("is", $tutorid, $total);
		$stmt->execute();
		$stmt->bind_result($totalTime);
		$stmt->store_result();
		if ($stmt->num_rows == 0){
				$insert3 = true;
				$totalTime = 0;
		}
		else{
			$stmt->fetch();
		}
			$stmt->free_result();
			$stmt->close();

		$stmt = $dbc->prepare("INSERT INTO tutored_hours (id, class, time) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $tutorid, $total);
		$stmt->execute();
		$stmt->close();

	$newClassTime = $classTime + $handshake;
	$newDeptTime = $deptTime + $handshake;
	$newTotalTime = $totalTime + $handshake;

	//adding the classTime, deptTime, and totalTime to the user's tutoring hours
	$stmt = $dbc->prepare("UPDATE tutored_hours SET time = ? WHERE id = ? AND class = ?");
	$stmt->bind_param("iis", $newClassTime, $tutorid, $class);
	$stmt->execute();
	$stmt->free_result();
	$stmt->close();

	$stmt = $dbc->prepare("UPDATE tutored_hours SET time = ? WHERE id = ? AND class = ?");
	$stmt->bind_param("iis", $newDeptTime, $tutorid, $result);
	$stmt->execute();
	$stmt->free_result();
	$stmt->close();

	$stmt = $dbc->prepare("UPDATE tutored_hours SET time = ? WHERE id = ? AND class = ?");
	$stmt->bind_param("iis", $newTotalTime, $tutorid, $total);
	$stmt->execute();
	$stmt->free_result();
	$stmt->close();

	$handshakeAccepted = true;


}	

if ($_POST['denyHandshake']){
	$id2 = $_POST['denyHandshake'];

	$stmt = $dbc->prepare("DELETE FROM handshakes WHERE rel_id = ? AND accepted = 0");
	$stmt->bind_param("i", $id2);
	$stmt->execute();
	$stmt->close();

	$handshakeDenied = true;
}

if ($_POST['withdrake']){
	$id2 = $_POST['withdrake'];

	$stmt = $dbc->prepare("DELETE FROM handshakes WHERE rel_id = ? AND accepted = 0");
		$stmt->bind_param("i", $id2);
		$stmt->execute();
		$stmt->close();

	$withdraked = true;
}


		//getting your current tutors
$rel_ids = [];
		//$stmt = $dbc->prepare('SELECT users.first_name, users.last_name, users.grade, tutors.class FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.tutee_id = ?');
$stmt = $dbc->prepare('SELECT DISTINCT tutors.id FROM tutors INNER JOIN scheduled_times ON tutors.id = scheduled_times.relationship_id WHERE tutors.tutee_id = ? AND scheduled_times.accepted = 1 AND tutors.archived = 0');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt-> bind_result($rel_id);
while($stmt->fetch()){
	$rel_ids[] = $rel_id;
}


		//getting your current tutees
$rel_ids2 = [];
		//$stmt = $dbc->prepare('SELECT users.first_name, users.last_name, users.grade, tutors.class FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.tutee_id = ?');
$stmt = $dbc->prepare('SELECT DISTINCT tutors.id FROM tutors INNER JOIN scheduled_times ON tutors.id = scheduled_times.relationship_id WHERE tutors.tutor_id = ? AND scheduled_times.accepted = 1 AND tutors.archived = 0');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt-> bind_result($rel_id2);
while($stmt->fetch()){
	$rel_ids2[] = $rel_id2;
}


?>

<!DOCTYPE html>

<html lang= 'en'>
<head>
	<?php 
	$pageTitle = "Buddies";
	require("../includes/header.php");
	?>
</head>
<body>
	<?php require("../includes/stdnav.php");  ?>
	<div class= 'container'>
		<div>
			<div class= "footer">
				<?php if($tutorDeleted) :?>
				<div class="alert alert-success">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Success!</strong> That tutor has been removed.
				</div>
			<?php endif; ?>
			<?php if($tuteeDeleted) :?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> That tutee has been removed.
			</div>
		<?php endif; ?>
			<?php if($handshakeAccepted) :?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> You have accepted the handshake.
			</div>
		<?php endif; ?>
			<?php if($handshakeSent) :?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> You have sent a handshake.
			</div>
		<?php endif; ?>
			<?php if($handshakeDenied) :?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> You have denied that handshake.
			</div>
		<?php endif; ?>
			<?php if($withdraked) :?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> You handshake has been withdrawn.
			</div>
		<?php endif; ?>
					<!-- <div class= "col-lg-6 text-center btn btn-default btn-warning pull-down">
						<a href="http://tutorbuddy.app:8000/findtutors"><h3>Get tutors!</h3></a>
					</div>
					<div class= "col-lg-6 text-center btn btn-default btn-info pull-down">
						<a href="../registration/?step=1"><h3>Sign up to be a tutor!</h3></a>
					</div> -->
				</div>
				<div class= "col-lg-6 text-center border">
					<h3>Your tutors</h3>
					<?php if(count($rel_ids) ==0){
						echo '<p>You do not have any tutors.</p>';
					}
					else{
						foreach ($rel_ids as $rel_id) {
							include('../views/yourtutorblock.php');
						}


					} ?>

				</div>
				<div class= "col-lg-6 text-center border">
					<h3>Your tutees</h3>
					<?php if(count($rel_ids2) ==0){
						echo '<p>You are not tutoring anybody yet.</p>';
					}
					else{
						foreach ($rel_ids2 as $rel_id2) {
							include('../views/yourtuteeblock.php');
						}
					} ?>
				</div>
				
			</div>
		</div>
	</body>
	</html>
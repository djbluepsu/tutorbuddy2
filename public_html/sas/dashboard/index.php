<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");

	if ($_POST["tutorRequest"]) {
		$tutor = $_POST["tutorRequest"];
		$tutee = $_SESSION['id'];
		if (strlen($_POST['class'][0]) == 1) {
		$class = $_POST['class'];
	}else{
		$class = $_POST['class'][0];
	}
		$requestedTimes = $_POST["time"];

		$stmt = $dbc->prepare("INSERT INTO tutors(tutor_id, tutee_id, class) VALUES (?, ?, ?)");
		$stmt->bind_param("iis", $tutor, $tutee, $class);
		$stmt->execute();
		$postID = $dbc->insert_id;
		$stmt->close();

		$times = [];
		foreach ($requestedTimes as $time) {
			$stmt = $dbc->prepare("INSERT INTO scheduled_times(relationship_id, time, accepted) VALUES (?, ?, 0)");
			$stmt->bind_param("is", $postID, $time);
			$stmt->execute();
			$stmt->close();
			$times[] = $time;
		}

		$stmt = $dbc->prepare("SELECT email, first_name FROM users WHERE id = ?");
		$stmt->bind_param("i", $tutor);
		$stmt->execute();
		$stmt->bind_result($email, $fname);
		$stmt->fetch();
		$stmt->close();

		$subject = "New tutoring request";
		$message = 'Hi ' . $fname . ",\n".'
		You have received a tutoring request from <strong>'.$_SESSION['fName'].' '.$_SESSION['lName'].'</strong> to tutor in <strong>'.$class.'</strong> during these times:'."\n"."<ul>";
		foreach ($times as $time) {
			$message.= "<li><strong>".$time."</strong></li>";
		}
		$message .= "</ul> \n <p>Please check your <a href='http://sas.tutorbuddy.net'><strong>dashboard</strong></a> and respond to this request.</p>";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Tutorbuddy <noreply@tutorbuddy.net>' . "\r\n";

		mail($email, $subject, $message, $headers);


		$requestSent = true;
		//DELETE FROM tutors WHERE tutee_id=? AND class=? AND tutor_id!=?

	}
	//withdrawing request
	if ($_POST['withdrawRequest']){
		$id = $_POST['withdrawRequest'];
		$stmt = $dbc->prepare("DELETE FROM tutors WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		$stmt = $dbc->prepare("DELETE FROM scheduled_times WHERE relationship_id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		$requestWithdrawn = true;

	}
		
		if ($_POST['rejectRequest']){
			$id2 = $_POST['rejectRequest'];
			$stmt = $dbc->prepare("DELETE FROM tutors WHERE id = ?");
			$stmt->bind_param("i", $id2);
			$stmt->execute();
			$stmt->close();

			$stmt = $dbc->prepare("DELETE FROM scheduled_times WHERE relationship_id = ?");
			$stmt->bind_param("i", $id2);
			$stmt->execute();
			$stmt->close();

			$requestRejected = true;

		}	

			if ($_POST['acceptRequest']){
				$id2 = $_POST['acceptRequest']; //relationship_id in scheduled_times, id in tutors
				//getting the tutor_id
				$stmt = $dbc->prepare("SELECT tutor_id, tutee_id, class FROM tutors WHERE id = ?");
				$stmt->bind_param("i", $id2);
				$stmt->execute();
				$stmt->bind_result($tutor_id, $tutee_id, $class);
				$stmt->fetch();
				$stmt->close();


				$acceptedTimes = [];
				foreach ($_POST['time'] as $time) {

					$stmt = $dbc->prepare('UPDATE scheduled_times SET accepted = 1 WHERE relationship_id = ? AND time = ?');
					$stmt->bind_param("is",$id2, $time);
					$stmt->execute();
					$stmt->close();
					
					
					$stmt = $dbc->prepare('DELETE FROM times WHERE user_id = ? AND free_times = ?');
					$stmt->bind_param("is", $tutor_id, $time);
					$stmt->execute();
					$stmt->close();
				

					
					$stmt = $dbc->prepare('DELETE FROM times WHERE user_id = ? AND free_times = ?');
					$stmt->bind_param("is", $tutee_id, $time);
					$stmt->execute();									
					$stmt->close();
						

					$acceptedTimes[] = $time;
					}
				$stmt = $dbc->prepare("DELETE FROM scheduled_times WHERE relationship_id = ? AND accepted = 0");
				$stmt->bind_param("i", $id2);
				$stmt->execute();
				$stmt->close();
				

				$stmt = $dbc->prepare("SELECT email, first_name FROM users WHERE id = ?");
				$stmt->bind_param("i", $tutee_id);
				$stmt->execute();
				$stmt->bind_result($email, $fname);
				$stmt->fetch();
				$stmt->close();

				$subject = "Your request has been accepted";
				$message = 'Hi ' . $fname . ",\n".'
				<strong>'.$_SESSION['fName'].' '.$_SESSION['lName'].'</strong> has accepted your request to tutor in <strong>'.$class.'</strong> during these times:'."\n"."<ul>";
				foreach ($acceptedTimes as $time) {
					$message.= "<li><strong>".$time."</strong></li>";
				}
				$message .= "</ul> \n <p>Happy tutoring!</p>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Tutorbuddy <noreply@tutorbuddy.net>' . "\r\n";

				mail($email, $subject, $message, $headers);
				$acceptRequest = true;

			}	

		//getting your requests for other tutors
		$rel_ids = [];
		//$stmt = $dbc->prepare('SELECT users.first_name, users.last_name, users.grade, tutors.class FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.tutee_id = ?');
		$stmt = $dbc->prepare('SELECT DISTINCT tutors.id FROM tutors INNER JOIN scheduled_times ON tutors.id = scheduled_times.relationship_id WHERE tutors.tutee_id = ? AND scheduled_times.accepted = 0 AND tutors.archived = 0');
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$stmt-> bind_result($rel_id);
		while($stmt->fetch()){
			$rel_ids[] = $rel_id;
		}

		//getting your requests for you from tutees
		$rel_ids2 = [];
		//$stmt = $dbc->prepare('SELECT users.first_name, users.last_name, users.grade, tutors.class FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.tutee_id = ?');
		$stmt = $dbc->prepare('SELECT DISTINCT tutors.id FROM tutors INNER JOIN scheduled_times ON tutors.id = scheduled_times.relationship_id WHERE tutors.tutor_id = ? AND scheduled_times.accepted = 0 AND tutors.archived = 0');
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
			$pageTitle = "Dashboard";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php require("../includes/stdnav.php");  ?>
		<div class= 'container'>
			<div>
				<div class= "footer">
					<?php if($requestSent) :?>
						<div class="alert alert-success">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>Success!</strong> Your request has been sent.
						</div>
					<?php endif; ?>
					<?php if($requestWithdrawn) :?>
						<div class="alert alert-success">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>Success!</strong> Your request has been withdrawn.
						</div>
					<?php endif; ?>
					<?php if($requestRejected) :?>
						<div class="alert alert-success">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>Success!</strong> You have declined that request.
						</div>
					<?php endif; ?>
					<?php if($acceptRequest) :?>
						<div class="alert alert-success">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>Success!</strong> You have accepted that request.
						</div>
					<?php endif; ?>
					<!-- <div class= "col-lg-6 text-center btn btn-default btn-warning pull-down">
						<a href="http://tutorbuddy.app:8000/findtutors"><h3>Get tutors!</h3></a>
					</div>
					<div class= "col-lg-6 text-center btn btn-default btn-info pull-down">
						<a href="../registration/?step=1"><h3>Sign up to be a tutor!</h3></a>
					</div> -->
					<a class= "col-lg-6 text-center btn btn-default btn-warning pull-down" href="http://sas.tutorbuddy.net/findtutors">
						<h3>Get tutors!</h3>
					</a>
					<a class= "col-lg-6 text-center btn btn-default btn-info pull-down" href="../registration/?step=1">
						<h3>Edit tutoring info!</h3>
					</a>
				</div>
				<div class= "col-lg-6 text-center border">
					<h3>Your requests</h3>
					<?php 
  					if (count($rel_ids) > 0){
  						foreach ($rel_ids as $rel_id) {
  							//$requestAccepted  = false;
  							
  							include('../views/requestblock.php');
  						}
  				 	}else{
  				 			echo '<p>You have not requested any tutors.</p>';

  				 	}
  				 	 ?>

 				</div>
				<div class= "col-lg-6 text-center border">
					<h3>Tutor requests</h3>
  					<?php if(count($rel_ids2) ==0){
  						echo '<p>You have not been requested to tutor yet.</p>';
  					}
  					else{
  						foreach ($rel_ids2 as $index => $rel_id2) {
  					  		include('../views/requestforyoublock.php');
  					  	}
  					} ?>
 				</div>
				
  			</div>
  		</div>
	</body>
 </html>
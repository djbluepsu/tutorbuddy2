<?php
	//$rel_id is passed on
	//get the name, grade, class, and tutor id
	$stmt = $dbc->prepare("SELECT users.first_name, users.last_name, users.grade, tutors.class, tutors.tutor_id FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.id =?");
	$stmt->bind_param("i", $rel_id);
	$stmt->execute();
	$stmt->bind_result($fName, $lName, $grade, $class, $tutor);
	$stmt->fetch();
	$stmt->close();

	$hasHandshake = false;
	$stmt = $dbc->prepare("SELECT time FROM handshakes WHERE rel_id = ? AND accepted = 0");
	$stmt->bind_param("i", $rel_id);
	$stmt->execute();
	$stmt->bind_result($handshake);
	$stmt->store_result();
	if($stmt->num_rows != 0){
		$hasHandshake = true;
	}
	$stmt->fetch();

	$stmt->close();

	$times= [];
	$stmt = $dbc->prepare('SELECT time FROM scheduled_times WHERE relationship_id = ?');
	$stmt->bind_param("i", $rel_id);
	$stmt->execute();
	$stmt->bind_result($time);
	while($stmt->fetch()){
		$times[] = $time;

	}
	$stmt->close();
?>


<div class='well'>
	<h3><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$tutor.'"';?>> <?php echo $fName." ".$lName; ?></a></h3>
	<p><span class='category'>Grade: </span><?php echo $grade ?></p>
	<form method="post" action="../buddies/index.php">
		<!-- <select class="c-select" name="time_select"> -->
			<h4>Class: <?php echo $class; ?></h4>
			<?php 
				$var = "";
				foreach ($times as $time) {
					$var.=$time;
					$var.=", ";
				}
					$var= substr($var, 0, -2);
					echo '<h5> Times: '.$var.'</h5>';
			?>
			<?php if($hasHandshake): ?>
			<button type= "submit" value= <?php echo '"'.$rel_id.'"'?> name= "acceptHandshake">Accept handshake for <?php echo minToHr($handshake); ?></button>
			<button type= "submit" value= <?php echo '"'.$rel_id.'"'?> name= "denyHandshake">Deny handshake</button>
			<?php else:?>
			<p>Handshake not yet received.</p>
		<?php endif; ?>
			<button type="submit" value=<?php echo '"'.$rel_id.'"'; ?> onclick="return confirm('Are you sure?');" name="deleteTutor">Delete Tutor</button>
		<!-- </select> -->


	</form>	
</div>
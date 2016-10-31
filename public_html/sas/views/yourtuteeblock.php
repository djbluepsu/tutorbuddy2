<?php
	//$rel_id2 is passed on

	$stmt = $dbc->prepare("SELECT users.first_name, users.last_name, users.grade, tutors.class, tutors.tutee_id FROM users INNER JOIN tutors ON users.id = tutors.tutee_id WHERE tutors.id =?");
	$stmt->bind_param("i", $rel_id2);
	$stmt->execute();
	$stmt->bind_result($fName, $lName, $grade, $class, $tutee);
	$stmt->fetch();
	$stmt->close();


	$handshakeSent = false;
	$stmt = $dbc->prepare("SELECT time FROM handshakes WHERE rel_id = ? AND accepted = 0");
	$stmt->bind_param("i", $rel_id2);
	$stmt->execute();
	$stmt->bind_result($handshake);
	$stmt->store_result();
	if($stmt->num_rows != 0){
		$handshakeSent = true;
	}
	$stmt->fetch();
	$stmt->close();

	$times= [];
	$stmt = $dbc->prepare('SELECT time FROM scheduled_times WHERE relationship_id = ?');
	$stmt->bind_param("i", $rel_id2);
	$stmt->execute();
	$stmt->bind_result($time);
	while($stmt->fetch()){
		$times[] = $time;

	}
	$stmt->close();
?>


<div class='well'>
	<h3><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$tutee.'"';?>> <?php echo $fName." ".$lName; ?></a></h3>
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
			<?php if(!$handshakeSent):?>
			<label for="handshake">Send handshake:</label>
			<input type="number" name="handshake" class="form-control" id="handshake" value='10' min='10' max='120' step= "5">
			<button type= "submit" value=<?php echo '"'.$rel_id2.'"'?> name= "sendHandshake">Send handshake</button>
			<?php else: ?>
			<p>Handshake already sent.</p>
			<button type= "submit" value= <?php echo '"'.$rel_id2.'"'?> name= "withdrake">Withdraw handshake for <?php echo minToHr($handshake); ?></button>
			<?php endif; ?>	

	
			<button type="submit" value=<?php echo '"'.$rel_id2.'"'; ?> onclick="return confirm('Are you sure?');" name="deleteTutee">Delete Tutee</button>
		<!-- </select> -->


	</form>	
</div>
<?php
	//$rel_id2 is passed on

	$stmt = $dbc->prepare("SELECT users.first_name, users.last_name, users.grade, tutors.class, tutors.tutee_id FROM users INNER JOIN tutors ON users.id = tutors.tutee_id WHERE tutors.id =?");
	$stmt->bind_param("i", $rel_id2);
	$stmt->execute();
	$stmt->bind_result($fName, $lName, $grade, $class, $tutee);
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
	<form method="post" action="../dashboard/index.php">
		<!-- <select class="c-select" name="time_select"> -->
			<h4>Class: <?php echo $class; ?></h4>
			<button type="submit" value=<?php echo '"'.$rel_id2.'"'; ?> onclick="return confirm('Are you sure?');" name="rejectRequest">Reject Request</button>
			<div class="btn-group">
			<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown">Times
			<span class="caret"></span>
			</button>
			<ul class="multiselect-container dropdown-menu">
			<?php 
				foreach ($times as $time) {
					echo '<li><a tabindex="0"><label class="checkbox"><input type="checkbox" name="time[]" value="'.$time.'"> '.$time.' </label></a></li>';

				}

			?>
			</ul></div>
			<button type="submit" value=<?php echo '"'.$rel_id2.'"'; ?> name="acceptRequest" onclick="return validateCheckbox('time');">Accept Request</button>
		<!-- </select> -->


	</form>	
</div>
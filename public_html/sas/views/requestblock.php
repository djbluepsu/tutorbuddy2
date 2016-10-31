<?php
	//$rel_id is passed on

	$stmt = $dbc->prepare("SELECT users.first_name, users.last_name, users.grade, tutors.class, tutors.tutor_id FROM users INNER JOIN tutors ON users.id = tutors.tutor_id WHERE tutors.id =?");
	$stmt->bind_param("i", $rel_id);
	$stmt->execute();
	$stmt->bind_result($fName, $lName, $grade, $class, $tutor);
	$stmt->fetch();
	$stmt->close();

	$times= [];
	$requestAccepted  = false;
	$stmt = $dbc->prepare('SELECT time, accepted FROM scheduled_times WHERE relationship_id = ?');
	$stmt->bind_param("i", $rel_id);
	$stmt->execute();
	$stmt->bind_result($time, $accepted);
	while($stmt->fetch()){
		$times[] = $time;
		if ($accepted == 1) {
			$requestAccepted = true;
		}
	}
	$stmt->close();
?>

<?php if(!$requestAccepted): ?>
<div class='well'>
	<h3><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$tutor.'"';?>> <?php echo $fName." ".$lName; ?></a></h3>
	<p><span class='category'>Grade: </span><?php echo $grade ?></p>
	<form method="post" action="../dashboard/index.php">
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
			<button type="submit" value=<?php echo '"'.$rel_id.'"'; ?> onclick="return confirm('Are you sure?');" name="withdrawRequest">Withdraw Request</button>
		<!-- </select> -->


	</form>	
</div>
<?php endif; ?>
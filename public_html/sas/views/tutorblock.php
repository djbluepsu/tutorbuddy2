<?php
	$stmt = $dbc->prepare("SELECT first_name, last_name, grade FROM users WHERE id=?");
	$stmt->bind_param("i", $tutor);
	$stmt->execute();
	$stmt->bind_result($fName, $lName, $grade);
	$stmt->fetch();
	$stmt->close();

?>


<div class='well'>
	<h3><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$tutor.'"';?>> <?php echo $fName." ".$lName; ?></a></h3>
	<p><span class='category'>Grade: </span><?php echo $grade ?></p>
	<form method="post" action="../dashboard/index.php" onsubmit="return validateCheckbox('time');">
		<!-- <select class="c-select" name="time_select"> -->
			<?php
				$commonTimes = [];
				echo '<div class="btn-group">
						<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown">Times
							<span class="caret"></span>
						</button>
						<ul class="multiselect-container dropdown-menu">';
				//echo '<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown">
				//	<span class="caret">Times</span>
				//</button>';
				//echo '<ul class="multiselect-container dropdown-menu">';
				//$arrayName = ["Wendesday after school", "A4", "A1"];
				foreach($rTimes as $time){
					$stmt = $dbc->prepare("SELECT * FROM times WHERE user_id=? AND free_times=?");
					$stmt->bind_param("is", $tutor, $time);
					$stmt->execute();
					$stmt->store_result();

					if ($stmt->num_rows > 0) {
						$commonTimes[] = $time;
						echo '<li><a tabindex="0"><label class="checkbox"><input type="checkbox" name="time[]" value="'.$time.'"> '.$time.' </label></a></li>';
					}
					$stmt->close();
				}
				echo "</ul></div>";
			?>
			<input type="hidden" name="class" value=<?php echo '"'.$class.'"'; ?>>
			<button type="submit" value=<?php echo '"'.$tutor.'"'; ?> name="tutorRequest">Request Tutor</button>
		<!-- </select> -->


	</form>	
</div>
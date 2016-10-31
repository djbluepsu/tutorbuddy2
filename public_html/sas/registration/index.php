<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	if ($_GET["step"] != 1 && $_GET["step"] != 2 ) {
		header("location:http://sas.tutorbuddy.net/404.shtml");
	}
	$user = $_SESSION["id"];

	//$numslots = 1;
	if ($_POST["freeSubmit"]) {
		$stmt = $dbc->prepare("DELETE FROM times WHERE user_id = ?");
		$stmt->bind_param("i", $user);
		$stmt->execute();
		$stmt->close();
		$query = "INSERT INTO times(user_id, free_times) VALUES(?, ?)";
		foreach ($_POST['free'] as $time) {
			$stmt = $dbc->prepare($query);
			$stmt->bind_param("is", $user, $time);
			$stmt->execute();
			$stmt->close();
		}
		$stmt = $dbc->prepare("UPDATE users SET num_slots=? WHERE id = ?");
		$stmt->bind_param("ii", $_POST['num_slots'], $user);
		$stmt->execute();
		$stmt->close();
		$timesSubmitted = true;
	}
	$stmt = $dbc->prepare("SELECT num_slots FROM users WHERE id = ?");
	$stmt->bind_param("i", $user);
	$stmt->execute();
	$stmt->bind_result($numslots);
	$stmt->fetch();
	$stmt->close();
	if ($_POST["courseSubmit"]) {
		$stmt = $dbc->prepare("DELETE FROM classes WHERE user_id = ?");
		$stmt->bind_param("i", $user);
		$stmt->execute();
		$stmt->close();
		$query = "INSERT INTO classes(user_id, class) VALUES(?, ?)";
		foreach ($_POST['course'] as $course) {
			$stmt = $dbc->prepare($query);
			$stmt->bind_param("is", $user, $course);
			$stmt->execute();
			$stmt->close();
		}
		$coursesSubmitted = true;
	}
?>
<html>
	<head>
		<?php
			$pageTitle = "Register";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php 
			require("../includes/stdnav.php"); 
		?>
		<form class="form-inline" role="form" method="post" action=<?php ($_SERVER['PHP_SELF']); ?>>
			<div class="footer">

			<?php if($coursesSubmitted) :?>
				<div class="alert alert-success">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Success!</strong> Your course information has been submitted.
				</div>
			<?php endif; ?>
			<?php if($timesSubmitted) :?>
				<div class="alert alert-success">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Success!</strong> Your available times have been submitted.
				</div>
			<?php endif; ?>
			</div>

			<h1 class= "align-center">Tutoring Registration Form</h1>


			<div class="form-group" class="m-x-auto">
				<?php if ($_GET["step"] == 1): ?>
				<legend>Select your available times</legend>
				<div>


					<?php
						foreach ($times as $category => $array) {
							echo '<div class="btn-group">
									<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown"> '.$category.'
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">';
									foreach ($array as $time) {
										$stmt = $dbc->prepare("SELECT * FROM times WHERE user_id=? AND free_times=?");
										$stmt->bind_param("is", $user, $time);
										$stmt->execute();
										$stmt->store_result();
										echo '<li><a tabindex="0"><label class="checkbox"><input type="checkbox" name="free[]" value="'.$time.'"';
										if ($stmt->num_rows > 0) {
											echo " checked";
										}
										echo '> '.$time.' </label></a></li>';
										$stmt->close();
									}
							echo '</ul>
								</div>';
						}
					?>
				<br>
				<label for="numsessions" class="control-label">How many sessions can you tutor in one week?</label>
				<input type="number" name="num_slots" class="form-control" id="num_slots" value=<?php echo '"'.$numslots.'"'; ?> min='1' step= "1">
				<br>
				<button type="submit" class="btn btn-primary" name="freeSubmit" value="submit">Submit</button>
				<br><br>
				<a href="index.php?step=2" class="btn btn-success">Courses</a>
				<?php elseif ($_GET["step"] == 2): ?>
				<legend>Select Subjects</legend>	
				<div>
					<span>Select the Courses You Are Able to Teach:</span>
					<br>

					<?php
						foreach ($courses as $subject => $array) {
							echo '<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" id="subjects" multiple="multiple" data-toggle= "dropdown"> '.$subject.'
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">';
									foreach ($array as $course) {
										$stmt = $dbc->prepare("SELECT * FROM classes WHERE user_id=? AND class=?");
										$stmt->bind_param("is", $user, $course);
										$stmt->execute();
										$stmt->store_result();
										echo '<li><a tabindex="0"><label class="checkbox"><input type="checkbox" name="course[]" value="'.$course.'"';
										if ($stmt->num_rows > 0) {
											echo " checked";
										}
										echo '> '.$course.'</label></a></li>';
										$stmt->close();
									}
							echo '</ul>
								</div>';
						}
					?>
			</div>
			<br>
			<button type="submit" class="btn btn-primary" name="courseSubmit" value="submit">Submit</button>
			<br><br>
			<a href="index.php?step=1" class="btn btn-success">Free Times</a>
			<?php endif; ?>


		<br>
		<div class="form-group">
			<!--<button type="submit" class="btn btn-primary" name="submit">Submit</button>-->
		</div>
		</div>
		</form>
					
	</body>
</html>
<?php $dbc->close(); ?>
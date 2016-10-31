<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	$otherUser = false;
	$user = $_SESSION["id"];
	if ($_GET["id"]) {
		if($_GET["id"] != $user){
		$user = $_GET["id"];
		$otherUser = true;
		}
	}
	$stmt = $dbc->prepare("SELECT first_name, last_name, email, grade FROM users WHERE id = ?");
	$stmt->bind_param("i", $user);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows == 0) {
		header("location:http://sas.tutorbuddy.net/404.shtml");
	}
	$stmt->bind_result($fname, $lname, $email, $grade);
	$stmt->fetch();
	$stmt->close();

?>
<!DOCTYPE html>

<html lang= 'en'>
	<head>
		<?php 
			$pageTitle = "Profile";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php require("../includes/stdnav.php");  ?>
		<h1><?php echo $fname." ".$lname; ?></h1>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="category"><h3><strong>Email:</strong></h3></span>
				<a href=<?php echo "mailto:".$email; ?>><?php echo $email; ?></a>
			</li>

			<?php
			$total = "Total";
			$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE id = ? AND class = ?");
			$stmt->bind_param("is", $user, $total);
			$stmt->execute();
			$stmt->bind_result($minutes);
			$stmt->fetch();
			$stmt->close();
			echo '<li class="list-group-item">
				<span class="category"><strong>Total time tutored: </strong></span>';
				echo minToHr($minutes);
			echo '</li>';


			foreach ($courses as $dept => $course) {
				$stmt = $dbc->prepare("SELECT time FROM tutored_hours WHERE id = ? AND class = ?");
				$stmt->bind_param("is", $user, $dept);
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows < 1) {
					$minutes = 0;
				}
				else{
				$stmt->bind_result($minutes);
			}
				$stmt->fetch();
				$stmt->close();
				echo '<li class="list-group-item">';
				echo '<span class="category"><strong>'.$dept.' time tutored: </strong></span>';
				echo minToHr($minutes);
				echo '</li>';
			}
			?>
		</ul>

		<!-- Direct request form -->
		<!-- #Harambe #JusticeForDaniel-->
<?php if ($otherUser) :?>
	 <legend>Request <?php echo $fname; ?> to tutor:</legend>
	 	<div>
		<form method="post" action="../dashboard/index.php" onsubmit="return validateForm();">
			<!-- <select class="c-select" name="time_select"> -->
				<?php
					//$fname, $lname, $grade, $email, $user passed on

				//get all the available times
				$availableTimes = [];
				$availableTimeGroups = [];
				foreach ($times as $category => $array) {
					$insert = false;
					foreach ($array as $time) {
						$stmt = $dbc->prepare("SELECT * FROM times WHERE user_id = ? AND free_times = ?");
						$stmt->bind_param("is", $user, $time);
						$stmt->execute();
						$stmt->store_result();
						if ($stmt->num_rows > 0) {
							$availableTimes[] = $time;
							$insert = true;
						}
						$stmt->close();
					}
					if ($insert) {
						$availableTimeGroups[] = $category;
					}
				}
				//get all the available classes
				$availableClasses = [];
				$availableDepts = [];
				foreach ($courses as $dept => $array) {
					$insert = false;
					foreach ($array as $class) {
						$stmt = $dbc->prepare("SELECT * FROM classes WHERE user_id = ? AND class = ?");
						$stmt->bind_param("is", $user, $class);
						$stmt->execute();
						$stmt->store_result();
						if ($stmt->num_rows > 0) {
							$availableClasses[] = $class;
							$insert = true;

						}
						$stmt->close();
					}
					if($insert){

						$availableDepts[] = $dept;
					}
				}
				if(!empty($availableTimeGroups)){
				echo "<legend>Select times:</legend>";
				
				foreach ($times as $category => $array) {
					if (in_array($category, $availableTimeGroups)) {
						
					
					echo '<div class="btn-group">
						<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown"> '.$category.'
							<span class="caret"></span>
						</button>
						<ul class="multiselect-container dropdown-menu">';
							
							foreach ($array as $time) {		
							if (in_array($time, $availableTimes)) {
									
									
								echo '<li><a data-value= "'.$time.'" href="#" ><label class="checkbox"><input type="checkbox" name="time[]" value="'.$time.'"> '.$time.' </label></a></li>';
							}
							}



							echo '</ul>
							</div>';
					}
				}
			} else {
				echo "This person is not available to tutor yet.";
		}
					echo "<hr>";
					if(!empty($availableTimeGroups)){

					echo "<legend>Select a class:</legend>";
				foreach ($courses as $subject => $classes) {
					if (in_array($subject, $availableDepts)) {
						
					
					echo '<div class="btn-group">
						<button type="button" class="btn btn-default" multiple="multiple" data-toggle="dropdown"> '.$subject.'
							<span class="caret"></span>
						</button>
						<ul class="multiselect-container dropdown-menu">';
							foreach ($classes as $class) {	
								if (in_array($class, $availableClasses)) {
												
								echo '<li><a data-value= "'.$class.'" href="#" ><label class="radio"><input type="radio" name="class[]" value="'.$class.'"> '.$class.' </label></a></li>';
								}
							}
							echo '</ul>
							</div>';
						}
					}
				}else{

					echo "This person cannot tutor in any class.";
				}
					?>
					<br>
					<br>
					<br>
				<?php if (!empty($availableDepts) && !empty($availableTimeGroups)): ?>
				<button class= "btn btn-success align-right right" type="submit" value=<?php echo '"'.$user.'"'; ?> name="tutorRequest">Request Tutor</button>
			<?php endif; ?>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			<!-- </select> -->


		</form>	
	</div>
	<?php endif; ?>
	</body>

	<?php 
		$dbc->close();
	?>
 </html>
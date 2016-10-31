<?php
	require("../includes/sessions.php");
	require("../includes/dbc_connect.php");
	$user = $_SESSION["id"];
	
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
		?>
		<form class="form-inline" action="result.php" method="post" onsubmit = "return validateForm();">
			<div class="jumbotron">
				<h1 class= "align-center">Tutoring Request Form</h1>
			</div>

			<div class="form-group" class="m-x-auto">
				<legend>Select your available times:</legend>
				<div>
					<?php
						foreach ($times as $category => $array) {
							echo '<div class="btn-group">
								<button type="button" class="btn btn-default" id="subjects" multiple="multiple" data-toggle="dropdown"> '.$category.'
									<span class="caret"></span>
								</button>
								<ul class="multiselect-container dropdown-menu">';
									foreach ($array as $time) {			
										echo '<li><a data-value= "'.$time.'" href="#" ><label class="checkbox"><input type="checkbox" name="time[]" value="'.$time.'"> '.$time.' </label></a></li>';
									}
									echo '</ul>
									</div>';
							}
					?>
				<br><br>
				<legend> Select a class:</legend>
				<?php
					foreach ($courses as $subject => $classes) {
						echo '<div class="btn-group">
							<button type="button" class="btn btn-default" multiple="multiple" data-toggle="dropdown"> '.$subject.'
								<span class="caret"></span>
							</button>
							<ul class="multiselect-container dropdown-menu">';
								foreach ($classes as $class) {			
									echo '<li><a data-value= "'.$class.'" href="#" ><label class="radio"><input type="radio" name="class[]" value="'.$class.'"> '.$class.' </label></a></li>';
								}
								echo '</ul>
								</div>';
						}
				?>
				<br><br>
			<div class="form-group">
            	<button type="submit" class="btn btn-primary">Find Tutors!</button>
        	</div>
		</form>
						
	</body>
</html>
<?php $dbc->close(); ?>
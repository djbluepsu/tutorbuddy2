<?php
	//$name is passed on
	//email is passed on
?>
<div class='well'>
	<h3><?php echo $name?></h3>
	<h4><?php echo 'Email: '.$email?></h4>
	<form method="post" action="../honorsocieties/index.php">
		<!-- <select class="c-select" name="time_select"> -->
			<button type= "submit" value=<?php echo '"'.$_SESSION['id'].'"'?> name= "Drop">Drop honor society</button>
		<!-- </select> -->
	</form>	
</div>
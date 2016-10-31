<?php
	//$name is passed on
?>
<div class='well'>
	<h3><?php echo $name?></h3>
	<form method="post" action="../honorsocieties/other.php">
		<!-- <select class="c-select" name="time_select"> -->
			<button type= "submit" value=<?php echo '"'.$_SESSION['id'].'"'?> name= "request">Request membership</button>
		<!-- </select> -->
	</form>	
</div>
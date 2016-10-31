<?php
	//$id is passed on
	$stmt = $dbc->prepare('SELECT first_name, last_name, grade FROM users WHERE id=?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($fname, $lname, $grade);
	$stmt->fetch();
	$stmt->close();
?>
<div class="well"><p align="left"><strong><h3 align="left"><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$id.'"';?>> <?php echo $fname." ".$lname; ?></a></h3></strong></p>
<p align="left">Grade: <?php echo $grade ?></p>
</div>
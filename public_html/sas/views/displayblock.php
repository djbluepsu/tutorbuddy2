<?php
	//$id, $minutes passed on
	$stmt = $dbc->prepare("SELECT first_name, last_name, grade FROM users WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($fname, $lname, $grade);
	$stmt->fetch();
	$stmt->close();
?>

<p align="center"><strong><h4 align="center"><a href=<?php echo '"http://sas.tutorbuddy.net/profile/?id='.$id.'"';?>> <?php echo $fname." ".$lname; ?></a></h4></strong></p>
<p align="center">Grade: <?php echo $grade ?></p>
<p align="center"> Time tutored: <?php echo minToHr($minutes)?></p>
<?php
	$dbhostname="localhost";
	$dbusername="tutorbuddy";
	$dbpassword="hello123!";
	$dbname="tutorbuddy";
	
	$dbc = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname);
	if($dbc->connect_errno > 0) {
		die ('Could not connect to MySQL ' . $dbc->connect_error);
	}
?>
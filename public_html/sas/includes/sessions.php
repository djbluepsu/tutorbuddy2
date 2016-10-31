<?php
	
	session_start();

	if(!isset($_SESSION['email'])) {
		$loc = $_SERVER['REQUEST_URI'];
		$_SESSION['returnURL'] = $loc;
		header("location: http://sas.tutorbuddy.net/login");
	} else {
		$email = $_SESSION['email'];
	}

	$json = file_get_contents("http://sas.tutorbuddy.net/includes/courses.json");
	//$courses = json_decode($string, true);
	$courses = json_decode($json, true);
	//var_dump($courses);

	$json = file_get_contents("http://sas.tutorbuddy.net/includes/times.json");
	$times = json_decode($json, true);

	$json = file_get_contents("http://sas.tutorbuddy.net/includes/honorsocieties.json");
	$honorsocieties = json_decode($json, true);

	function minToHr($minutes) {
		if ($minutes < 60) {
			return $minutes." minutes";
		}
		elseif ($minutes == 60) {
			return "1 hour";
		}
		elseif ($minutes%60 == 0) {
			$hours = (int)$minutes/60;
			return $hours." hours";
		}
		elseif ($minutes < 120) {
			return (int)($minutes/60)." hour ".($minutes%60)." minutes";
		}
		else {
			$hours = (int) $minutes/60;
			$leftoverMin = $minutes%60;
			if ($leftoverMin > 0) {
				return (int)$hours." hours ".$leftoverMin." minutes";
			}
			else {
				return $hours." hours";
			}
		}
	}

	function getRowname($colname){
		$substr = substr($colname, 0,2);
		switch ($substr) {
			case 'mi':
				return "Top overall tutor: ";
				break;
			case 'ss':
				return "Top social studies tutor: ";
				break;
			case 'ma':
				return "Top math tutor: ";
				break;
			case 'sc':
				return "Top science tutor: ";
				break;
			case 'ch':
				return "Top Chinese tutor: ";
				break;
			case 'fr':
				return "Top French tutor: ";
				break;
			case 'ja':
				return "Top Japanese tutor: ";
				break;
			case 'sp':
				return "Top Spanish tutor: ";
				break;
			case 'en':
				return "Top English tutor: ";
				break;
			case 'ar':
				return "Top art tutor: ";
				break;
	
		}
		

	}

	function getSubjectHours($dept){
			switch ($dept) {
				case 'Mathematics':
					return "math_minutes";
					break;
				case 'Science':
					return "science_minutes";
					break;
				case 'Arts':
					return "art_mminutes";
					break;
				case 'Social Studies':
					return "ss_minutes";
					break;
				case 'Chinese':
					return "chinese_minutes";
					break;
				case 'French':
					return "french_minutes";
					break;
				case 'Spanish':
					return "spanish_minutes";
					break;
				case 'Japanese':
					return "japanese_minutes";
					break;
				case 'English':
					return "english_minutes";
					break;
			}



	}
?>
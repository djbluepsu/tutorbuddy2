function validatePasswordChange() {

	// checks the passwords are of proper langth and matching
	var pw1 = document.getElementById("pw1").value;
	var pw2 = document.getElementById("pw2").value;

	if(pw1.length < 5) {
		alert("Passwords must be at least 5 characters long");
		return false;
	}

	var passwordMatch = pw1 === pw2;
	if(!passwordMatch) {
		alert("Your passwords do not match");
		return false;
	}

	return true;
}

function forgotPassword() {
	window.location.assign("http://sas.tutorbuddy.net/login/forgot.php");
}
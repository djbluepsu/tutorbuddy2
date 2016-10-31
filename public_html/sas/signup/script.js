function validateForm() {
	
	// checks that the email is syntactically valid
	var userEmail = document.getElementById("emailinput").value;
	var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

	var validEmail = regex.test(userEmail);
	if(!validEmail) {
		alert("Please enter a valid email address, you entered " + userEmail);
		return false;
	}

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
/*
	if (document.getElementById('newschoolbutton').checked) {
		if (!document.getElementById('schooltext').value) {
			alert("Please enter a school name");
			return false;
		}
	}
	*/
	/*
	var username = document.getElementById("usernameinput").value;

	if(username.length < 5) {
		alert("Usernames must be at least 5 characters");
		return false;
	}

	regex = /^[a-zA-Z0-9._-]+$/;
	var validUsername = regex.test(username);
	if(!validUsername) {
		alert("Usernames may only contain letters, numbers, periods, hyphens, and underscores");
		return false;
	}

	var disallowedUsernames = [".php", ".php1", ".php2", ".php3", ".php5", ".html", ".xhtml", "html", ".phtml", ".asp", ".aspx", "default_profile"];

	for (var i = 0; i < disallowedUsernames.length; i++) {
		var len = disallowedUsernames[i].length;
		var str = username.substring(username.length-len,username.length);
		if(disallowedUsernames[i] === str) {
			alert("Usernames may not contain " + disallowedUsernames[i]);
			return false;
		}
	}

	return true;
	*/
}
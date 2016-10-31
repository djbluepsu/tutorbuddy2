<?php
	require('../includes/sessions.php');

?>
<!DOCTYPE html>

<html lang= 'en'>
	<head>
		<?php 
			$pageTitle = "About";
			require("../includes/header.php");
		?>
	</head>
	<body>
		<?php require("../includes/stdnav.php");  ?>

<h3>What is Tutorbuddy?</h3>
<p>Tutorbuddy is a place for students to connect to their peer tutors. 
	Previously, honor societies used Google Forms, which required the officers to act as the mediators between tutors and tutees. 
	Tutorbuddy makes the entire tutoring system operate more efficiently, while at the same time tracking hours though the "handshake" system.</p>
<br>
<br>
<h3>How do I use Tutorbuddy?</h3>
<p>When you open up Tutorbuddy after signing in, you will see the dashboard. The dashboard is a main hub for several different functions. First, it allows you to see your pending requests for other tutors, as well as other requests people have for you.
You will also see two buttons: one to sign up as a tutor, and one to find other tutors. This is where you go to request tutors and enroll as a tutor in all classes. The dashboard tab can be found in the top left of the screen, right next to the Tutorbuddy logo.
</p>
<p>
	Next, there is the "Buddies" tab. When you click on this tab, you will see two different columns: one column for your tutors and one for your tutees. You will notice that there may be buttons, saying "send handshake", "accept handshake", or "decline handshake".
	The Handshake function is the hour-tracking system. It is the responsibility of the tutor to "send a handshake" to their tutee after a specified period of tutoring. The tutee must then verify this handshake by either accepting or denying the handshake. 
</p>

<p>
You will see three more tabs on the upper right side of the screen. The one with your name or username is a link to your personal profile. You can find your total hours for each subject there.
</p>
<p>When you first sign up, I heavily encourage you to sign up to be a tutor in any class that you can. Remember, just because you can tutor in many classes does not mean that people will ask you to tutor in all of those classes.
This website was designed to prioritize those who have the least tutoring hours. Also, you can choose how many hours a week you can tutor by updating the "number of slots available" field.</p>
<br>
<br>
<p>This website was designed and created by Alex Cuozzo with tremendous contributions from Soumil Mukherjee and Aryaman Tumalapalli. Special thanks to Will Mundy. It is dedicated to the teacher who continues to inspire students to pursue their passions and accomplish great things: Ms. Goode.</p>

<p> I am always looking to add new features in and improve on current ones. For now, my list of future plans is as follows(ordered by priority): 
<ol>
<li>Add in honor societies for people to enroll in</li>
<li>Add in honor society pages for societies to update their members on upcoming events and opportunities</li>
<li>Add in administration accounts and teacher accounts for said honor societies</li>
<li>Add in single session requests (for people who tutor others for short periods of time when available)</li>
<li>Add in custom tutor requests, so you can transfer existing relationships</li>
<li>Add in AJAX support (this is what Facebook uses to make the page load things like instant messages live)</li>
<li>Add in a community where you can ask and answer class-specific questions and get small amounts of service credit for each response</li>

</ol>
</p>

<p>I am always open to suggestions. Feel free to email me at cuozzo41084@sas.edu.sg or schedule a meeting with me in person. I would love to get your feedback on what is good and what needs work. Happy tutoring! </p>

<h3>Updates</h3>
<ul>
	<li><strong>10/10/2016:</strong> Added in confirmation for deleting tutoring relationships/withdrawing requests</li>
	<li><strong>10/10/2016:</strong> Improved search bar, now protects against sneaky HTML code</li>
	<li><strong>10/10/2016:</strong> Fixed errors associated with profile/index.php not working</li>
	<li><strong>10/10/2016:</strong> Fixed email confirmation errors</li>
	<li><strong>10/11/2016:</strong> Restructured database, fixed associated errors</li>
		<ul>
				<li>Allows for tracking of hours by relationship, by department, by class</li>
				<li>Allows for tracking of handshakes</li>
		</ul>
	<li><strong>10/11/2016:</strong> Improved search engine</li>
		<ul>
				<li>Shows up to 5 tutors</li>
				<li>Shows tutors who have not yet been requested</li>
		</ul>
	<li><strong>10/12/2016:</strong> Added direct tutoring requests</li>
</ul>

</body>
</html>
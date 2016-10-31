
<nav class= 'navbar navbar-default' role= 'navigation'>
	<div class= 'container'>
			<div class= 'navbar-header'>
				<a class= 'navbar-brand' href="http://sas.tutorbuddy.net"><img src="../images/Tutorbuddy.png" height="30" width="30" align = "middle"></a>
			</div>
			<div class='collapse navbar-collapse'>
				<ul class='nav navbar-nav'>
					<li><a class='navbar-brand nav navbar-nav' href='http://sas.tutorbuddy.net/about/index.php'>Tutorbuddy</a></li>
					<li><a href='http://sas.tutorbuddy.net/dashboard/index.php'>Dashboard</a></li>
					<li><a href='http://sas.tutorbuddy.net/buddies/index.php'>Buddies</a></li>
					<li><a href='http://sas.tutorbuddy.net/leaderboards/index.php'>Leaderboards</a></li>
					<form class= 'navbar-form navbar-left' method="POST" 
					action="http://sas.tutorbuddy.net/search/index.php">
						<div class='form-group'>
								<input type='text' id="query" name='query' class='form-control' placeholder= 'Find a buddy' required>
						</div>
						<button type='submit' id="search" name ="search" class='btn btn-default' value='search'>Search</button>
					</form>
					<!-- <li><a href=''>Honor Societies</a></li>
					<li><a href=''>More</a></li> -->
				</ul>
				<ul class='nav navbar-nav navbar-right'>
					<li><a href='http://sas.tutorbuddy.net/profile/index.php'><?php echo $_SESSION['fName']; ?></a></li>
					<li><a href='http://sas.tutorbuddy.net/?logout=1'>Sign out</a></li>
				</ul>
		</div>
	</div>
</nav>


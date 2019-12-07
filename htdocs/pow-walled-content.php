<!doctype html><html lang="en" dir="ltr">

	<head>
		<meta charset="utf-8">
		<script type="text/javascript" src="sha256.js"></script>
		<script type="text/javascript" src="pow-functions.js"></script>
	</head>

	<body>

		<?php
		session_start();

		if( !isset($_SESSION['pow']['solved']) || $_SESSION['pow']['solved'] != true ){

			echo '<p>Access restricted! You must solve proof of work before access this content.</p>';
			echo '<p>To solve proof of work request <a href="solve-pow.php">solve-pow.php</a></p>';

		} else {

			echo '<p>Welcome to walled garden. You see this only for you have send valid solution to proof of work problem.</p>';
			echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
			echo '<p>If you want test again you make server web forget you solve proof of work at <a href="require-new-pow.php">require-new-pow.php</a></p>';
			// you put content here

		}

		?> 
 
	</body>

</html>

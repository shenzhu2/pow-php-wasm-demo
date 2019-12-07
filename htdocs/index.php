<!doctype html><html lang="en" dir="ltr">

	<head>
		<meta charset="utf-8">
		<script type="text/javascript" src="sha256.js"></script>
		<script type="text/javascript" src="pow-functions.js"></script>
	</head>

	<body>


		<p>For solve proof of work problem, call function javascript get_pow_solution() with</p>

		<ol>
			<li>hostname - the hostname of your server <code>$_SERVER['HTTP_HOST']</code></li>
			<li>timestamp - a current iso-8601 timestamp in UTC</li>
			<li>rand - the random string put by the server and stored to <code>$_SESSION['pow']['rand']</code></li>
			<li>difficulty - the difficulty set by the server and stored to <code>$_SESSION['pow']['difficulty']</code></li>

		</ol>

		<p>Example for solve proof of work problem and send solution to server <a href="solve-pow.php">solve-pow.php</a></p>

		<p>Function javascript <code>get_pow_solution()</code> calculate solution to proof of work problem and send solution to server web to <code>submit-pow-solution.php</code>. If server web determine solution correct and customer not cheat, PHP set <code>$_SESSION['pow']['solved'] = true</code>. You check value of PHP variable session.</p>

		<p>Example for display content only if user send correct solution to proof of work problem found at <a href="pow-walled-content.php">pow-walled-content.php</a></p>

	</body>

</html>

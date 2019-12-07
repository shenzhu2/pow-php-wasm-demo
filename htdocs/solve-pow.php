<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../settings.php' );
require_once( 'pow-functions.php' );
session_start();

// hostname of server for customer pow hash input
$pow['hostname'] = $_SERVER['HTTP_HOST'];

// make random number for customer pow hash input
if( !isset($_SESSION['pow']['rand']) ){
	$_SESSION['pow']['rand'] = random_str(20);
}
$pow['rand'] = $_SESSION['pow']['rand'];

// make iso-8601 timestamp in UTC for customer pow hash input
date_default_timezone_set('UTC');
$pow['timestamp'] = date("c");

// save difficulty for prevent cheat customer
$pow['difficulty'] = $POW_DEFAULT_DIFFICULTY;
$_SESSION['pow']['difficulty'] = $pow['difficulty'];

?>
<!doctype html><html lang="en" dir="ltr">

	<head>
		<meta charset="utf-8">
		<script type="text/javascript" src="sha256.js"></script>
		<script type="text/javascript" src="pow-functions.js"></script>
	</head>

	<body>

		<noscript>
			<div class="error" id="js-disabled">
				<h3>&#9888;&nbsp; Error in Javascript</h3>

				<p>Your browser not support javascript! Please enable javascript to see demo.</p>
			</div>
		</noscript>

		<button style="display:none;" id="pow-button" onclick="get_pow_solution( '<?php echo $pow['hostname']?>', '<?php echo $pow['timestamp'] ?>', '<?php echo $pow['rand'] ?>', '<?php echo $pow['difficulty'] ?>' )">Solve Proof Of Work</button>

		<div id="output-div"><span id="output-span"></span></div>

		<script type="text/javascript">
		if( ! wasmEnabled() ){
			var wasmError = '' +
			'				<div class="error" id="js-disabled">' +
			'					<h3>&#9888;&nbsp; Error in Web Assembly</h3>' +
			'' +
			'					<p>Your browser not support web assembly! Please enable web assembly to see demo. See</p>' +
			'' +
			'					<ul>' +
			'					<li><a href="https://developer.mozilla.org/en-US/docs/WebAssembly/C_to_wasm">Mozilla Web Assembly Documentation</a></li>' +
			'					<li><a href="https://webassembly.org/roadmap/">webassembly.org</a></li>' +
			'					</ul>' +
			'				</div>';
			document.getElementById("output-span").innerHTML = wasmError;
		} else {

			var Module = {
				onRuntimeInitialized: function() {
					document.getElementById("pow-button").style.display = "block";
				}
			};
		}
		</script>
		<script type="text/javascript" src="pow_wasm.js"></script>


	</body>

</html>

<?php
session_start();

/*****************
* VALIDATE INPUT *
*****************/

// hostname
if( !isset($_POST['hostname']) || !isset($_SERVER['HTTP_HOST']) || $_POST['hostname'] != $_SERVER['HTTP_HOST'] ){
	http_response_code(400);
	die( "ERROR Bad hostname." );
}

// timestamp. ignore '+' for bugs. force UTC.
if( !isset($_POST['timestamp']) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}).00:00$/', $_POST['timestamp']) == true ){
	http_response_code(400);
	die( "ERROR Bad timestamp." );
}

// accept only solution less than 1 old hour
$timestamp = substr( $_POST['timestamp'], 0, 19 ) . "+00:00";
if( (time() - strtotime($timestamp)) > 3600 ){
	http_response_code(400);
	die( "ERROR Bad timestamp." );
}

// random string
if( !isset($_POST['rand']) || !isset($_SESSION['pow']['rand']) || $_POST['rand'] != $_SESSION['pow']['rand'] ){
	http_response_code(400);
	die( "ERROR Bad rand." );
}

// counter. expect 32-bit int
if( !isset($_POST['counter']) || !preg_match('/^[0-9]{1,10}$/', $_POST['counter']) == true ){
	http_response_code(400);
	die( "ERROR Bad counter." );
}

// difficulty
if( !isset($_POST['difficulty']) || !isset($_SESSION['pow']['difficulty']) || $_POST['difficulty'] != $_SESSION['pow']['difficulty'] ){
	http_response_code(400);
	die( "ERROR Bad difficulty." );
}

/*****************
* CHECK SOLUTION *
*****************/

$hash = hash( 'sha256', hash( 'sha256', $_POST['hostname'] . $timestamp . $_POST['rand'] . $_POST['counter'] ) );

$startString = str_repeat( '0', $_POST['difficulty'] );
if( substr( $hash, 0, $_POST['difficulty'] ) != $startString ){
	http_response_code(400);
	die( "ERROR Invalid solution." );
}

// prevent replay attack
unset( $_SESSION['pow']['rand'] );

// save valid proof of work solution for customer
$_SESSION['pow']['solved'] = true;

echo "ack\n";

?>

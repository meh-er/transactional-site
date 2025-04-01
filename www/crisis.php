<<?php
date_default_timezone_set('Europe/London');
if (session_status() === PHP_SESSION_NONE) {
    session_start(); //session may be already started
}

// Change this to your connection info.
if(isset($_SESSION['loggedin'])){
    require_once("header-in.php");
	}else{  
    require_once("header.php");
	}
include 'booking-index.php';

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'leisure-centre-booking';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$duration = 60;
$cleanup = 0;
$start = '08:00';
$end = '17:00';

function timeslots ($duration, $cleanup, $start, $end){
	$start = new DateTime($start);
	$end = new DateTime($end, null);
	$i=0;
	$interval = new DateInterval("PT".$duration."M");
	$cleanupInterval = new DateInterval("PT".$cleanup."M");
	$slots = array ();
	
	for ($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
		$endPeriod = clone $intStart;
		$endPeriod->add($interval);
		if($endPeriod>$end){
			break;
		}
		
		$slots[] = $intStart->format("H:iA"). "-". $endPeriod->format("H:iA");
			
	}
	
	return $slots;
}


if (isset($_POST['dels'])){
	$timeslot=$_POST['timeslot'];
 	$bookingDate = $_SESSION['bookingDate'];
	$userid = $_SESSION['id'];
	$mysqli = new mysqli('localhost', 'root', 'root', 'leisure-centre-booking');
 if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
	$stmt = $mysqli->prepare("DELETE FROM bookings WHERE booking_date = ? and timeslot = ? AND id = ?");
if (!$stmt) {
        die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    }
	$stmt->bind_param('ssi', $bookingDate, $timeslot, $userid);
 if (!$stmt->bind_param("ssi", $bookingDate, $timeslot, $userid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }
	$stmt->execute();
if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

	$stmt->close();
	$mysqli->close();

	$msg = "<div class='alert alert-success'>Termination Successful</div>";
}
if(isset($_POST['books'])){
	$timeslot=$_POST['timeslot'];
 	$bookingDate = $_SESSION['bookingDate'];
	$userid = $_SESSION['id'];
	$mysqli = new mysqli('localhost', 'root', 'root', 'leisure-centre-booking');
 if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
	$stmt = $mysqli->prepare("INSERT INTO bookings (booking_date, timeslot, id) VALUES (?,?,?)");
if (!$stmt) {
        die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    }
	$stmt->bind_param('ssi', $bookingDate, $timeslot, $userid);
 if (!$stmt->bind_param("ssi", $bookingDate, $timeslot, $userid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }
	$stmt->execute();
if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

	$stmt->close();
	$mysqli->close();

	$msg = "<div class='alert alert-success'>Booking Successful</div>";
}

function checkCurrentUserBooking($userId, $bookingDate, $timeslot) {
    $mysqli = new mysqli('localhost', 'root', 'root', 'leisure-centre-booking');


    // Check if the connection was successful
    if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    // Prepare the SQL statement to check if the user has booked the timeslot
$query = "SELECT * FROM bookings WHERE id = ? AND booking_date = ? AND timeslot = ?";
    $stmt = $mysqli->prepare($query);

    // Bind parameters to the prepared statement
    $stmt->bind_param('iss', $userId, $bookingDate, $timeslot);

    // Execute the prepared statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    $numRows = $result->num_rows;

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();

    // If any rows were returned, the user has made a booking for the timeslot
    return $numRows > 0;
}
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Books</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class ="container">
			<h1 class="text-center">Book Time for: <?php echo $_SESSION['bookingDate']; ?></h1><hr>
		<div class="row">
			<div class="col-md-12">
			<?php echo isset($msg)?$msg:"";?>	  
							</div>
			<?php 
$timeslots = timeslots($duration, $cleanup, $start, $end);
$processedTimeslots = array();
			foreach($timeslots as $ts){
		// Check if the current user has made a booking for this timeslot
 foreach($timeslots as $ts){
// Check if the current timeslot has already been processed
    if (in_array($ts, $processedTimeslots)) {
        continue; // Skip processing if timeslot has already been added
    }
    // Check if the current user has made

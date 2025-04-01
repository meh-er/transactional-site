<?php
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
	$end = new DateTime($end);
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
	if (!$stmt->bind_param('ssi', $bookingDate, $timeslot, $userid)) {
     die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
}
	if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

if (!$stmt = $mysqli->prepare("UPDATE users SET booking_count = booking_count - 1 WHERE id = ?")) {
        die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    }
	$stmt->bind_param('i', $userid);
 if (!$stmt->bind_param("i",$userid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }

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
	if ($_SESSION['activity_id'] == null) {
			$activityid = "0";
	} else {
	$activityid = $_SESSION['activity_id'];
	}
	$mysqli = new mysqli('localhost', 'root', 'root', 'leisure-centre-booking');
 if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
	$stmt = $mysqli->prepare("INSERT INTO bookings (booking_date, timeslot, id, activity_id) VALUES (?,?,?,?)");
		
	 if (!$stmt->bind_param("ssii", $bookingDate, $timeslot, $userid, $activityid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }

if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
	
	$stmt = $mysqli->prepare("UPDATE users SET booking_count = booking_count + 1 WHERE id=?");
	$stmt->bind_param('i', $userid);
 if (!$stmt->bind_param("i", $userid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }
	if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
if (!$stmt) {
        die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
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

// Check if the current timeslot has already been processed
    if (in_array($ts, $processedTimeslots)) {
        continue; // Skip processing if timeslot has already been added
    }
    // Check if the current user has made a booking for this timeslot
	$processedTimeslots[] = $ts;
    $isCurrentUserBooking = checkCurrentUserBooking($_SESSION['id'], $_SESSION['bookingDate'], $ts);

    // Determine the class based on whether the current user has made a booking
    $buttonClass = $isCurrentUserBooking ? "btn btn-danger delete" : "btn btn-success book";
	
			?>
			<div class="col-md-2">
				<div class="form-group">
				<button class="<?php echo $buttonClass; ?>" data-timeslot="<?php echo $ts;?>"><?php echo $ts;?>
					</button>
			</div>
				</div>
			<?php } ?>
	
	</div>
	</div>
		
		<!-- Boostrap Modal -->
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Booking:<span id="slot"></span></h4>
			  </div>
			  <div class="modal-body">
				<div class="row">
				  <div class="col-md-12">
					  <form action="" method="post">
						<div class-"form-group">
							<label for="">Timeslot</label>
							<input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
						  </div>
						  <div class="form-group pull-right">
						  	<button class="btn btn-primary" type="submit" name="books">Submit</button>
							<button class="btn btn-primary" type="submit" name="dels">Delete</button>
						  </div>
					  </form>
					</div>
				  </div>
			  </div>
			</div>
		  </div>
		</div>
		<script>
		 $(".book, .delete").on("click", function() {
			var timeslot = $(this).attr("data-timeslot");
			$("#slot").html(timeslot);
			$("#timeslot").val(timeslot);
			$("#myModal").modal("show");
			$()

		 })
		
		</script>
	</body>
</html>
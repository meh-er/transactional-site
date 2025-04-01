<!doctype html>
<html>
<head>
    <link href="calendar.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<?php
include 'Calendar.php';
include 'Booking.php';
include 'BookableCell.php';
//include 'times.php';
//include "users.php"; // this needs to go somewhere else
	

$booking = new Booking(
    'leisure-centre-booking',
    'localhost',
    'root',
    'root'
);

$bookableCell = new BookableCell($booking);
	

$calendar = new Calendar();
$calendar->attachObserver('showCell', $bookableCell);
$bookableCell->routeActions();
echo $calendar->show();

if (isset($_POST['activity_id'])) {
    $_SESSION['activity_id'] = $_POST['activity_id'];
}

?>
</body>
</html>
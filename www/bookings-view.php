<?php
// session_start();
/*if (!isset($_SESSION['loggedin'])) {
	header('Location: users.php');
	exit;
}

*/
session_start();
if(isset($_SESSION['loggedin'])){
    require_once("header-in.php");
}else{  
    require_once("header.php");
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'leisure-centre-booking';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
/*
$stmt = $con->prepare('SELECT booking_date, timeslot, activity_id FROM bookings WHERE id = ? GROUP BY id');
		// In this case we can use the account ID to get the account info.
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$stmt->bind_result($booking_date, $timeslot, $activity);
		$stmt->fetch();
		$stmt->close();
*/
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Bookings</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
		<div class="content">
			<h2>My Bookings</h2>
			<div>
				<p>Your booking details are below:</p>
				<table>
<tr>
						<th>Date</th>
						<th>Time</th>
					</tr>
<?php
$connection = mysqli_connect('localhost', 'root', 'root', 'leisure-centre-booking');

$query = "SELECT booking_date, timeslot FROM `bookings` WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $_SESSION['id']);
//$stmt->bind_result($bookingdate, $timeslot);
$stmt->execute();
$query_run = $stmt->get_result();

while ($row = $query_run->fetch_assoc()) {

while ($row = mysqli_fetch_array($query_run))
{
    echo "<tr>";
    echo "<td>" . $row['booking_date'] . "</td>";
    echo "<td>" . $row['timeslot'] . "</td>";
    echo "</tr>";
}
}
				echo "</table>";
?>
			</div>
		</div>
	</body>
</html>
<?php
date_default_timezone_set('Europe/London');
/*
$mysqli = new mysqli('localhost', 'root', 'root', 'leisure-centre-booking');
$stmt = $mysqli->prepare('select * from bookings where MONTH(date) = ? AND YEAR (date) = ?');
$stmt->bind_param('ss', $month, $year);
$bookings = array();
if($stmt->execute()){
	$result=$stmt->get_result();
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$bookings[]=$row['date'];
		}
		$stmt->close();
	}
}

function build_calendar($month, $year){
	$daysOfWeek = array('Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); //array of days of the week
	$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year); // get first day of month
	$numberDays = date('t', $firstDayOfMonth); // no. days this month contains
	$dateComponents = getdate($firstDayOfMonth); //info of first date
	$monthName = $dateComponents['month']; //name of month
	$dayOfWeek = $dateComponents['wday']; //index value of first day of month
	$dateToday = date('Y-m-d'); //get current date
	
	$prev_month = date('m', mktime(0, 0, 0, $month-1, 1, $year));
	$prev_year = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
	$next_month = date('m', mktime(0, 0, 0, $month+1, 1, $year));
	$next_year = date('Y', mktime(0, 0, 0, $month+1, 1, $year));
	$calendar = "<center><h2>$monthName $year</h2>";
	$calendar.= "<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."'>Prev Month</a>";
	$calendar.= "<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a>";
	
	$calendar.= "<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=$next_year'>Next Month</a></center>";
	$calendar= "<br><table class='table table-bordered'>";
	$calendar.= "<tr>";
	foreach($daysOfWeek as $day) {
		$calendar.="<th class='header'>$day</th>";
	}

	$calendar.= "<tr></tr>";
	$currentDay = 1; //Initiate day counter
	if($dayOfWeek > 0) {
		for($k=0;$k<$dayOfWeek;$k++){
			$calendar.="<td></td>";
		}
	}
	$month = str_pad($month, 2, "0", STR_PAD_LEFT); // get month number
	while($currentDay <= $numberDays){
		//if 7th column (sunday) reached, start a new row
		if($dayOfWeek == 7){
			$dayOfWeek = 0;
			$calendar.= "</tr><tr>";
		}
		
		$currentDayRel = str_pad($month, 2, "0", STR_PAD_LEFT);
		$date = "$year-$month-$currentDayRel";
		$dayName = strtolower(date(
		'I',strtotime($date)));
		$today = $date==date('Y-m-d')?'today':'';
		$calendar.="<td class='$today'><h4>$currentDayRel</h4></td>";
		$currentDay++;
		$dayOfWeek++;
	}

	
	//last week of the month
	if($dayOfWeek < 7){
		$remainingDays = 7-$dayOfWeek;
		for($i=0;$i<$remainingDays;$i++){
			$calendar.= "<td></td>";
		}
	}
	$calendar.="</tr>";
	$calendar.= "</table>";
	
	return $calendar;
}
?>


<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<style>
		table{
			table-layout:  fixed;
		}
		th {
			positio: absolute;
			top: -9999px;
			left: -9999px;
		}
		tr {
			border: 1px solid #ccc;
		}
		td{
			width: 33%;
		}
		.today{
			background: yellow;
		}
	
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<?php
				$dateComponents = getdate();
			if(isset($_GET['month']) && isset($_GET['year'])){
				$month = $_GET['month'];
				$year = $_GET['year'];
			}else{
				$month = $dateComponents['mon'];
				$year = $dateComponents['year'];
			}
			echo build_calendar($month, $year);
			?>
	
			</div>
		</div>
	</div
</body>
</html>

<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'leisure-centre-booking';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
	
?>
<!doctype html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title>Booking Form</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<form class="Equipment" method="post" action="Equipment</h1>
			<div class="fields">
				<!-- Input Elements -->
 <div class="wrapper">
	<div>
		<label for="date">Date</label>
		<div class="field">
			<input id="date" type="date" name="date" required>
		</div>
	</div>
	<div class="gap"></div>
	<div>
		<label for="time">Time</label>
		<div class="field">
			<input id="time" type="time" name="time" required>
		</div>
	</div>
</div>
<div class="wrapper">
	<div>
	<div id="Location" onclick=javascript:showresponddiv(this.id)>
		</div>
		<label for="Location">Location</label>
		<div class="field">
			<select id="Location" name="Location" required>
				<option disabled selected value="">--</option>
				<option value="Court">Court</option>
				<option value="Gym">Gym</option>
				<option value="Swimming Pool">Swimming Pool</option>
			</select>		</div>
	</div>
	<div class="gap"></div>
	<div>
	<div id="Equipment 1" style=display:none;>
		</div>
		<label for="Equipment Type">Equipment Type</label>
		<div class="field">
			<select id="equipment-type" name="equipment-type" required>
				<option disabled selected value="">--</option>
				<option value="0">Tennis Rackets</option>
				<option value="1">Tennis Balls</option>
				<option value="2">Badminton Rackets</option>
				<option value="3">Basketballs</option>
				<option value="4">Footballs</option>
			</select>
		</div>
	</div>
</div>
	<div class="wrapper">
	<div>
		<label for="Equipment no">Equipment No.</label>
		<div class="field">
			<select id="equipment-no" name="equipment-no" required>
				<option disabled selected value="">--</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
	</div>
</div>
	<div class = "container">
			<h1 class="text-center">Book</h1><hr>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<form action "" method="post">
					<div class "form-group">
						<label for "">Name</label>
						<input type"text" class="form-control" name"name">
						</div>
						<button class="btn btn-primary" type="sub">Submit</button>
					</form>
				</div>
		</div>
	</div>
</div>			
</script>
<div class="gap"></div>
<input type="submit" value="Book">
</div>
		</form>
	</body>
</html>
*/
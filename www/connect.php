<?php
function OpenCon()
{
	$sname= "localhost";
	$uname= "root";
	$password = "root";
	$db_name = "leisure-centre-booking";
	$conn = mysqli_connect($sname, $uname, $password, $db_name);
	if (!$conn) {
		echo "Connection failed!";
	}
}
?>
<?php
session_start();
if(isset($_SESSION['loggedin'])){
    require_once("header-in.php");
}else{  
    require_once("header.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>About page</title>
</head>

<body>
	<h1>ABOUT</h1>
</body>
</html>
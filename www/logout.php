<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>

<?php
session_start();
session_destroy();
// Redirect to the login page:
header('Location: index.php');
?>
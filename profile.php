<?php
session_start();
if(!(isset($_SESSION["auth"]) && $_SESSION["auth"])){
	header("Location: index.php");
	}
?>
<html>
<head>
</head>
<body >
Welcome to your Profile!
<form action="logout.php" method="GET">
<input type="submit" value="Logout" />
</form>
</body>
</html>
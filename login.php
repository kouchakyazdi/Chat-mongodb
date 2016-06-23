<?php
session_start();

$connection= new MongoClient();
$db= $connection->test;
$collection = $db->selectCollection('User');
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_POST['submit'] === "login") {
        $results = $collection->findOne(array('username' => $_POST['username']));
        if($results && $results['password'] == $_POST['password']){
        	$_SESSION["auth"] = True;
			header("Location: profile.php");
		}
        else{
		?>

<html>
<head></head>
<body><h1>Wrong User or Pass</h1></body>
<a href="index.php">Go to login page </a>
		<?php
    	}
    }
}
?>
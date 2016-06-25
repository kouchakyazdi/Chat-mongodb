<?php 
session_start();

$connection = new MongoClient();
$db = $connection->test;
$collection = $db -> selectCollection('User');

if($_SERVER_["REQUEST_METHOD"] == "POST"){
	if ($_POST["submit"] == "update") {
		$name = $_POST['name'];
		$family = $_POST['family'];
		$email = $_POST['email'];
		$contact = $_POST['bio'];
		$birth = $_POST['birth'];
 		$collection->update(
             array("username" => $_SESSION['username']),
             array('$set' => array("first_name" => 'first', "updated_at" => 'updatedat'))
 );
//		$collection -> update( array( 'username'=>amiri , array('set' => array('password' => 123 ))));
	}
}

 ?>
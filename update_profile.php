<?php 
session_start();

$connection = new MongoClient();
$db = $connection->test;
$collection = $db -> selectCollection('User');

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if (isset($_POST["submit"]) && $_POST["submit"] == "update") {
		$name = $_POST['name'];
		$family = $_POST['family'];
		$email = $_POST['email'];
		$contact = $_POST['bio'];
		$birth = $_POST['birth'];

		$fileName="";
		$target_dir = "uploads/";
		if(isset($_FILES["image"]["name"]))
			$fileName=basename($_FILES["image"]["name"]);
		$target_file = $target_dir . $fileName;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$gridfs = $connection->selectDB('test')->getGridFS();
		$t=new MongoDate();
		if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
			echo 'No upload';
		}
		else{
			$gridfs->storeUpload('image', array('owner' => $_SESSION['username']/*, "ts" => gmdate(DATE_ISO8601 ,$t->sec)*/));
		}

		$collection->update(
			array("username" => $_SESSION['username']),
			array('$set' => array("name" => $name, "family" => $family , "email" => $email, "contact" => $contact,
				"birth" => $birth, "image" => $fileName)) , array("upsert" => "true"));
		header("Location: chatUI.php");
	}
}

 ?>
<?php
session_start();
$connection= new MongoClient();
$db= $connection->test;
$userCollection = $db->User;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_POST['submit'] === "register") {
        $_SESSION["username"]=$_POST['username'];
        $doc= array("_id" => $_POST['username']  ,"username"=>$_POST['username'] , "password"=>$_POST['password'] , "report" => 0);
        $userCollection->insert($doc);
        echo "done";
        header("Location: profile.php");
    }

}

?>
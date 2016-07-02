<?php
session_start();
$connection= new MongoClient();
$db= $connection->test;
$collection = $db->selectCollection('User');
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_POST['submit'] === "login") {
        $results = $collection->findOne(array('username' => $_POST['username']));
        if($results && $results['password'] == $_POST['password'] ){
            if($results["report"] < 10){
                $_SESSION['username']=$_POST['username'];
                header("Location: chatUI.php");
            }
            else
                echo "your account is close ";

        }
        else
            echo "error!";
    }

}

?>
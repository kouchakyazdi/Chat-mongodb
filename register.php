<?php

$connection= new MongoClient();
$db= $connection->test;
$userCollection = $db->User;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_POST['submit'] === "register") {
        $doc= array("username"=>$_POST['username'] , "password"=>$_POST['password']);
        $userCollection->insert($doc);
        echo "done";
        //gathering information
    }

}

?>
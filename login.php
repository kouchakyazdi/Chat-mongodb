<?php

$connection= new MongoClient();
$db= $connection->test;
$collection = $db->selectCollection('User');
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_POST['submit'] === "login") {
        $results = $collection->findOne(array('username' => $_POST['username']));
        if($results && $results['password'] == $_POST['password'])
            echo "logged in!!";
        else
            echo "error!";
    }

}

?>
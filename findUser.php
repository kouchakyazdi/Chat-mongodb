<?php
session_start();

    $connection= new MongoClient();
    $db= $connection->test;
    $collection = $db->selectCollection('User');
    if($_SERVER["REQUEST_METHOD"]=="POST") {
//--------------------------------
        $cursor = $collection -> find();
        $i = 0;
        foreach ($cursor as $doc){
            $a[$i++] = $doc['username'] ;
        }
//--------------------------------        
        $q = $_POST["query"];
        if (strlen($q) > 0){
            $hint = "";
            for($i = 0; $i < count($a); $i++){
                if (strtolower($q) == strtolower(substr($a[$i],0,strlen($q)))){
                    if($hint == ""){
                        $hint = $a[$i];
                    }
                }
            }
        }
        else{ $hint=$hint." , ".$a[$i];
        }
        if ($hint == ""){ $response="no user ";
        }
        else {
            $response=$hint;
        }
        echo $response;

    }
    else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            $cursor = $collection -> find();
            $index = 1;
            $i = 0;
            foreach ($cursor as $doc){
                echo $index++." ".$doc['username']."<br>" ;
                $a[$i++] = $doc['username'] ;
            }
    }

?>
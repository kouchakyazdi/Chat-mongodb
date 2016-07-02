<?php
session_start();
    $connection= new MongoClient();
    $db= $connection->test;
    $collection = $db->selectCollection('User');
        if (isset($_POST['func']) && $_POST['func']=="findUsername"){
            $allCursor = $collection -> find(array("username"=> $_POST["username"]));
            $xml = new SimpleXMLElement('<users/>');
            foreach ($allCursor as $cursor){
                $user = $xml->addChild('user');
                $user->addChild('name' , $cursor['name']);
                $user->addChild('family' , $cursor['family']);
                $user->addChild('username' , $cursor['username']);
            }

            header('Content-type: text/xml');
            print($xml->asXML());

        }
        else if (isset($_POST['func']) && $_POST['func']=="findName"){
            $allCursor = $collection -> find(array("name"=> $_POST["name"]));
            $xml = new SimpleXMLElement('<users/>');
            foreach ($allCursor as $cursor){
                $user = $xml->addChild('user');
                if(isset($cursor['name']))
                    $user->addChild('name' , $cursor['name']);
                if(isset($cursor['family']))
                $user->addChild('family' , $cursor['family']);
                if(isset($cursor['username']))
                $user->addChild('username' , $cursor['username']);
            }

            header('Content-type: text/xml');
            print($xml->asXML());
        }


    //}

?>
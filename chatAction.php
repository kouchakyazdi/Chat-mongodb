<?php
session_start();
$username = $_SESSION['username'];
$m = new MongoClient();
$db = $m->selectDB('test');
$collection = new MongoCollection($db, 'User');

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if (isset($_POST['func']) && $_POST['func']=="getChatRecord"){
        $friendName=$_POST['friendName'];
        $_SESSION['friendName']=$friendName;
        $messageCollection = new MongoCollection($db, 'Message');
        $message = array('$or' => array(
            array("to" => $friendName , "owner" => $username),
            array("to" => $username , "owner" => $friendName)
        ));

        $cursor = $messageCollection->find($message)->sort(array('ts'=>-1))->limit(2);
        $messageArr=iterator_to_array($cursor,false);
        //$dataArray=array();
        //for($i=0;$i<sizeof($messageArr);$i++){
        //echo "<div class=\"message\">".
        //$messageArr[$i]["owner"].":".$messageArr[$i]["content"]."</div>";
        //$dataArray[$i]["owner"]=$messageArr[$i]["owner"];
        //}
        echo json_encode($messageArr);
    }
    else if (isset($_POST['func']) && $_POST['func']=="submitMessage"){
        $friendName=$_POST['friendName'];
        $content=$_POST['content'];
        $messageCollection = new MongoCollection($db, 'Message');
        $message = array("content" => $content , "owner" => $username , "to" => $friendName , "ts" => new MongoDate());
        $messageCollection->insert($message);
        echo $username;
    }
    else if (isset($_POST['func']) && $_POST['func']=="moreMessage"){
        $friendName=$_POST['friendName'];
        $pageNumber=$_POST['pageNumber'];
        $messageCollection = new MongoCollection($db, 'Message');
        $message = array('$or' => array(
            array("to" => $friendName , "owner" => $username),
            array("to" => $username , "owner" => $friendName)
        ));
        $cursor = $messageCollection->find($message)->sort(array('ts'=>-1))->skip($pageNumber > 0 ? (($pageNumber-1)*2) : 0)->limit(2);
        $messageArr=iterator_to_array($cursor,false);
        echo json_encode($messageArr);
    }
    else if (isset($_POST['func']) && $_POST['func']=="unfriend"){
        $friendName=$_POST['friendName'];
        $collection = new MongoCollection($db, 'User');
        $user = array('$pull' =>array('friends' => $friendName));
        $collection->update(array("username" => $username), $user);
        //$arr=iterator_to_array($cursor,false);
        echo "deleted!";
    }
    else if (isset($_POST['func']) && $_POST['func']=="report"){
        $friendName=$_POST['friendName'];
        $collection = new MongoCollection($db, 'User');
        $user = array('$inc' =>array('report' => 1));
        $collection->update(array("username" => $username), $user);
        //$arr=iterator_to_array($cursor,false);
        echo "reported!";
    }
    else if (isset($_POST['func']) && $_POST['func']=="logout"){
        $_SESSION['username']="";
        session_destroy();
        //echo "true";
        //header("Location : index.php");
    }

}





if($_SERVER["REQUEST_METHOD"]=="GET") {

    if (isset($_GET['button']) && $_GET['button'] == "profile") {
        //include 'profile.php';
        //$x=profile();
        //Moved collection to outer scope
        $user = array('username' => $username);
        $cursor = $collection->find($user);
        $oneCursor=$collection->findOne($user);
        $arr=iterator_to_array($cursor,false);
        //echo implode(",",$arr[0]);

        $images = $db->getGridFS();
        $image = $images->findOne(array( "owner" =>$username) );

        $file = $images->findOne(array('filename' => $oneCursor['image']));
        $files = $db->fs->files;
        $file1 = $files->findOne(array('filename' => $oneCursor['image']));
        $id = $file->file['_id'];
        $file=fopen($oneCursor['image'], "w+");
        //if ( (substr($_POST['filename'],-3) == 'zip') || (substr($_POST['filename'],-3) == 'pdf') ) {
        /* Any file types you want to be downloaded can be listed in this */
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$oneCursor['image']);
        header('Content-Transfer-Encoding: binary');
        $cursorFile = $db->fs->chunks->find(array("files_id" => $id))->sort(array("n" => 1));
        foreach($cursorFile as $chunk) {
            fwrite($file,$chunk['data']->bin) ;
        }

        echo json_encode($arr[0]);
    }
    if (isset($_GET['button']) && $_GET['button'] == "loadProfile") {
        $user = array('username' => $_SESSION['friendName']);
        $cursor = $collection->find($user);

        $oneCursor=$collection->findOne($user);
        $arr=iterator_to_array($cursor,false);
        //echo implode(",",$arr[0]);

        $images = $db->getGridFS();
        $image = $images->findOne(array( "owner" =>$_GET['fname']) );
        if(isset($oneCursor['image'])){
            $file = $images->findOne(array('filename' => $oneCursor['image']));
            $files = $db->fs->files;
            $file1 = $files->findOne(array('filename' => $oneCursor['image']));
            $id = $file->file['_id'];
            $file=fopen($oneCursor['image'], "w+");
            //if ( (substr($_POST['filename'],-3) == 'zip') || (substr($_POST['filename'],-3) == 'pdf') ) {
            /* Any file types you want to be downloaded can be listed in this */
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$oneCursor['image']);
            header('Content-Transfer-Encoding: binary');
            $cursorFile = $db->fs->chunks->find(array("files_id" => $id))->sort(array("n" => 1));
            foreach($cursorFile as $chunk) {
                fwrite($file,$chunk['data']->bin) ;
            }
        }



        echo json_encode($arr[0]);
    }
}


?>





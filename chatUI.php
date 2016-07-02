<?php
session_start();
$friendName="";

$username=$_SESSION["username"];
$m = new MongoClient();
$db = $m->selectDB('test');
$collection = new MongoCollection($db, 'User');
$user = array('username' => $username);
$cursor = $collection->find($user);
$arr=iterator_to_array($cursor,false);
//echo json_encode($arr[0]);
//if($friendName!=""){
//if($_SERVER["REQUEST_METHOD"]=="POST"){
    //if (!empty($_POST['friendName']))
    /*$friendName=$_POST['friendName'];
    $messageCollection = new MongoCollection($db, 'Message');
    $message = array('$or' => array(
        array("to" => $friendName),
        array("to" => $username)
    ));*/

    //$cursor = $messageCollection->find($message);
    //$messageArr=iterator_to_array($cursor->limit(20),false);
    //$dataArray=array();
    //for($i=0;$i<sizeof($messageArr);$i++){
        //echo "<div class=\"message\">".
            //$messageArr[$i]["owner"].":".$messageArr[$i]["content"]."</div>";
        //$dataArray[$i]["owner"]=$messageArr[$i]["owner"];
    //}
    //echo json_encode($messageArr);
//}

//}
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Material Login Form</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chat.css">
</head>

<body>

<div id="chatDiv">

    <div id="buttons">
        <table>
            <tr>
                <td>
                    <div class="button-container"><label style="font-size: 30px;">friend</label></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="unfriend" type="submit" name="submit" value="unfriend"></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="refresh" type="submit" name="submit" value="refresh"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="profile" type="submit" name="submit" value="profile"></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="more-message" type="submit" name="submit" value="more message"></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" type="submit" name="submit" value="private chat"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="loadProfile" type="submit" name="submit" value="load profile"></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" type="submit" class="report" name="submit" value="report"></div>
                </td>
                <td>
                    <div class="button-button-container button-container"><input id="button" type="submit" name="submit" class="search" value="search"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="button-button-container button-container"><input id="button" class="logout" type="submit" name="submit" value="logout"></div>
                </td>
            </tr>
        </table>

    </div>
    <div id="chatList">
        <div id="list" class="scrollable">
            <?php
            if(isset($arr[0]["friends"]))
                foreach ($arr[0]["friends"] as $a){
                    echo "<div class=\"friend\"><div 
                    class=\"friend-button-container button-container\"><input id=\"button\" class=\"friend-button\" type=\"submit\" name=\"submit\" value=\"".
                    $a."\"></div></div>";
                }
            ?>
        </div>
        <div id="chat">
            <div id="chatRecord" class="scrollable">

            </div>
            <div id="chatFooter">
                <div><textarea type="text" id="chatInput"></textarea></div>
                <div class="submit-button-container button-container"><input class="submit-message" id="button" type="submit" name="submit" value="submit"></div>
                <div id="time">time: <input type="text" id="timer"></div>
            </div>

        </div>
    </div>

</div>

<script src='./js/JQuery.js'></script>
<script src="./js/index.js"></script>
<script>
    var pageNumber=1;
    var friendElement=null;
    $(".profile").click(function () {
        window.location="profileLoad.html";
    });
    $(".loadProfile").click(function () {
        window.location="friendProfileLoad.html?fname="+$("label").html();
    });
    $(".unfriend").click(function () {
        friendElement.parent().parent().remove();
        friendElement=null;
        var fname=$("label").html();
        $("label").html("");
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'unfriend'
            },
            method: 'post',
            success: function (res) {
                alert(res);
            }
        });
    });
    $(".submit-message").click(function () {
        var fname=$("label").html();
        var content=$("#chatInput").val();
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'submitMessage',
                content : content
            },
            method: 'post',
            success: function (res) {
                $("#chatRecord").append("<div class=\"message\">"+
                    res+" : "+content+"</div>");
            }
        });
    });
    $(".more-message").click(function () {
        var fname=$("label").html();
        pageNumber++;
        var content=$("#chatInput").val();
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'moreMessage',
                pageNumber : pageNumber
            },
            method: 'post',
            success: function (res) {
                console.log(pageNumber);
                console.log(res);
                var jsonparsed = JSON.parse (res);
                $.each(jsonparsed, function(i, v) {
                    $("#chatRecord").prepend("<div class=\"message\">"+
                        v.owner+" : "+v.content+"</div>");
                });
            }
        });
    });
    $(".friend-button").click(function () {
        pageNumber=1;
        $("#chatRecord").html("");
        var fname=$(this).attr("value");
        friendElement=$(this);
        $("label").html(fname);
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'getChatRecord'
            },
            method: 'post',
            success: function (res) {
                //console.log(res);
                var jsonparsed = JSON.parse (res);
                $.each(jsonparsed, function(i, v) {
                    //if (v.name == "Peter") {
                        //console.log(rees[i].owner);
                        //console.log(v.content);
                    $("#chatRecord").prepend("<div class=\"message\">"+
                        v.owner+" : "+v.content+"</div>");
                        //return;
                    //}
                });
                /*$("#chatRecord").html("<div class=\"message\">"+
                res[0].owner+":"+res[0].content+"</div>");
                console.log("<div class=\"message\">"+
                    res[0].owner.toString()+":"+res[0].content.toString()+"</div>");*/
                /*$.getJSON( res, function( json ) {
                    console.log( "JSON Data: " + json.users[ 3 ].name );
                });*/
            }
        });

    });
    $(".refresh").click(function () {
        pageNumber=1;
        $("#chatRecord").html("");
        var fname=$("label").html();
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'getChatRecord'
            },
            method: 'post',
            success: function (res) {
                var jsonparsed = JSON.parse (res);
                $.each(jsonparsed, function(i, v) {
                    $("#chatRecord").prepend("<div class=\"message\">"+
                        v.owner+" : "+v.content+"</div>");
                });
            }
        });

    });
    $(".report").click(function () {
        $("#chatRecord").html("");
        friendElement.parent().parent().remove();
        friendElement=null;
        var fname=$("label").html();
        $("label").html("");
        $.ajax('chatAction.php', {
            data: {
                friendName : fname,
                func : 'report'
            },
            method: 'post',
            success: function (res) {
                alert(res);
            }
        });

    });
    $(".logout").click(function () {
        $.ajax('chatAction.php', {
            data: {
                func : 'logout'
            },
            method: 'post',
            success: function (res) {
                console.log(res);

                    console.log('sala');
                    window.location = "index.php";
                    console.log('sala');

            }, error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                console.log(jqXHR.responseText);
                // STOP LOADING SPINNER
            }
        });

    });
    $(".search").click(function () {
        window.location="search.html";
    });
</script>

</body>
</html>

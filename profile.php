<?php
session_start();
//if(!(isset($_SESSION["auth"]) && $_SESSION["auth"])){
    //header("Location: index.php");
//}
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Profile update</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Welcome "<?php echo $_SESSION["username"] ?>"</h1>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="input-container">
                <input type="text" name="name" id="name" required="required"/>
                <label for="name">name</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" name="family" id="family" required="required"/>
                <label for="name">family</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" name="email" id="email" required="required"/>
                <label for="name">email</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" name="contact" id="contact" required="required"/>
                <label for="name">contact</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" name="bio" id="bio" required="required"/>
                <label for="name">bio</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" name="birth" id="birth" required="required"/>
                <label for="name">birth</label>
                <div class="bar"></div>
            </div>
            <!-- image browser -->
            <div class="fileUpload btn btn-primary">
                <span>Upload</span>
                <input type="file" name="image" class="image">
            </div>
            <br><br>
            <div class="button-container">
                <input id="button" type="submit" name="submit" value="update">
            </div>
        </form>

    </div>
</div>
<script src='http://localhost/DB-Final/js/JQuery.js'></script>
<script src="js/index.js"></script>
</body>
</html>




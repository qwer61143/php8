<?php
    session_start();
    require_once("../method/connet.php");

    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = :good_id");
?>
<html>
    <head>
    <meta charset="utf8">
    <title>您的產品列表</title>
    </head>
    <body>
        
    </body>
</html>
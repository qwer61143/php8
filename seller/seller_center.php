<?php
    session_start();
    require_once("../method/connet.php");

    $query = $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['u_id'], PDO::PARAM_INT);
    $query -> execute();

    if(!$query -> fetch(PDO::FETCH_ASSOC)){
        header("Location:../user/user_center.php");
        exit();
    }
?>
<html lang="zh-TW">
    <head>
    <meta charset="utf8">
    <title>賣家中心</title>
    </head>
    <body>
        <div>
            <a href = "../goods/good_upload.php">good_upload</a>
        </div>
        <div>
            <a href = "seller_good_list.php">您的產品列表</a>
        </div>
    </body>
</html>
<?php
    session_start();
    require_once("../method/connet.php");

    if(!isset($_SESSION['u_name'])){
        header("Location:../user/user_login.php");
        exit();
    }

    $query =  $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['u_id'], PDO::PARAM_INT);
    $query -> execute();

    if(!$query -> fetch(PDO::FETCH_ASSOC)){
        header("Location :../user/user_center.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf8">
        <title>商品上傳</title>
    </head>
    <body>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div>商品名稱<input type="text" name="good_name" value="<?php if(isset($_GET['nameErr'])){ echo $_GET['nameErr'];}?>"></div>
            <div>商品圖片<input type="file" name="good_pic"></div>
            <div>售價<input type="text" name="good_price" value="<?php if(isset($_GET['priceErr'])){ echo $_GET['priceErr'];}?>"></div>
            <div>商品數量<input type="number" name="good_total" value="<?php if(isset($_GET['totalErr'])){ echo $_GET['totalErr'];}?>"></div>
            <div>商品資訊<textarea name="good_info" row="5" cols="20"></textarea></div>
            <input type="hidden" name="good_seller" value="<?php echo $_SESSION['u_id']?>">
            <input type="submit" value="上傳">
        </form>
    </body>
</html>



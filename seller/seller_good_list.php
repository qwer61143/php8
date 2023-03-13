<?php
    session_start();
    require_once("../method/connet.php");

    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_seller = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['u_id'], PDO::PARAM_INT);
    $query -> execute();
    $result = $query -> fetchAll(PDO::FETCH_ASSOC);
?>
<html>
    <head>
    <meta charset="utf8">
    <title>您的產品列表</title>
    </head>
    <body>
        <?php foreach($result as $data) : ?>
            <div><a href="good_update.php?gid=<?php echo $data['good_id'] ?>"><img src="<?php echo $data['good_pic'] ?>"></a></div>
            <div><?php echo $data['good_name']?></div>
        <?php endforeach ?>
    </body>
</html>
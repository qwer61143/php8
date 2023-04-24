<?php
    session_start();

    require_once("../../lib/Connect.php");

    $db = new Connect;
    $conn = $db->getConnect();
    
    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_seller = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $query -> execute();
    $result = $query -> fetchAll(PDO::FETCH_ASSOC);

    require_once "../../method/bootstrap.html";
?>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>您的產品列表</title>
    <link rel="stylesheet" href="../../css/css.css" type="text/css">
    </head>
    <body>
        <?php
            require_once "../../view/navbar.php";
        ?>

        <div class="text-center seller-center-title mt-5">您的商品</div>

        <div class="container mt-5">
            <div class="row d-flex justify-content-center align-items-center text-center ">
                    <?php foreach($result as $good) :?>
                        <div class="col-2 col-md-3">
                            <div class="card border-0">
                                <a href="good_update.php?gid=<?php echo $good['good_id'] ?>">
                                    <img src="<?php echo $good['good_pic'] ?>" class="card-img-top custom-img" alt="...">
                                </a>
                                <div class="card-body text-center">
                                    <div class="custom-gdlist-title mb-3"><?php echo $good['good_name']?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <?php
            require_once "../../view/footer.php";
        ?>

    </body>
</html>
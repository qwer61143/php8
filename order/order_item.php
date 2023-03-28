<?php
    session_start();

    require_once("../method/connet.php");

    $order_id = $_GET['oid'];

    $query = $conn -> prepare("SELECT * FROM `order_item` WHERE order_id = :order_id");
    $query -> bindParam(":order_id", $order_id, PDO::PARAM_INT);
    $query -> execute();

    $result = $query -> fetchAll();

    require_once("../method/bootstrap.html");
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <title>訂單編號:<?php echo $order_id ?></title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 position-relative">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">InsideTech</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">主頁</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/cart/cart.php">購物車</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">更改語言</a>                
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">繁體中文</a></li>
                                <li><a class="dropdown-item" href="#">英文</a></li>
                                <li>
                                <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">更多</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link " href="../contact.php">需要幫助嗎?</a>
                        </li>
                        
                            <li class="nav-item dropdown">
                            <?php if (isset($_SESSION['u_name']) && $_SESSION['u_name'] != "") { ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['u_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="../index.php?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="/user/user_login.php">登入</a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <?php foreach($result as $item) { ?>
            <?php 
                $good_query = $conn -> prepare("SELECT good_pic FROM `goods` WHERE `good_id` = :good_id"); 
                $good_query -> bindParam(":good_id", $item['good_id'] , PDO::PARAM_INT);
                $good_query -> execute();

                $good_result = $good_query -> fetch();
            ?>
            <div class="container border border-dark mt-5">
                <div class="row">
                    <div class="col-3 d-flex align-items-center">
                        <img src="<?php echo $good_result['good_pic'] ?>" class="img-fluid" alt="Product Image">
                    </div>
                    <div class="col-3 d-flex align-items-center text-center">
                        <div class="text-center" style="width: 100%;">
                            <h5 class="card-title"><?php echo $item['item_name'] ?></h5>
                        </div>
                    </div>
                    <div class="col-1 d-flex align-items-center text-center">
                        <p class="card-text">單價: <?php echo $item['price'] ?></p>
                    </div>
                    <div class="col-1 d-flex align-items-center text-center">
                        <p class="card-text">購買數量: <?php echo $item['quantity'] ?></p>
                    </div>
                    <div class="col-1 d-flex align-items-center text-center">
                        <a href="../goods/good.php?id=<?php echo $item['good_id'] ?>">
                            <p class="card-text">商品頁面</p>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </body>
</html>
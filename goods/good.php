<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");
    
    $good_id = $_GET['id'];
    $select = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = ? ");
    $select -> execute(array($good_id));
    $good = $select -> fetch(PDO::FETCH_ASSOC);

    $query = $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :good_seller");
    $query -> bindParam(":good_seller",$good['good_seller'],PDO::PARAM_INT);
    $query -> execute();
    $seller_query = $query -> fetch(PDO::FETCH_ASSOC);

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);
    
    if(isset($_POST['cartaction']) && $_POST['cartaction'] == 'add'){
        $cart -> add($_POST['id'], $_POST['quantity'],[
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'img' => $_POST['img']
        ]);
        header("Location:../index.php");
        //exit;
    }

    require_once("../method/bootstrap.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title><?php echo $good['good_name'] ?></title>
        <style>
        .card-body-text{
            font-size: 1.5em;
        }

        .padding-right {
            padding-right: 2rem;
        }

        .img-wrapper {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .img-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s;
        }

        .img-wrapper img:hover {
            transform: scale(1.2);
        }

        @media (max-width: 576px) {
            .img-wrapper img:hover {
                transform: none;
            }
        }

        .card-no-border {
            border: none !important;
            background-color: transparent;
        }
</style>
    </style>
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

        <div style="padding-left: 12rem; padding-right: 12rem;">
            <div class="row g-0 mb-3 mt-5 card-no-border">
                <div class="col-md-4 padding-right">
                    <div class="img-wrapper">
                        <img src="<?php echo $good['good_pic'] ?>" class="img-fluid rounded-start" alt="...">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title card-body-text">商品名:<?php echo $good['good_name'] ?></h5>
                        <h5 class="card-title card-body-text mt-2">售價:<?php echo '$'.$good['good_price'] ?></h5>
                        <h5 class="card-title card-body-text mt-2 mb-5">已售:<?php echo $good['good_sold'] ?></h5>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $good_id ?>">
                                <input type="hidden" name="name" value="<?php echo $good['good_name'] ?>"> 
                                <input type="hidden" name="price" value="<?php echo $good['good_price'] ?>">
                                <input type="hidden" name="img" value="<?php echo $good['good_pic'] ?>">
                                <input type="number" name="quantity">
                                <input type="hidden" name="cartaction" value="add">
                                <input type="submit" value="加入購物車">
                            </form>

                        <p class="card-text card-body-text"><small class="text-muted"><?php echo "上架時間:".$good['good_uptime']?></small></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-5" style="padding-left: 12rem; padding-right: 12rem;">
            <div class="row mt-3">
                <div class="col-md-4 d-flex align-items-center">
                    <div class="me-3">
                        <img src="../imgs/seller.png" alt="Seller Photo" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div>
                        <h5><?php echo $seller_query['seller_name'] ?></h5>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <p>評分：<?php echo $seller_query['rating'] ?></p>
                            <p>發貨地：<?php echo $seller_query['seller_address'] ?></p>
                            <p>加入時間：<?php echo $seller_query['seller_jointime'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>上次登入時間：<?php echo $seller_query['seller_logintime'] ?></p>
                            <p>連絡電話：<?php echo $seller_query['seller_phone'] ?></p>
                            <p>付款方式：<?php echo $seller_query['seller_paymethod'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
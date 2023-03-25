<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    require_once("../method/connet.php");

    if(!isset($_SESSION['seller_id']) || ($_SESSION["seller_id"] == "")){
        header("Location:/seller/seller_login.php");
        exit;
    }

    $query = $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $query -> execute();

    if(!$query -> fetch(PDO::FETCH_ASSOC)){
        header("Location:join_seller.php");
        exit();
    }

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['seller_name']);
        unset($_SESSION['seller_id']);
        header("Location:seller_login.php");
        exit;
    }

    require_once("../method/bootstrap.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <style>
            .custom-img-size {
                max-width: 50%;
                margin: 0 auto;
            }
        </style>
        <title>賣家中心</title>
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
                            <?php if (isset($_SESSION['seller_name']) && $_SESSION['seller_name'] != "") { ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['seller_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="seller_login.php">登入</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
        <div class="col">
            <div class="card h-100 border-0">
                <img src="../imgs/product.png" class="card-img-top custom-img-size img-fluid" alt="...">
                <div class="card-body">
                    <a href="seller_good/good_upload.php">
                        <h5 class="card-title text-center">上傳商品</h5>
                    </a>
                </div>
            </div>
        </div>
        <div class="col">
                <div class="card h-100 border-0">
                    <img src="../imgs/seller.png" class="card-img-top custom-img-size img-fluid" alt="...">
                    <div class="card-body">
                        <a href="seller_profile.php">
                            <h5 class="card-title text-center">商家資料</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0">
                    <img src="../imgs/buylist.png" class="card-img-top custom-img-size img-fluid" alt="...">
                    <div class="card-body">
                        <a href="seller_good/seller_good_list.php">
                            <h5 class="card-title text-center">您的商品</h5>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
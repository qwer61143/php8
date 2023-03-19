<?php
    session_start();

    require_once("../method/connet.php");
    require_once("../method/bootstrap.html");

    if(!isset($_SESSION['u_name']) || ($_SESSION["u_name"] == "")){
        header("Location:user_login.php");
        exit;
    }
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        unset($_SESSION['u_id']);
        header("Location:user_login.php");
        exit;
    }
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
        <title>用戶中心</title>
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
                            <a class="nav-link" href="../seller/join_seller.php">成為賣家!</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="cart/cart.php">購物車</a>
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
                            <a class="nav-link " href="cart/cart.php">需要幫助嗎?</a>
                        </li>
                       
                          <li class="nav-item dropdown">
                            <?php if (isset($_SESSION['u_name']) && $_SESSION['u_name'] != "") { ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['u_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="user/user_login.php">登入</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
        <div class="col">
                <div class="card h-100 border-0">
                    <img src="../imgs/user.png" class="card-img-top custom-img-size img-fluid" alt="...">
                    <div class="card-body">
                        <a href="user_update.php">
                            <h5 class="card-title text-center">個人資料</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0">
                    <img src="../imgs/buylist.png" class="card-img-top custom-img-size img-fluid" alt="...">
                    <div class="card-body">
                        <a href="">
                            <h5 class="card-title text-center">購買清單</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0">
                    <img src="../imgs/help.png" class="card-img-top custom-img-size img-fluid" alt="...">
                    <div class="card-body">
                        <a href="">
                            <h5 class="card-title text-center">客服中心</h5>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
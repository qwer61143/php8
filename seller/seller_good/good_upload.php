<?php
    session_start();

    require_once("../../method/connet.php");
    require_once("../../method/bootstrap.html");

    if(!isset($_SESSION['seller_id'])){
        header("Location:seller_login.php");
        exit();
    }

    $query =  $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $query -> execute();

    if(!$query -> fetch(PDO::FETCH_ASSOC)){
        header("Location :join_seller.php");
        exit();
    }
    
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['seller_name']);
        unset($_SESSION['seller_id']);
        header("Location:../../index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../../css/css.css" type="text/css">
        <style>
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        </style>
        <title>商品上傳</title>
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
                            <a class="nav-link active" aria-current="page" href="../../index.php">主頁</a>
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
                            <a class="nav-link " href="../../contact.php">需要幫助嗎?</a>
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
                                <a class="nav-link" href="../seller_login.php">登入</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="centered-form">
            <form class="w-50" method="POST" action="upload.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="good_name">商品名稱</label>
                    <input type="text" class="form-control" id="good_name" name="good_name">
                </div>
                <div class="form-group">
                    <label for="good_pic">商品圖片</label>
                    <input type="file" class="form-control" id="good_pic" name="good_pic">
                </div>
                <div class="form-group">
                    <label for="good_price">商品價格</label>
                    <input type="number" class="form-control" id="good_price" name="good_price">
                </div>
                <div class="form-group">
                    <label for="good_total">商品數量</label>
                    <input type="number" class="form-control" id="input4" name="good_total">
                </div>
                <div class="form-group">
                    <label for="good_info">商品描述</label>
                    <input type="text" class="form-control" id="good_info" name="good_info">
                </div>
                <input type="hidden" name="good_seller" value="<?php echo $_SESSION['seller_id'] ?>">
                <button type="submit" class="btn btn-primary">上傳</button>
            </form>
        </div>
    </body>
</html>

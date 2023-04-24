<?php
    session_start();

    require_once "../lib/Connect.php";

    if(!isset($_SESSION['seller_name']) || ($_SESSION["seller_name"] == "")){
        header("Location: seller_login.php");
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
        <title>賣家中心</title>
    </head>
    <body>

        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="text-center seller-center-title mt-5">賣家中心</div>

        <div class="container">
            <div class="row d-flex justify-content-center align-items-center mt-5">
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/product.png" class="card-img-top custom-seller-img" alt="...">
                        <div class="card-body">
                            <a href="seller_good/good_upload.php">
                                <h5 class="card-title text-center mt-5">上傳商品</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/seller.png" class="card-img-top custom-seller-img" alt="...">
                        <div class="card-body">
                            <a href="seller_profile.php">
                                <h5 class="card-title text-center mt-5">商家資料</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/buylist.png" class="card-img-top custom-seller-img" alt="...">
                        <div class="card-body">
                            <a href="seller_good/seller_good_list.php">
                                <h5 class="card-title text-center mt-5">您的商品</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once "../view/footer.php";
        ?>

    </body>
</html>
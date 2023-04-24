<?php
    session_start();

    require_once "../lib/Connect.php";

    if(!isset($_SESSION['name']) || ($_SESSION["name"] == "")){
        header("Location:user_login.php");
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
        <title>用戶中心</title>
    </head>
    <body>
        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="text-center user-center-title mt-5">用戶中心</div>

        <div class="container">
            <div class="row d-flex justify-content-center align-items-center mt-5">
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/user.png" class="card-img-top custom-usercenter-img" alt="...">
                        <div class="card-body">
                            <a href="user_update.php">
                                <h5 class="card-title text-center mt-5">個人資料</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/buylist.png" class="card-img-top custom-usercenter-img" alt="...">
                        <div class="card-body">
                            <a href="../order/order_list.php">
                                <h5 class="card-title text-center mt-5">購買清單</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <div class="card border-0">
                        <img src="../imgs/help.png" class="card-img-top custom-usercenter-img" alt="...">
                        <div class="card-body">
                            <a href="">
                                <h5 class="card-title text-center mt-5">客服中心</h5>
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
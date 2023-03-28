<?php
    session_start();

    require_once("../method/connet.php");
    require_once("../method/class.Cart.php");

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        unset($_SESSION['u_id']);
        header("Location:user_login.php");
        exit;
    }

    $good_id = "";

    require_once("../method/bootstrap.html");
?>


<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title>結帳</title>
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
            
        <?php if($cart->getTotalItem()> 0) { ?>
            <?php
                $allItems = $cart -> getItems();
                foreach($allItems as $items) {
                    foreach($items as $item) {
                        $good_id = $item['id'].",".$good_id;
                    ?>
                <div class="container border border-dark mt-5">
                    <div class="row">
                        <div class="col-3 d-flex align-items-center">
                            <img src="<?php echo $item['attributes']['img'] ?>" class="img-fluid" alt="Product Image">
                        </div>
                        <div class="col-3 d-flex align-items-center text-center">
                            <div class="text-center" style="width: 100%;">
                                <h5 class="card-title"><?php echo $item['attributes']['name']; ?></h5>
                            </div>
                        </div>
                        <div class="col-1 d-flex align-items-center text-center">
                            <p class="card-text">單價: <?php echo $item['attributes']['price']; ?></p>
                        </div>
                        <div class="col-2 d-flex flex-column justify-content-center align-items-center">
                            <p class="card-text">數量: <?php echo $item['quantity'] ?> </p>
                        </div>
                        <div class="col-1 d-flex align-items-center text-center">
                            <p class="card-text">總價: <?php echo number_format($item['quantity'] * $item['attributes']['price']); ?></p>
                        </div>
                    </div>
                </div>
                        
            <?php }}} ?>

        <form class="w-50 mx-auto" action="cartreport.php" method="post">
            <div class="input-group mb-3 mt-5">
                <span class="input-group-text" id="c_name">您的姓名</span>
                <input type="text" class="form-control" name="c_name">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="c_phone">連絡電話</span>
                <input type="text" class="form-control" name="c_phone">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="c_address">地址</span>
                <input type="text" class="form-control" name="c_address">
            </div>

            <select class="form-select" name="paymethod" id="paytype">
                <option value="ATM" selected>ATM</option>
                <option value="刷卡">刷卡</option>
                <option value="貨到付款">貨到付款</option>
            </select>

            <input type="hidden" value="<?php echo $good_id ?>" name="good_id">           
            <input class="mt-3" type="submit" value="送出訂購單">

        </form>

    </body>
</html>
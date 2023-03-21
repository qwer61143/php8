<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");
    require_once("../method/bootstrap.html");

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);

    if (isset($_POST['cartaction']) && ($_POST['cartaction'] == "update")) {
        if (isset($_POST['updateid'])) {
            $i = count($_POST['updateid']);
            for($j = 0; $j < $i; $j++) {
                $good = $cart -> getItem($_POST['updateid'][$j]);
                $cart -> update($good['id'], $_POST['quantity'][$j], [
                    'name' => $good['attributes']['name'],
                    'price' => $good['attributes']['price'],
                ]);
            print_r($cart);
            }
        }
        header("Location:cart.php");
    }
    

    if(isset($_GET['cartaction']) && $_GET['cartaction'] == "remove"){
        $removeID = intval($_GET['removeid']);
        $cart -> remove($removeID);
       header("Location:cart.php");
    }

    if(isset($_GET['cartaction']) && $_GET['cartaction'] == "clear"){
        $cart -> clear();
        header("Location:cart.php");
    }
    
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        unset($_SESSION['u_id']);
        header("Location:user_login.php");
        exit;
    }
?>
 
    <?php if($cart->getTotalItem()> 0) { ?>
            <form action="" method="POST">
                <?php
                    $allItems = $cart -> getItems();
                    foreach($allItems as $items) {
                        foreach($items as $item) {
                         ?>
                            <a href="?cartaction=remove&removeid=<?php echo $item['id']; ?>">remove</a>
                            <?php echo $item['attributes']['name']; ?> 
                            <input type="hidden" name="updateid[]" value="<?php echo $item['id'] ?>">
                            <input type="text" name="quantity[]" value="<?php echo $item['quantity'] ?>">
                            <?php echo $item['attributes']['price']; ?>
                            <?php echo "總價是" ?>
                            <?php echo number_format($item['quantity'] * $item['attributes']['price']); ?>
                <?php }} ?>
                        <div>
                            <p>總共是:<?php echo $cart -> getAttributeTotal('price') ?></p>
                            <input type="hidden" name="cartaction" value="update">
                            <input type="submit" name="updatebtn" value="更新購物車">
                            <input type="button" name="clearbtn" value="清空購物車" onClick="window.location.href='?cartaction=clear'">
                            <input type="button" name="paybtn" value="結帳" onClick="window.location.href='checkout.php'">
                            <input type="button" name="backbtn" value="回上一頁" onClick="window.history.back();">
                        </div>
            </form>
    <?php }else { ?>
        <div>Cart is empty</div>
    <?php } ?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title>您的購物車</title>
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
        
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <img src="product_image.jpg" class="img-fluid" alt="Product Image">
                </div>
                <div class="col-3">
                    <h5 class="card-title">Product Name</h5>
                </div>
                <div class="col-2">
                    <p class="card-text">Price: $100</p>
                </div>
                <div class="col-2">
                    <p class="card-text">Quantity: 1</p>
                </div>
                <div class="col-2">
                    <p class="card-text">Total: $100</p>
                </div>
            </div>
        </div>

    </body>
</html>
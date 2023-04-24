<?php
    session_start();

    require_once("../method/connet.php");

    $query = $conn -> prepare("SELECT * FROM `orders` WHERE customer_id = :u_id");
    $query -> bindParam(":u_id", $_SESSION['u_id'], PDO::PARAM_INT);
    $query -> execute();

    $result = $query -> fetchAll();

    require_once("../method/bootstrap.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title>購物紀錄</title>
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
            <div class="container border border-dark mt-5">
                <div class="row">
                    <div class="col-3 d-flex align-items-center">
                        <!-- <img src="<?php echo $item['pic'] ?>" class="img-fluid" alt="Product Image"> -->
                    </div>
                    <div class="col-2 d-flex align-items-center text-center">
                        <div class="text-center" style="width: 100%;">
                            <a href="order_item.php?oid=<?php echo $item['order_id']; ?>">
                                <h5 class="card-title">訂單編號: <?php echo $item['order_id']; ?> </h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-center text-center">
                        <p class="card-text">總價: <?php echo $item['order_total']; ?></p>
                    </div>
                    <div class="col-2 d-flex align-items-center text-center">
                        <p class="card-text">訂購日期: <?php echo $item['order_date'] ?> </p>
                    </div>
                    <div class="col-1 d-flex align-items-center text-center">
                        <p class="card-text">狀態:</p>
                    </div>
                </div>
            </div>
        <?php } ?>

    </body>
</html>
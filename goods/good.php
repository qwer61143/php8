<?php
    session_start();

    require_once "../method/class.Cart.php";
    require_once "../lib/Connect.php";
    require_once "../lib/GetProduct.php";
    
    $good_id = $_GET['id'];

    $db = new Connect;
    $conn = $db->getConnect();
    
    $getGood = new GetGOOD($conn);
    $good = $getGood->getGood($good_id);

    $query = $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :good_seller");
    $query -> bindParam(":good_seller",$good['good_seller'],PDO::PARAM_INT);
    $query -> execute();
    $seller_query = $query -> fetch(PDO::FETCH_ASSOC);

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);
    
    if(isset($_POST['cartaction']) && $_POST['cartaction'] == 'add') {
        $cart -> add($_POST['id'], $_POST['quantity'],[
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'img' => $_POST['img']
        ]);
        // 紀錄是否成功加入購物車
        $_SESSION['cartAddSuccess'] = "已加入購物車";
        header("Location: " . $_SERVER['REQUEST_URI']); 
        exit;
    }
    // 定義$cartAddSuccess
    $cartAddSuccess = isset($_SESSION['cartAddSuccess']) ? $_SESSION['cartAddSuccess'] : "";
    unset($_SESSION['cartAddSuccess']);
 
    require_once "../method/SweetAlert2.html";
    require_once "../method/bootstrap.html";
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title><?php echo $good['good_name'] ?></title>
    </head>
    <body>

        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="container mt-5">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item me-3"><a href="/index.php" class="custom-a">首頁</a></li>
                    <li class="breadcrumb-item me-3"><a href="#" class="custom-a"><?php if(isset($good['good_category'])) { echo $good['good_category']; } ?></a></li>
                    <li class="breadcrumb-item me-3"><a href="#" class="custom-a"><?php if(isset($good['good_subcategory'])) { echo $good['good_subcategory']; } ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page" class="custom-a"><?php echo $good['good_name']; ?></li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-6 mt-5 mb-5">
                    <img class="good-img" src="<?php echo $good['good_pic'] ?>" alt="<?php echo $good['good_name'] ?>">
                </div>
                <div class="col-6 mt-5 mb-5">
                    <div class="card-body">
                        <div class="good-text-title">商品名:<?php echo $good['good_name'] ?></div>
                        <div class="good-text-title mt-2">售價:<?php echo '$'.$good['good_price'] ?></div>
                        <div class="good-text mt-4">商品資訊:<?php echo '$'.$good['good_info'] ?></div>
                        <div class="good-text mt-2 mb-5">已售:<?php echo $good['good_sold'] ?></div>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $good_id ?>">
                                <input type="hidden" name="name" value="<?php echo $good['good_name'] ?>"> 
                                <input type="hidden" name="price" value="<?php echo $good['good_price'] ?>">
                                <input type="hidden" name="img" value="<?php echo $good['good_pic'] ?>">
                                <input type="number" name="quantity" min="0" value="1">
                                <input type="hidden" name="cartaction" value="add">
                                <input type="submit" value="加入購物車">
                            </form>
                        <div class="good-text"><?php echo "上架時間:".$good['good_uptime']?></div>
                        <div class="good-text mt-2"><?php if(isset($good['good_category'])) { echo "商品分類:" . $good['good_category']; } ?></div>
                        <div class="good-text mt-2"><?php if(isset($good['good_brand'])) { echo "品牌:" . $good['good_brand']; } ?></div>
                    </div>
                </div>
            </div>
            <div class="divider-horizontal mt-5 mb-5"></div>
        </div>
        
        

        <div class="container">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-center align-items-center">
                    <img src="../imgs/seller.png" alt="Seller_Photo" class="custom-good-seller-img rounded-circle">
                    <p class="ms-3"><?php echo $seller_query['seller_name'] ?></p>
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
                            <p>接受的付款方式：<?php echo $seller_query['seller_paymethod'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once "../view/footer.php";
        ?>

        <?php if(isset($cartAddSuccess) && $cartAddSuccess != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '已加入購物車!',
                })

                setTimeout(() => {
                    <?php $cartAddSuccess = ""; ?>
                }, 1000);
            </script>
        <?php endif; ?>

    </body>
</html>
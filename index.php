<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("method/connet.php");
    require_once("method/class.Cart.php");
    require_once("method/bootstrap.html");

    $num_pages = 1;
    $page_records = 12;

    if(isset($_GET['page'])){
        $num_pages = $_GET['page'];
    }
    $start_records = ($num_pages - 1) * $page_records ;
    
    if(isset($_GET['keyword']) && $_GET['keyword'] != ""){
        $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_name LIKE ? or good_info LIKE ? ORDER BY good_id DESC");
        $keyword = "%".$_GET['keyword']."%";
        $query -> bindParam(1, $keyword, PDO::PARAM_STR);
        $query -> bindParam(2, $keyword, PDO::PARAM_STR);
    }elseif(isset($_GET['price1']) && isset($_GET['price2']) && ($_GET['price1'] <= $_GET['price2'])){
        $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_price BETWEEN ? AND ? ORDER BY good_price DESC");
        $query -> bindParam(1, $_GET['price1'], PDO::PARAM_INT);
        $query -> bindParam(2, $_GET['price2'], PDO::PARAM_INT);
    }else{
        $query = $conn -> prepare("SELECT * FROM `goods`");
    }
    $query -> execute();
    $result = $query -> fetchALL();

    $total_records = count($result);
    $total_pages = ceil($total_records / $page_records);

    function keepURL(){
        $keepURL = "";
        if(isset($_GET['keyword'])){
            $keepURL.="&keyword=".urlencode($_GET['keyword']);
        }
        if(isset($_GET['price1'])){
            $keepURL.="&price=".urlencode($_GET['price1']); 
        }
        if(isset($_GET['price2'])){
            $keepURL.="&price=".urlencode($_GET['price2']); 
        }
        return $keepURL;
    }

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        unset($_SESSION['u_id']);
        header("Location:index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .form-group {
                display: flex;
                flex-direction: column;
            }
            .search-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100px;
            }
            .navbar {
                font-size: 20px;
                padding: 20px;
            }
            .custom-img {
                width: 100%; /* 設定圖片寬度為 100% */
                height: 175px; /* 設定圖片高度為 200px */
                object-fit: cover; /* 使圖片保持比例填充區域 */
            }
        </style>
        <title>首頁</title>
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
                            <a class="nav-link active" aria-current="page" href="index.php">主頁</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/seller/seller_center.php">賣家中心</a>
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
                                    <li><a class="dropdown-item" href="user/user_center.php">用戶中心</a></li>
                                    <li><a class="dropdown-item" href="?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="/user/user_login.php">登入</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="form-group search-container mt-5">
            <form method="get" action="index.php" name="form1">
                <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="關鍵字">
                <button type="submit" class="btn btn-primary">搜尋</button>
                </div>
            </form>
            <form action="index.php" method="get" name="form2">
                <div class="input-group">
                <input type="text" name="price1" class="form-control me-2" placeholder="最低價格">
                <input type="text" name="price2" class="form-control me-2" placeholder="最高價格">
                <button type="submit" class="btn btn-primary">以價格區間查詢</button>
                </div>
            </form>
        </div>

    <?php 
        $query_str = $query -> queryString." LIMIT {$start_records}, {$page_records}";
        $query_limit = $conn -> prepare($query_str);

        if(isset($_GET['keyword']) && ($_GET['keyword'] != "")){
            $keyword = "%".$_GET['keyword']."%";
            $query_limit -> bindParam(1, $keyword, PDO::PARAM_STR);
            $query_limit -> bindParam(2, $keyword, PDO::PARAM_STR);
        }elseif(isset($_GET['price1']) && isset($_GET['price2']) && ($_GET['price1'] <= $_GET['price2'])){
            $query_limit -> bindParam(1, $_GET['price1'], PDO::PARAM_INT);
            $query_limit -> bindParam(2, $_GET['price2'], PDO::PARAM_INT);
        }
        $query_limit -> execute();
    ?>

        <div class="container mt-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-6 g-4">
                <?php while($limit_result = $query_limit -> fetch(PDO::FETCH_ASSOC)) :?>
                    <div class="col">
                        <div class="card">
                            <a href="goods/good.php?id=<?php echo $limit_result['good_id'] ?>">
                            <img src="<?php echo $limit_result['good_pic'] ?>" class="card-img-top custom-img" alt="...">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $limit_result['good_name']?></h5>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">$<?php echo $limit_result['good_price']?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile ?>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <?php if($num_pages>1) { ?>
                <a href="?page=1<?php echo KeepURL();?>">|&lt;</a>
                <a href="?page=<?php echo ($_GET['page'] - 1)?><?php echo keepURL();?>">&lt;&lt;</a>
            <?php }else {?>
                |&lt; &lt;&lt;
            <?php } ?>

            <?php
                for($i=1; $i<=$total_pages; $i++){
                    if($i == $num_pages){
                        echo $i;
                    }else {
                        $keepURL = KeepURL();
                        echo "<a href=\"?page=$i$keepURL\">$i<a>";
                    }
                }
            ?>

            <?php if($num_pages<$total_pages) { ?>
                <a href="?page=<?php echo ($num_pages + 1);?><?php echo KeepURL();?>">&gt;&gt;</a>
                <a href="?page=<?php echo $total_pages ?><?php echo keepURL();?>">&gt;|</a>
            <?php }else {?>
                &lt;&lt; &gt;|
            <?php } ?>
        </div>      
    </body>
</html>
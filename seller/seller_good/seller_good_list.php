<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("../../method/connet.php");
    require_once("../../method/bootstrap.html");

    $num_pages = 1;
    $page_records = 12;

    if(isset($_GET['page'])){
        $num_pages = $_GET['page'];
    }

    $start_records = ($num_pages - 1) * $page_records ;
    
    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_seller = :seller_id");
    $query -> bindParam(':seller_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $query -> execute();
    $result = $query -> fetchAll(PDO::FETCH_ASSOC);

    $total_records = count($result);
    $total_pages = ceil($total_records / $page_records);

    $query_str = $query -> queryString." LIMIT {$start_records}, {$page_records}";
    $query_limit = $conn -> prepare($query_str);
    $query_limit -> bindParam(':seller_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $query_limit -> execute();

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['seller_name']);
        unset($_SESSION['seller_id']);
        header("Location:../../index.php");
        exit;
    }
?>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>您的產品列表</title>
    <link rel="stylesheet" href="../../css/css.css" type="text/css">
    <style>
    .custom-img {
        width: 100%; /* 設定圖片寬度為 100% */
        height: 200px; /* 設定圖片高度為 200px */
        object-fit: cover; /* 使圖片保持比例填充區域 */
    }
    </style>
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
        
        <div class="container mt-5">
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">
                <?php while($limit_result = $query_limit -> fetch(PDO::FETCH_ASSOC)) :?>
                    <div class="col">
                        <div class="card">
                            <a href="good_update.php?gid=<?php echo $limit_result['good_id'] ?>">
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
    </body>
</html>
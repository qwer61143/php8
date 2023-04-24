<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once "lib/Util.php";
    require_once "lib/Connect.php";
    require_once "method/class.Cart.php";

    use MyApp\Utils\Util;

    $db = new Connect;
    $conn = $db->getConnect();

    $query_str = "SELECT * FROM `goods`";

    $query_new = $query_str . "ORDER BY good_uptime DESC LIMIT 0,12";
    $query_new = $conn->prepare($query_new);
    $query_new->execute();
    $newProduct = $query_new->fetchAll();
 
    $query_hot = $query_str . "ORDER BY good_sold DESC LIMIT 0,12";
    $query_hot = $conn->prepare($query_hot);
    $query_hot->execute();
    $hotProduct = $query_hot->fetchAll();

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        Util::logout();
    }

    require_once "method/bootstrap.html";
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/css.css">
        <title>首頁</title>
    </head>
    <body>
      
    <?php 
        require_once "view/navbar.php"
    ?>

    <div class="container">
        <div class="row mt-3 mb-3">
            <div class="ms-2 mt-3 mb-3 custom-title">新品上架</div>
            <div class="col-12">
                <div class="multiple-items d-flex justify-content-center">
                    <?php foreach($newProduct as $new) : ?>
                        <div class="card rounded-0 border-0 custom-card m-2">
                            <div class="d-flex justify-content-center ">
                                <a href="/goods/good.php?id=<?php echo $new['good_id'] ?>">
                                    <img src="<?php echo $new['good_pic'] ?>" class="custom-img border border-1" alt="product-img">
                                </a>
                            </div> 
                            <div class="card-body custom-card-body">
                                <p class="card-text"><?php echo $new['good_name'] ?></p>
                                <p class="card-text custom-card-price"><?php echo "$" . $new['good_price']?></p>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <div class="divider-horizontal"></div>

        <div class="row mt-3">
            <div class="ms-2 mt-3 mb-3 custom-title border-2">熱銷商品</div>
            <div class="col-12">
                <div class="multiple-items d-flex justify-content-center">
                    <?php foreach($hotProduct as $hot) : ?>
                        <div class="card rounded-0 border-0 custom-card m-3">
                            <div class="d-flex justify-content-center">
                                <a href="/goods/good.php?id=<?php echo $hot['good_id'] ?>">
                                    <img src="<?php echo $hot['good_pic'] ?>" class="custom-img border border-1" alt="...">
                                </a>
                            </div>
                            <div class="card-body custom-card-body">
                                <p class="card-text"><?php echo $hot['good_name'] ?></p>
                                <p class="card-text custom-card-price"><?php echo "$" . $hot['good_price']?></p>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>

    <?php 
        require_once "view/footer.php"
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <!-- 輪播功能 -->
    <script type="text/javascript">
           $(document).ready(function() {
                $('.multiple-items').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev border-0 me-3"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next border-0 ms-3"><i class="fas fa-chevron-right"></i></button>'
                });
            });
    </script>

    </body>
</html>
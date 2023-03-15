<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once("method/connet.php");
    require_once("method/class.Cart.php");

    $num_pages = 1;
    $page_records = 5;

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
?>


<!DOCTYPE html>
<html lang="zh-TW">
    <head>
    <meta charset="utf8">
    <title>首頁</title>
    </head>
    <body>
        <div><a href="/user/user_login.php">用戶登入</a></div>
        <div>
            <form method="get" action="index.php" name="form1">
                <input type="text" name="keyword" value="關鍵字">
                <input type="submit" value="搜尋">
            </form>
            <form action="index.php" method="get" name="form2">
                <input type="text" name="price1" value="0">
                <input type="text" name="price2" value="0">
                <input type="submit" value="以價格區間查詢">
            </form>
        </div>
        <div><a href="cart/cart.php">我的購物車</a></div>

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

     <?php while($limit_result = $query_limit -> fetch(PDO::FETCH_ASSOC)) :?>
        <div>
            <?php echo $limit_result['good_id'] ?>
            <a href="goods/good.php?id=<?php echo $limit_result['good_id'] ?>"><img src=<?php echo $limit_result['good_pic'] ?> width='200' heigh='200' ></a>
            <?php echo $limit_result['good_name']?>
            <?php echo $limit_result['good_price']?>
        </div>
    <?php endwhile ?>

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

    </body>
</html>
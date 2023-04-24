<?php
    session_start();

    require_once "../lib/Util.php";
    require_once "../lib/Connect.php";
    require_once "../method/class.Cart.php";

    use MyApp\Utils\Util;

    $db = new Connect;
    $conn = $db->getConnect();

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
        Util::logout();
    }

    require_once("../method/bootstrap.html");


    $num_pages = 1;
    $page_records = 12;

    if (isset($_GET['page'])) {
        $num_pages = $_GET['page'];
    }
    $start_records = ($num_pages - 1) * $page_records;

    $query_str = "SELECT * FROM `goods`";
    $where_conditions = [];

    if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
        $where_conditions[] = "(good_name LIKE ? OR good_info LIKE ?)";
    }

    if (isset($_GET['price1']) && isset($_GET['price2']) && ($_GET['price1'] <= $_GET['price2'])) {
        $where_conditions[] = "good_price BETWEEN ? AND ?";
    }

    if (count($where_conditions) > 0) {
        $query_str .= " WHERE " . implode(" AND ", $where_conditions);
    }
    $query_str .= " ORDER BY good_price DESC";

    $query = $conn->prepare($query_str);

    $param_index = 1;

    if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
        $keyword = "%" . $_GET['keyword'] . "%";
        $query->bindParam($param_index++, $keyword, PDO::PARAM_STR);
        $query->bindParam($param_index++, $keyword, PDO::PARAM_STR);
    }

    if (isset($_GET['price1']) && isset($_GET['price2']) && ($_GET['price1'] <= $_GET['price2'])) {
        $query->bindParam($param_index++, $_GET['price1'], PDO::PARAM_INT);
        $query->bindParam($param_index++, $_GET['price2'], PDO::PARAM_INT);
    }

    $query->execute();
    $result = $query->fetchAll();

    $total_records = count($result);
    $total_pages = ceil($total_records / $page_records);

    $query_str .= " LIMIT {$start_records}, {$page_records}";
    $query_limit = $conn->prepare($query_str);
    $query_limit->execute();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/css.css">
    <title>search</title>
</head>
<body>
    <?php require_once "../view/navbar.php" ?>
</body>
</html>

<div class="form-group search-container mt-5">
       <form action="index.php" method="get" name="form2">
           <div class="input-group">
           <input type="text" name="price1" class="form-control me-2" placeholder="最低價格">
           <input type="text" name="price2" class="form-control me-2" placeholder="最高價格">
           <button type="submit" class="btn btn-primary">以價格區間查詢</button>
           </div>
       </form>
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
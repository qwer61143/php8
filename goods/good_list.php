<?php
    require_once("../method/connet.php");

    $page_records = 12;
    $num_pages = 1;

    if(isset($_GET['page']) && $_GET['page'] != "") {
        $num_pages = $_GET['page']; 
    }

    $start_records = ($num_pages -1) * $page_records;

    $query = $conn->prepare("SELECT * FROM `goods` ORDER BY good_price DESC");
    $query_limit = $conn->prepare("SELECT * FROM `goods` ORDER BY good_price DESC LIMIT {$start_records}, {$page_records}");

    $query->execute();
    $query_limit->execute();

    $total_records = $query->rowCount();
    $total_pages = ceil($total_records/$page_records);
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>產品列表</title>
    </head>
    <body>
        <?php
            require_once "../view/navbar.php"
        ?>

        <?php while($goods_data = $query_limit -> fetch(PDO::FETCH_ASSOC)) : ?>
            <div>
                <a href="good.php?id=<?php echo $goods_data['good_id']?>">
                <img src=<?php echo $goods_data['good_pic'] ?> width='200' heigh='200' >
                </a>
            </div>
        <?php endwhile ?>

        <?php if($num_pages>1) { ?>
            <a href="?page=1">first page</a> | <a href="?page=<?php echo $num_pages-1 ?>">previous page</a> 
        <?php } ?>
        <?php if($num_pages<$total_pages) {?>
            <a href="?page=<?php echo $num_pages +1 ?>">next page</a> | <a href="?page=<?php echo $total_pages?>">last page</a>
        <?php } ?>
 
    </body>

</html>


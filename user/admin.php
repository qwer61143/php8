<?php
    session_start();
    require_once("../method/connet.php");

    if(!isset($_SESSION['u_name']) || ($_SESSION["u_name"] == "")){
        header("Location:user_login.php");
        exit;
    }
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        header("Location:user_login.php");
        exit;
    }
    if(isset($_SESSION['u_level']) && ($_SESSION['u_level'] == "member")){
        header("Location: user_center.php");
    }
    if(isset($_GET['action']) && ($_GET['action']) == "delete"){
        $delete = $conn -> prepare("DELETE FROM `userdata` WHERE u_id = ? ");
        $delete -> execute(array($_GET['id']));
    }

    $page_records = 5;
    $num_pages = 1;

    if(isset($_GET['page'])){
        $num_pages = $_GET['page']; 
    }
    $start_records = ($num_pages -1) * $page_records;
    
    $query = $conn -> prepare("SELECT * FROM `userdata` WHERE u_level = 'member' ORDER BY u_jointime DESC");
    $query_limit =  $conn -> prepare("SELECT * FROM `userdata` WHERE u_level = 'member' ORDER BY u_jointime DESC LIMIT {$start_records}, {$page_records}");
    $query_limit -> execute();
    $query -> execute();
    $unlimit_result = $query -> fetchAll(PDO::FETCH_ASSOC);

    $total_records = $query -> rowCount();
    $total_pages = ceil($total_records/$page_records);
?>
<html>
    <head lang="zh-TW">
        <meta charset="utf8">
        <title>管理者系統</title>
        <script>
            function deleteuser() {
                if(confirm('確定要刪除嗎')){
                    return true;
                }else{
                    return false;
                }
            }
        </script>
    </head>
    <body>
    <?php while($limit_result = $query_limit -> fetch(PDO::FETCH_ASSOC)) : ?>
        <div>
            <a href="admin_update.php?id=<?php echo $limit_result['u_id'] ?>">修改資料</a>
            <a href="?action=delete&id=<?php echo $limit_result['u_id']?>" onclick="return deleteuser();">刪除</a>
            <?php echo $limit_result['u_id']?>
            <?php echo $limit_result['u_name']?>
            <?php echo $limit_result['u_cname']?>
            <?php echo $limit_result['u_phone']?>
            <?php echo $limit_result['u_sex']?>
            <?php echo $limit_result['u_birthday']?>
            <?php echo $limit_result['u_jointime']?>
        </div>
    <?php endwhile ?>
    <?php 
        echo "資料筆數:".$total_records;
    ?>
        <?php if($num_pages>1) { ?>
            <a href="?page=1">1</a> | <a href="?page=<?php echo $num_pages-1 ?>">previous page</a> 
        <?php } ?>
        <?php if($num_pages<$total_pages) {?>
            <a href="?page=<?php echo $num_pages +1 ?>">next page</a> | <a href="?page=<?php echo $total_pages?>">last page</a>
        <?php } ?>
    <div><a href = "?logout=true">logout</a></div>
    </body>

</html>

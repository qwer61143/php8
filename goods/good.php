<?php
    require_once("../method/connet.php");

    $good_id = $_GET['id'];
    $select = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = ? ");
    $select -> execute(array($good_id));
    $good = $select -> fetch(PDO::FETCH_ASSOC);
    
    echo $good['good_name'].'<br>';
    echo $good['good_price'].'<br>';
    echo $good['good_uptime'].'<br>';
?>
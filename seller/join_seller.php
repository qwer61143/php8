<?php 
    require_once("../method/connet.php");
    session_start();

    $query = $conn -> prepare("SELECT * FROM `seller` WHERE seller_id = :u_id");
    $query -> bindParam('u_id', $_SESSION['u_id'], PDO::PARAM_INT);
    $query ->execute();
    $query_result = $query -> fetch(PDO::FETCH_ASSOC);

    if($_SESSION['u_id'] == $query_result['seller_id']){
        header("Location: seller_center.php");
        exit();
    }else{
        $join = $conn -> prepare("INSERT INTO `seller`(seller_id) VALUES (:u_id)");
        $join -> bindParam(':u_id', $_SESSION['u_id'], PDO::PARAM_INT);
        $join -> execute();
        header("Location: seller_center.php");
        exit();
    }
?>
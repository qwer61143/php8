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
        unset($_SESSION['u_id']);
        header("Location:user_login.php");
        exit;
    }
?>
<div><a href = "user_update.php">update</a></div>
<div><a href = "?logout=true">logout</a></div>
<div><a href = "../seller/join_seller.php">be seller</a></div>
<div><a href = "../seller/seller_center.php">seller center</a></div>
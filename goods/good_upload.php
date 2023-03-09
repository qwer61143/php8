<?php
    require_once("../method/connet.php");

    function data_in($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $good_name = $good_pic = $good_price = $good_info = $good_total = "";
    $priceErr = $totalErr = "";

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(empty($_POST['good_name'])){
            echo "請輸入商品名稱";
        }else {
            $good_name = data_in($_POST['good_name']);
        }

        if(empty($_POST['good_price'])){
            echo "請輸入商品價格";
        }else {
            $good_price = data_in($_POST["good_price"]);
            if(!preg_match("/^[0-9]*$/", $good_price)){
                $priceErr = "請輸入正確價格";
        }
        if(empty($_POST['good_total'])){
            echo "請輸入商品數量";
        }else {
            $good_total = data_in($_POST['good_total']);
            if(!preg_match("/^[0-9]*$/", $_POST['good_total'])){
                $totalErr = "只能輸入數字";
            }
        if(!empty($_POST['good_info'])){
            $good_info = data_in($_POST['good_info']);
        }
        }

        $insert = $conn -> prepare("INSERT INTO `goods`(good_name, good_pic, good_price, good_total, good_info, good_uptime) VALUES (?,?,?,?,?,NOW())");
        $insert -> execute(array($good_name, $good_pic, $good_price, $good_total, $good_info));
    }
    }

?>
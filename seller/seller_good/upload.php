<?php
    require_once("../../method/connet.php");

    function data_in($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $good_name = $good_pic = $good_price = $good_info = $good_total = $good_seller = "";
    $nameErr = $priceErr = $totalErr = "";

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(empty($_POST['good_name'])){
            $nameErr = "請輸入商品名稱";
            header("Location: good_upload.php?nameErr=$nameErr");
            exit();
        }else {
            $good_name = data_in($_POST['good_name']);
        }
        
        if($_FILES['good_pic']['error'] === UPLOAD_ERR_OK){
            if($_FILES['good_pic']['size']>10485760){
                echo $_FILES['good_pic']['error'];
                //$picErr = "檔案過大,只能10mb";
            }else{

                $image = $_FILES['good_pic']['tmp_name'];
                $handle = fopen($image,"rb");
                $image = base64_encode(fread($handle,filesize($image)));
                $curl_post = array('image' => $image , 'title' => $good_name);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image');
                curl_setopt($curl, CURLOPT_TIMEOUT , '30');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . "c5f40afed18aaa5"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                //curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post );
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 'true');

                $curl_result = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($curl_result, true);

                if($result['success'] == 'true'){
                    $good_pic = $result['data']['link'];
                }else {
                    echo $result['status'];
                }
            }
        }else {
            echo "檔案錯誤代碼".$_FILES['good_pic']['error'];
        }

        if(empty($_POST['good_price'])){
            $priceErr = "請輸入商品價格";
            header("Location: good_upload.php?priceErr=$priceErr");
            exit();
        }else {
            $good_price = data_in($_POST["good_price"]);
            if(!preg_match("/^[0-9]*$/", $good_price)){
                $priceErr = "請輸入正確價格";
                header("Location: good_upload.php?priceErr=$priceErr");
                exit();
            }
        }

        if(empty($_POST['good_total'])){
            $totalErr = "請輸入商品數量";
            header("Location: good_upload.php?totalErr=$totalErr");
            exit();
        }else {
            $good_total = data_in($_POST['good_total']);
            if(!preg_match("/^[0-9]*$/", $_POST['good_total'])){
                $totalErr = "只能輸入數字";
                header("Location: good_upload.php?totalErr=$totalErr");
                exit();
            }
        }
        if(!empty($_POST['good_info'])){
            $good_info = data_in($_POST['good_info']);
        }
        $seller_id = $_POST['good_seller'];

        $insert = $conn -> prepare("INSERT INTO `goods`(good_name, good_pic, good_price, good_total, good_info, good_uptime, good_seller) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
        $insert -> execute(array($good_name, $good_pic, $good_price, $good_total, $good_info, $seller_id));
        $good_id = $conn -> lastInsertId();

        $update = $conn -> prepare("UPDATE `seller` SET goods = CONCAT(goods, ',', :good_id) WHERE seller_id = :seller_id");
        $update -> bindParam(':good_id', $good_id, PDO::PARAM_INT);
        $update -> bindParam(':seller_id', $seller_id ,PDO::PARAM_INT);
        $update -> execute();
    }
?>
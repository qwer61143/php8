<?php
    session_start();
    require_once("../method/connet.php");

    function data_in($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['action']) && ($_POST['action'] == "update")){

        $good_name = data_in($_POST['good_name']);
        $good_price = data_in($_POST['good_price']);
        $good_total = data_in($_POST['good_total']);
        $good_info = data_in($_POST['good_info']);
        $good_id = data_in($_GET['gid']);
    
        if($_FILES['good_pic']['error'] === UPLOAD_ERR_OK){

            if($_FILES['good_pic']['size']>10485760){
                echo $_FILES['good_pic']['error'];
            }else {

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

        $update = $conn -> prepare("UPDATE `goods` SET good_name= ?, good_pic= ?, good_price= ?, good_total= ?, good_info= ? WHERE good_id =?");
        $update -> execute(array($good_name,$good_pic,$good_price,$good_total,$good_info,$good_id));

        }
    
    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = :good_id");
    $query -> bindParam(':good_id', $_GET['gid'], PDO::PARAM_INT);
    $query -> execute();
    $result = $query -> fetch(PDO::FETCH_ASSOC);

?>
<html lang="zh-TW">
    <head>
        <meta charset="utf8">
        <title><?php echo $result['good_name'] ?></title>
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <div><img src="<?php echo $result['good_pic'] ?>" width='200' heigh='200'></div>
            <div>商品名稱<input type="text" name="good_name" value="<?php echo $result['good_name']?>"></div>
            <div>更換商品圖片<input type="file" name="good_pic"></div>
            <div>售價<input type="text" name="good_price" value="<?php echo $result['good_price']?>"></div>
            <div>商品數量<input type="number" name="good_total" value="<?php echo $result['good_total']?>"></div>
            <div>商品資訊<textarea name="good_info" row="5" cols="20" ><?php echo $result['good_info'] ?></textarea></div>
            <input type="hidden" name="action" value="update">
            <input type="submit" value="更新">
        </form>
    </body>
</html>
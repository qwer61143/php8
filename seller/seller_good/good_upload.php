<?php
    session_start();

    require_once "../../lib/Connect.php";
    require_once "../../lib/Goods.php";

    if(!isset($_SESSION['seller_id'])){
        header("Location: ../seller_login.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $good_pic = "";

        if(isset($_FILES['good_pic'])) {
            if($_FILES['good_pic']['error'] === UPLOAD_ERR_OK) {
                if($_FILES['good_pic']['size']>10485760) {
                    $errors['good_pic'] = "照片過大，只能上傳10Mb以下圖片";
                }else {
                    $image = $_FILES['good_pic']['tmp_name'];
                    $handle = fopen($image,"rb");
                    $image = base64_encode(fread($handle,filesize($image)));
                    $curl_post = array('image' => $image , 'title' => $_POST['good_name']);

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
                // echo "檔案錯誤代碼".$_FILES['good_pic']['error'];
            }
        }

        $good = [
            'good_name' => $_POST['good_name'],
            'good_pic' => $good_pic,
            'good_price' =>$_POST['good_price'],
            'good_total' => $_POST['good_total'],
            'good_info' => $_POST['good_info'],
            'good_category' => $_POST['good_category'],
            'good_brand' => $_POST['good_brand'],
            'good_seller' => $_SESSION['seller_id']
        ];

        $db = new Connect;
        $conn = $db->getConnect();

        $good_data = new Good($conn);
        $good_data->setGoodData($good);
        $errors = $good_data->validateGoodsData();

        if(empty($errors)) {
            $insert = $conn->prepare("INSERT INTO `goods`(good_name, good_pic, good_price, good_total, good_info, good_category, good_brand, good_uptime, good_seller) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
            $insert -> execute(array_values($good));
            $good_id = $conn -> lastInsertId();

            $update = $conn -> prepare("UPDATE `seller` SET goods = CONCAT(goods, ',', :good_id) WHERE seller_id = :seller_id");
            $update -> bindParam(':good_id', $good_id, PDO::PARAM_INT);
            $update -> bindParam(':seller_id', $good['good_seller'] ,PDO::PARAM_INT);
            $update -> execute();

            $success = "上傳成功!";
        }else {
            $upload_failed = "上傳失敗!";
        }
    }

    require_once "../../method/SweetAlert2.html";
    require_once "../../method/bootstrap.html";
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../../css/css.css" type="text/css">
        <title>商品上傳</title>
    </head>
    <body>
     
        <?php
            require_once "../../view/navbar.php";
        ?>

        <div class="container">
            <div class="row">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <img src="../../imgs/addgood.png" alt="addgood" title="上傳商品" class="add-good-logo mt-5">
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column justify-content-center text-center mt-5">
                        <form class="" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="good_name">商品名稱</label>
                                <input type="text" class="form-control" id="good_name" name="good_name">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_name'])) { echo $errors['good_name'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_pic">商品圖片</label>
                                <input type="file" class="form-control" id="good_pic" name="good_pic">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_pic'])) { echo $errors['good_pic'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_price">商品價格</label>
                                <input type="number" class="form-control" id="good_price" name="good_price">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_price'])) { echo $errors['good_price'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_total">商品數量</label>
                                <input type="number" class="form-control" id="good_total" name="good_total">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_total'])) { echo $errors['good_total'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_info">商品描述</label>
                                <input type="text" class="form-control" id="good_info" name="good_info">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_info'])) { echo $errors['good_info'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_category">商品分類</label>
                                <input type="text" class="form-control" id="good_category" name="good_category">
                            </div>
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_category'])) { echo $errors['good_category'];} ?>
                            </div>

                            <!-- <div class="form-group">
                                <label for="good_subcategory">商品副分類</label>
                                <input type="text" class="form-control" id="good_subcategory" name="good_subcategory">
                            </div> -->
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['good_subcategory'])) { echo $errors['good_subcategory'];} ?>
                            </div>

                            <div class="form-group">
                                <label for="good_brand">商品品牌</label>
                                <input type="text" class="form-control" id="good_brand" name="good_brand">
                            </div>
                                <div class="error-message"> 
                                    <?php if(isset($errors) && !empty($errors['good_brand'])) { echo $errors['good_brand'];} ?>
                                </div>
                            <button type="submit" class="btn btn-primary mt-3">上傳</button>
                                <div class="error-message"> 
                                    <?php if(isset($errors) && !empty($errors['good_brand'])) { echo $errors['good_brand'];} ?>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
  
        <?php
            require_once "../../view/footer.php"
        ?>

        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire(
                    '上傳成功!',
                )
            </script>
        <?php endif; ?>

        <?php if(isset($upload_failed) && $upload_failed != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    icon: 'error',
                    title: '上傳失敗!',
                })
            </script>
        <?php endif; ?>

    </body>
</html>

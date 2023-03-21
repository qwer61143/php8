<?php
    session_start();

    require_once("../../method/connet.php");
    require_once("../../method/bootstrap.html");

    function data_in($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $query = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = :good_id");
    $query -> bindParam(':good_id', $_GET['gid'], PDO::PARAM_INT);
    $query -> execute();
    $result = $query -> fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['action']) && ($_POST['action'] == "update")){

        $good_name = data_in($_POST['good_name']);
        $good_price = data_in($_POST['good_price']);
        $good_total = data_in($_POST['good_total']);
        $good_info = data_in($_POST['good_info']);
        $good_id = data_in($_GET['gid']);
    
        if(!empty($_FILES['good_pic']['name']) && $_FILES['good_pic']['error'] === UPLOAD_ERR_OK){

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
                $img_result = json_decode($curl_result, true);

                if($img_result['success'] == 'true'){
                    $good_pic = $img_result['data']['link'];
                }else {
                    echo $img_result['status'];
                }
            }
        }else {
            $good_pic = $result['good_pic'];
            if ($_FILES['good_pic']['error'] != UPLOAD_ERR_NO_FILE) {
                echo "檔案錯誤代碼" . $_FILES['good_pic']['error'];
            }
        }

        $update = $conn -> prepare("UPDATE `goods` SET good_name= ?, good_pic= ?, good_price= ?, good_total= ?, good_info= ? WHERE good_id =?");
        $update -> execute(array($good_name,$good_pic,$good_price,$good_total,$good_info,$good_id));

        header("Location: good_update.php?gid=" . $good_id);
        exit;
        }
    
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['seller_name']);
        unset($_SESSION['seller_id']);
        header("Location:../../index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../../css/css.css" type="text/css">
        <style>
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .image-container img {
            max-width: 40vw;
            max-height: 40vw;
            width: auto;
            height: auto;
        }
        </style>
        <title>商品更新</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 position-relative">
                <div class="container-fluid">
                    <a class="navbar-brand" href="../index.php">InsideTech</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../../index.php">主頁</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">更改語言</a>                
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">繁體中文</a></li>
                                    <li><a class="dropdown-item" href="#">英文</a></li>
                                    <li>
                                    <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">更多</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link " href="../../contact.php">需要幫助嗎?</a>
                            </li>
                        
                            <li class="nav-item dropdown">
                            <?php if (isset($_SESSION['seller_name']) && $_SESSION['seller_name'] != "") { ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['seller_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="../seller_login.php">登入</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="centered-form">
            <form class="w-50" method="POST" action="" enctype="multipart/form-data">
                <div class="text-center  mb-5">
                    <span><?php echo $result['good_name']?></span>
                </div>
                <div class="image-container mb-5">
                    <img src="<?php echo $result['good_pic'] ?>">
                </div>
                <div class="form-group">
                    <label for="good_name">商品名稱</label>
                    <input type="text" class="form-control" id="good_name" name="good_name" value="<?php echo $result['good_name']?>">
                </div>
                <div class="form-group">
                    <label for="good_pic">商品圖片</label>
                    <input type="file" class="form-control" id="good_pic" name="good_pic" value="<?php echo $result['good_pic'] ?>">
                </div>
                <div class="form-group">
                    <label for="good_price">商品價格</label>
                    <input type="number" class="form-control" id="good_price" name="good_price" value="<?php echo $result['good_price']?>">
                </div>
                <div class="form-group">
                    <label for="good_total">商品數量</label>
                    <input type="number" class="form-control" id="input4" name="good_total" value="<?php echo $result['good_total']?>">
                </div>
                <div class="form-group">
                    <label for="good_info">商品描述</label>
                    <input type="text" class="form-control" id="good_info" name="good_info" value="<?php echo $result['good_info'] ?>">
                </div>
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
    </body>
</html>
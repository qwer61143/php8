<?php
    session_start();
    
    require_once("../method/bootstrap.html");
    
    unset($_SESSION['u_name']);
    unset($_SESSION['u_level']);
    unset($_SESSION['u_id']);

    function data_in($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $u_phone = $u_email = $u_name = $u_password = "";
    $u_emailErr = $u_phoneErr = $u_passwordErr = $u_nameErr= "";

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(empty($_POST['u_phone'])) {
            echo "請輸入手機號碼";
        }else {
            $u_phone = data_in($_POST['u_phone']);
            if(!preg_match("/^[0-9]*$/",$u_phone)) {
                $u_phoneErr = "請輸入正確的手機號碼";
            }
        }
        if(empty($_POST['u_email'])) {
            echo "請輸入電子郵件地址";
        }
        else{
            $u_email = data_in($_POST['u_email']);
            if(!filter_var($u_email, FILTER_VALIDATE_EMAIL)){
                $u_emailErr = "請輸入有效的電子郵件地址";
            }
        }
        if(empty($_POST['u_name'])) {
            echo "請輸入帳號名稱";
        }else {
            $u_name = data_in($_POST['u_name']);
            if(!preg_match("/^[A-Za-z0-9]*$/",$u_name)) {
                $u_nameErr = "帳號只允許英文及數字";
            }
        }
        if(empty($_POST['u_password'])){
            echo "請輸入密碼";
        }else {
            $u_password = data_in($_POST['u_password']);
            if(!preg_match("/^[A-Za-z0-9]*$/",$u_password)){
                $u_passwordErr = "密碼只允許數字及英文";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <style>
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        </style>
        <title>會員註冊系統</title>
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
                            <p class="nav-link">用戶註冊</p>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
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
                </div>
            </div>
        </nav>

        <div class="centered-form">
            <form class="w-50" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group">
                    <label for="input1">請輸入手機號碼</label>
                    <input type="text" class="form-control" id="input1" name="u_phone"><?php echo $u_phoneErr?>
                </div>
                <div class="form-group">
                    <label for="input2">請輸入電子郵件地址</label>
                    <input type="text" class="form-control" id="input2" name="u_email"><?php echo $u_emailErr?>
                </div>
                <div class="form-group">
                    <label for="input3">請輸入使用者名稱</label>
                    <input type="text" class="form-control" id="input3" name="u_name"><?php echo $u_nameErr?>
                </div>
                <div class="form-group">
                    <label for="input4">請輸入密碼</label>
                    <input type="text" class="form-control" id="input4" name="u_password"><?php echo $u_passwordErr?>
                </div>
                <button type="submit" class="btn btn-primary">註冊</button>
            </form>
        </div>

        <?php
        if($u_phone && $u_email && $u_name && $u_password != ""){
            if(empty($u_emailErr) && empty($u_phoneErr) && empty($u_passwordErr) && empty($u_nameErr)) {
                require_once("../method/connet.php");
                try{
                    $insert = $conn -> prepare("INSERT INTO `userdata`(u_phone, u_email, u_name, u_password, u_jointime) VALUES (?,?,?,?,NOW())");
                    $insert -> execute(array($u_phone, $u_email, $u_name, password_hash($u_password, PASSWORD_BCRYPT)));
                    header("Location:user_center.php");
                    exit;
                } catch(PDOException $e){
                    echo "insert FAILED". $e -> getMessage();
                }
            }
        }
        ?>
    </body>
</html>
<?php
    session_start();

    require_once "../lib/Connect.php";
    require_once "../lib/Validator.php";

    if(isset($_SESSION['seller_name']) && $_SESSION['seller_name']!=""){
        header("Location: seller_center.php");
        exit;
    }
    
    if(isset($_POST['seller_name']) && isset($_POST['seller_password'])) {
        $seller_name = $_POST['seller_name'];
        $seller_password = $_POST['seller_password'];

        $db = new Connect;
        $conn = $db->getConnect();

        $check = new SellerLoginCheck($conn);
        $check->loginCheck($seller_name, $seller_password);

        $errors = $check->getErrors();

        if(empty($errors)) {
                $success = "登入成功";
        }else {
            if(!empty($errors['seller_name']) && $errors['seller_name'] == "*") {
                $login_failed = "請輸入手機號碼!";
            }else if(!empty($errors['seller_password']) && $errors['seller_password'] == "*") {
                $login_failed = "請輸入密碼";
            }else {
                $login_failed = "帳號或密碼輸入錯誤!";
            }
        }
    }

    require_once "../method/SweetAlert2.html";
    require_once "../method/bootstrap.html";
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/css.css">
        <title>賣家登入</title>
    </head>
    <body>
        <?php
            require_once "../view/navbar.php"
        ?>
            <div class="d-flex flex-column justify-content-center align-items-center text-center mt-5">
                <img src="../imgs/seller.png" alt="" class="seller-logo mb-3">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                    <input class="form-control my-3" type="text" placeholder="請輸入商店帳號" name="seller_name" value="<?php if (!empty($_COOKIE['seller_name'])) echo $_COOKIE['seller_name'] ?>">
                    <div class="error-message">
                        <?php if(!empty($errors['seller_name'])) { echo $errors['seller_name'];} ?>
                    </div>
                    <input class="form-control my-3" type="password" placeholder="請輸入密碼" name="seller_password" value="<?php if (!empty($_COOKIE['seller_password'])) echo $_COOKIE['seller_password'] ?>">
                    <div class="error-message">
                        <?php if(!empty($errors['seller_password'])) { echo $errors['seller_password'];} ?>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberme" name="rememberme" value="true" checked>
                        <label class="form-check-label" for="rememberme">Remember me?</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">送出</button>
                </form>
                <!-- <div class="mt-3"><a href="forgetpassword.php">忘記密碼</a></div> -->
                <div><a href="join_seller.php">成為賣家</a></div>
            </div>

        <?php
            require_once "../view/footer.php"
        ?>

        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '登入成功!',
                    text: '三秒後導向賣家中心',
                    icon: 'success',
                    timer: 3000, // 計時器（毫秒）
                }).then(() => {
                    // 重定向至 login.php
                    window.location.href = "seller_center.php";
                });
            </script>
        <?php endif; ?>

        <?php if(isset($login_failed) && $login_failed != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $login_failed ?>!',
                })
            </script>
        <?php endif; ?>

        
    </body>
</html>

<?php
    session_start();
    
    require_once "../lib/Connect.php";
    require_once "../lib/Validator.php";

    $login_failed = "";

    if(isset($_SESSION['name']) && $_SESSION['name']!=""){
        if($_SESSION['level'] == "member"){
            header("Location: user_center.php");
            exit;
        }else{
            header("Location: admin.php");
            exit;
        }
    }
    
    if (isset($_POST['phone']) && isset($_POST['password'])) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];

        $db = new Connect;
        $conn = $db->getConnect();

        $check = new LoginCheck($conn);
        $check->loginCheck($phone, $password);

        $errors = $check->getErrors();

        if(empty($errors)) {
            if($_SESSION['level'] == "admin"){
                header("Location: admin.php");
                exit;
            }else {
                $success = "登入成功";
            }
        }else {
            if(!empty($errors['phone']) && $errors['phone'] == "*") {
                $login_failed = "請輸入手機號碼!";
            }else if(!empty($errors['password']) && $errors['password'] == "*") {
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
        <title>會員登入</title>
    </head>
    <body>
        <?php
            require_once "../view/navbar.php"
        ?>
            <div class="d-flex flex-column justify-content-center align-items-center text-center mt-5">
                <img src="../imgs/user.png" alt="userlogin" title="用戶登入" class="userlogin-logo">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                    <input class="form-control my-3" type="text" placeholder="請輸入手機號碼" name="phone" value="<?php if (!empty($_COOKIE['phone'])) echo $_COOKIE['phone'] ?>">
                    <div class="error-message">
                        <?php if(!empty($errors['phone'])) { echo $errors['phone'];} ?>
                    </div>
                    <input class="form-control my-3" type="password" placeholder="請輸入密碼" name="password" value="<?php if (!empty($_COOKIE['password'])) echo $_COOKIE['password'] ?>">
                    <div class="error-message">
                        <?php if(!empty($errors['password'])) { echo $errors['password'];} ?>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberme" name="rememberme" value="true" checked>
                        <label class="form-check-label" for="rememberme">Remember me?</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">送出</button>
                </form>
                <!-- <div class="mt-3"><a href="forgetpassword.php">忘記密碼</a></div> -->
                <div><a href="user_register.php">用戶註冊</a></div>
            </div>

        <?php
            require_once "../view/footer.php"
        ?>

        
        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '登入成功!',
                    text: '三秒後導向用戶中心',
                    icon: 'success',
                    timer: 3000, // 計時器（毫秒）
                }).then(() => {
                    // 重定向至 login.php
                    window.location.href = "user_center.php";
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
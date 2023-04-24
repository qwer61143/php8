<?php
    session_start();
    
    require_once "../lib/Connect.php";
    require_once "../lib/User.php";
    require_once "../lib/Validator.php";

    $db = new Connect;
    $conn = $db->getConnect();

    $data = [];

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $data = [
            'name' => $name,
            'password' => $password,
            'phone' => $phone,
            'email' => $email
        ];
   
        $user = new User($data);
        $userdata = $user->getUserData();

        $validate = new ValidatorUserRegister($conn);

        $validate->validateUserRegister($userdata);
        $errors = $validate->getErrors();
    

        if(isset($errors) && empty($errors)) {
            try {
                $insert = $conn->prepare("INSERT INTO `userdata`(`name`, `password`, phone, email, jointime) VALUES (?, ?, ?, ?, NOW())");
                $insert->execute(array($name, password_hash($password, PASSWORD_BCRYPT), $phone, $email));
                $success = "註冊成功";
            }catch(PDOException $e) { 
                $register_failed = "註冊失敗";
            }
        }else {
            $register_failed = "註冊資料錯誤";
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
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <title>會員註冊系統</title>
    </head>
    <body>
        
        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="container">
            <div class="row">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <img src="../imgs/insidetech-logo.png" alt="logo" title="logo" class="register-logo">
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column justify-content-center text-center mt-5">
                        <form class="" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                            <h1>註冊</h1>
                            <input type="text" class="form-control mt-5" name="name" placeholder="請輸入使用者名稱">
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['name'])) { echo $errors['name'];} ?>
                            </div>
                        
                            <input type="password" class="form-control mt-3"  name="password" placeholder="請輸入密碼">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['password'])) { echo $errors['password'];} ?>
                            </div>

                            <input type="text" class="form-control mt-3" name="phone" placeholder="請輸入手機號碼">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['phone'])) { echo $errors['phone'];} ?>
                            </div>
                        
                            <input type="email" class="form-control mt-3" name="email" placeholder="請輸入電子郵件">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['email'])) { echo $errors['email'];} ?>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">註冊</button>     
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider-horizontal mt-3"></div>

        <?php
            require_once "../view/footer.php"
        ?>

        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '註冊成功!',
                    text: '三秒後重新導向登入介面',
                    icon: 'success',
                    timer: 3000, // 計時器（毫秒）
                }).then(() => {
                    // 重定向至 login.php
                    window.location.href = "user_login.php";
                });
            </script>
        <?php endif; ?>

        <?php if(isset($register_failed) && $register_failed != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $register_failed ?>!',
                })
            </script>
        <?php endif; ?>

    </body>
</html>
<?php
    session_start();
    
    require_once("../method/connet.php");
     
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
            $_SESSION['phone_error'] = "*";
        }else {
            $u_phone = data_in($_POST['u_phone']);
            if(!preg_match("/^[0-9]*$/",$u_phone)) {
                $u_phoneErr = "請輸入正確的手機號碼";
                $_SESSION['phone_error'] = "請輸入正確的手機號碼";
                
              
            }else {

                $query_phone = $conn -> prepare("SELECT u_phone FROM userdata WHERE u_phone = ?");
                $query_phone -> execute(array($u_phone));

                if($query_phone -> fetch(PDO::FETCH_ASSOC)) {
                    $_SESSION['phone_error'] = "您已經註冊過了";
                    $u_phoneErr = "此號碼已經註冊過了";
                }
            }
        }
        if(empty($_POST['u_email'])) {
            $_SESSION['email_error'] = "*";
        }
        else{
            $u_email = data_in($_POST['u_email']);
            if(!filter_var($u_email, FILTER_VALIDATE_EMAIL)){
                $u_emailErr = "請輸入有效的電子郵件地址";
                $_SESSION['email_error'] = "請輸入有效的電子郵件地址";
            }
        }
        if(empty($_POST['u_name'])) {
            $_SESSION['name_error'] = "*";
        }else {
            $u_name = data_in($_POST['u_name']);
            if(!preg_match("/^[A-Za-z0-9]*$/",$u_name)) {
                $u_nameErr = "帳號只允許英文及數字";
                $_SESSION['name_error'] = "帳號只允許英文及數字";
            }
        }
        if(empty($_POST['u_password'])){
            $_SESSION['password_error'] = "*";
        }else {
            $u_password = data_in($_POST['u_password']);
            if(!preg_match("/^[A-Za-z0-9]*$/",$u_password)){
                $u_passwordErr = "密碼只允許數字及英文";
                $_SESSION['password_error'] = "密碼只允許數字及英文";
            }
        }
    }
    
    if($u_phone && $u_email && $u_name && $u_password != ""){
        if(empty($u_emailErr) && empty($u_phoneErr) && empty($u_passwordErr) && empty($u_nameErr)) {
            
            try{
                $insert = $conn -> prepare("INSERT INTO `userdata`(u_phone, u_email, u_name, u_password, u_jointime) VALUES (?,?,?,?,NOW())");
                $insert -> execute(array($u_phone, $u_email, $u_name, password_hash($u_password, PASSWORD_BCRYPT)));
                header("Location:user_center.php");
                exit;
            } catch(PDOException $e){ 
                echo "insert FAILED". $e -> getMessage();
                $_SESSION['register_error'] = "註冊失敗". $e -> getMessage();
            }
        }
    }
    require_once("../method/bootstrap.html");
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
            .error {
                font-size: 12px;
                color: red;
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                display: none;
            }
            .custom-img-size {
                max-width: 25% ;
                height: auto;
                margin: 0 auto;
            }
            .input-container {
                display: flex;
                align-items: center;
            }
            .error-message {
                margin-left: 10px;
                color: red;
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

        <div class="centered-form align-items-start text-center mt-5">
            <form class="w-50 mx-auto mt-5" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <img src="../imgs/register.png" alt="" class="custom-img-size mb-5">
                <div class="form-group position-relative" >
                    <div>
                        <label for="u_phone">請輸入手機號碼</label>
                        <div class="input-container">
                            <input type="text" class="form-control" id="u_phone" name="u_phone" oninput = "validatePhone()">
                            <p class="error-message"style="<?php if(isset($_SESSION['phone_error']) && $_SESSION['phone_error'] != "") {
                                echo "display:block";
                                
                            }else {
                                echo "display:none";
                            }?>">
                                <?php if(isset($_SESSION['phone_error']) && $_SESSION['phone_error'] != "") {
                                    echo $_SESSION['phone_error']; }
                                    unset($_SESSION['phone_error']);
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="phone_error" style="display:none">只能輸入數字!</span>
                    </div>
                </div>
                <div class="form-group  position-relative">
                    <div>
                        <label for="u_email">請輸入電子郵件地址</label>
                        <div class="input-container">
                            <input type="text" class="form-control" id="u_email" name="u_email" oninput = "validateEmail()">
                            <p class="error-message"style="<?php if(isset($_SESSION['email_error']) && $_SESSION['email_error'] != "") {
                                echo "display:block";
                            }else {
                                echo "display:none";
                            }?>">
                                <?php if(isset($_SESSION['email_error']) && $_SESSION['email_error'] != "") {
                                    echo $_SESSION['email_error']; }
                                    unset($_SESSION['email_error']);
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="email_error" style="display:none">請輸入正確的電子郵件!</span>
                    </div>
                </div>
                <div class="form-group  position-relative">
                    <div>
                        <label for="u_name">請輸入使用者名稱</label>
                        <div class="input-container">
                            <input type="text" class="form-control" id="u_name" name="u_name" oninput = "validateName()">
                            <p class="error-message"style="<?php if(isset($_SESSION['name_error']) && $_SESSION['name_error'] != "") {
                                echo "display:block";
                            }else {
                                echo "display:none";
                            }?>">
                                <?php if(isset($_SESSION['name_error']) && $_SESSION['name_error'] != "") {
                                    echo $_SESSION['name_error']; }
                                    unset($_SESSION['name_error']);
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="name_error" style="display:none">使用者名稱只能有英文及數字!</span>
                    </div>
                </div>
                <div class="form-group  position-relative">
                    <div>
                        <label for="u_password">請輸入密碼</label>
                        <div class="input-container">
                            <input type="password" class="form-control" id="u_password" name="u_password" oninput = "validatePassword()">
                            <p class="error-message"style="<?php if(isset($_SESSION['password_error']) && $_SESSION['password_error'] != "") {
                                echo "display:block";
                            }else {
                                echo "display:none";
                            }?>">
                                <?php if(isset($_SESSION['password_error']) && $_SESSION['password_error'] != "") {
                                    echo $_SESSION['password_error']; }
                                    unset($_SESSION['password_error']);
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="password_error" style="display:none">密碼只能有英文及數字!</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">註冊</button>
            </form>
        </div>

        <script>

            function validatePhone() {
                const phone = document.getElementById("u_phone");
                const phoneError = document.getElementById("phone_error");
                const phoneRegex = /^[0-9]+$/;

                if (!phoneRegex.test(phone.value)) {
                    phoneError.style.display = "inline";
                } else {
                    phoneError.style.display = "none";
                }
            }

            function validateEmail() {
                const email = document.getElementById("u_email");
                const emailError = document.getElementById("email_error");
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!emailRegex.test(email.value)) {
                    emailError.style.display = "inline";
                } else {
                    emailError.style.display = "none";
                }
            }

            function validateName() {
                const name = document.getElementById("u_name");
                const nameError = document.getElementById("name_error");
                const nameRegex = /^[\u4e00-\u9fa5a-zA-Z0-9]+$/;

                if (!nameRegex.test(name.value)) {
                    nameError.style.display = "inline";
                } else {
                    nameError.style.display = "none";
                }
            }

            function validatePassword() {
                const password = document.getElementById("u_password");
                const passwordError = document.getElementById("password_error");
                const passwordRegex = /^[a-zA-Z0-9]+$/;

                if (!passwordRegex.test(password.value)) {
                    passwordError.style.display = "inline";
                } else {
                    passwordError.style.display = "none";
                }
            }

            </script>
    </body>
</html>
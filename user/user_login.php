<?php
    require_once("../method/bootstrap.html");
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
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 position-relative">
                <div class="container-fluid">
                    <a class="navbar-brand" href="../index.php">InsideTech</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="user_login.php">用戶登入</a>
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
            <div class="d-flex flex-column justify-content-center align-items-center text-center my-5">
                <img src="../imgs/user.png" alt="">
                <form method="POST" action="login_check.php">
                    <input class="form-control my-3" type="text" placeholder="請輸入手機號碼" name="u_phone" value="<?php if (!empty($_COOKIE['u_phone'])) echo $_COOKIE['u_phone'] ?>">
                    <input class="form-control my-3" type="password" placeholder="請輸入密碼" name="u_password" value="<?php if (!empty($_COOKIE['u_password'])) echo $_COOKIE['u_password'] ?>">
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberme" name="rememberme" value="true" checked>
                        <label class="form-check-label" for="rememberme">Remember me?</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">送出</button>
                </form>
                <div class="mt-3"><a href="forgetpassword.php">忘記密碼</a></div>
                <div><a href="user_register.php">用戶註冊</a></div>
            </div>
    </body>
</html>
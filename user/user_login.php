<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <title>會員登入</title>
    </head>
    <body>
        <form method="POST" action="login_check.php">
            <input type="text" placeholder="請輸入手機號碼" name="u_phone" value="<?php if(!empty($_COOKIE['u_phone'])) echo $_COOKIE['u_phone'] ?>">
            <br>
            <input type="password" placeholder="請輸入密碼" name="u_password" value="<?php if(!empty($_COOKIE['u_password'])) echo $_COOKIE['u_password'] ?>">
            <br>
            <input type="checkbox" name="rememberme" value="true" checked>remember?
            <input type="submit" value="送出">
        </form>
            <div><a href="forgetpassword.php">忘記密碼</a></div>
    </body>
</html>
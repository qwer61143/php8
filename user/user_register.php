<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <title>會員註冊系統</title>
    </head>
    <body>
        <?php
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

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
            <div>請輸入手機號碼:<input type="text" name="u_phone" ><?php echo $u_phoneErr?></div>
            <div>請輸入電子郵件地址:<input type="text" name="u_email"><?php echo $u_emailErr?></div>
            <div>請輸入使用者名稱:<input type="text" name="u_name"><?php echo $u_nameErr?></div>
            <div>請輸入密碼:<input type="password" name="u_password"><?php echo $u_passwordErr?></div>
            <div>送出<input type="submit"></div>
        </form>

        <?php
        if($u_phone && $u_email && $u_name && $u_password != ""){
            if(empty($u_emailErr) && empty($u_phoneErr) && empty($u_passwordErr) && empty($u_nameErr)) {
                require_once("../method/connet.php");
                try{
                    $insert = $conn -> prepare("INSERT INTO `userdata`(u_phone, u_email, u_name, u_password, u_jointime) VALUES (?,?,?,?,NOW())");
                    $insert -> execute(array($u_phone, $u_email, $u_name, password_hash($u_password, PASSWORD_BCRYPT)));
                } catch(PDOException $e){
                    echo "insert FAILED". $e -> getMessage();
                }
            }
        }
        ?>
    </body>
</html>
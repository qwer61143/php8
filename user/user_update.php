<!DOCTYPE html>
<html lang="zh-TW">
    <head>
    <meta charset="utf8">
    <title>更新會員資料</title>
    </head>
    <body>
        <?php
            session_start();
            require_once("../method/connet.php");

            function data_in($data) {
                if(isset($data)){
                $data = trim($data);
                $data = stripcslashes($data);
                $data = htmlspecialchars($data);
                return $data;
                }
            }

            $select = $conn -> prepare("SELECT * FROM `userdata` WHERE u_id=?");
            $select -> execute(array($_SESSION['u_id']));
            $s_result = $select -> fetch(PDO::FETCH_ASSOC);
        ?>

        <form action="" method="POST">
            <div>帳號名稱<input type="text" name="u_name" value="<?php echo $s_result['u_name'] ?>"></div>
            <div>中文名稱<input type="text" name="u_cname" value="<?php if(isset($s_result['u_cname'])){ echo $s_result['u_cname'];} ?>"></div>
            <div>女<input type="radio" name="u_sex" value="F" <?php if($s_result["u_sex"]=="F") echo "checked";?>></div>
            <div>男<input type="radio" name="u_sex" value="M" <?php if($s_result["u_sex"]=="M") echo "checked";?>></div>
            <div>生日<input type="date" name="u_birthday" value="<?php if(isset($s_result['u_birthday'])){ echo $s_result['u_birthday'];} ?>"></div>
            <div>手機號碼<input type="text" name="u_phone" value="<?php echo $s_result['u_phone'] ?>"></div>
            <div>email<input type="text" name="u_email" value="<?php echo $s_result['u_email'] ?>"></div>
            <div>住址<input type="text" name="u_adress" value="<?php if(isset($s_result['u_adress'])){ echo $s_result['u_adress'];} ?>"></div>
            <input name="u_id" type="hidden" value="<?php echo $s_result['u_id'] ?>">
            <input name="action" type="hidden" value="update">
            <input type="submit" value="送出">
        </form>

            <?php 
                if(isset($_POST['action']) && ($_POST['action'] == "update")){
                    $u_name = data_in($_POST['u_name']);
                    $u_cname = data_in($_POST['u_cname']);
                    $u_sex = data_in($_POST['u_sex']);
                    $u_birthday = data_in($_POST['u_birthday']);
                    $u_phone = data_in($_POST['u_phone']);
                    $u_email = filter_var($_POST['u_email'],FILTER_VALIDATE_EMAIL);
                    $u_adress = data_in($_POST['u_adress']);
                    $u_id = data_in($_POST['u_id']);
                    $update = $conn -> prepare("UPDATE `userdata` SET u_name=?, u_cname=?, u_sex=?, u_birthday=?, u_phone=?, u_email=?, u_adress=? WHERE u_id=?");
                    $update -> execute(array($u_name, $u_cname, $u_sex , $u_birthday, $u_phone, $u_email, $u_adress, $u_id));
                }
            ?>
    </body>
</html>

<?php
    session_start();
    require_once("../method/connet.php");

    function data_in($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(!isset($_SESSION['u_name']) || ($_SESSION["u_name"] == "")){
        header("Location:user_login.php");
        exit;
    }
    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        header("Location:user_login.php");
        exit;
    }
    if(isset($_SESSION['u_level']) && ($_SESSION['u_level'] == "member")){
        header("Location: user_center.php");
    }
    
    if(isset($_POST['action']) && ($_POST['action'] == "update")){
        $update = $conn -> prepare("UPDATE `userdata` SET u_name= ?, u_password= ?, u_cname= ?, u_sex= ?, u_birthday= ?, u_phone= ?, u_email= ?, u_adress= ? WHERE u_id =?");
        $u_name = data_in($_POST['u_name']);
        $u_password =data_in($_POST['u_password']);
        $u_cname = data_in($_POST['u_cname']);
        $u_sex = data_in($_POST['u_sex']);
        $u_birthday = data_in($_POST['u_birthday']);
        $u_phone = data_in($_POST['u_phone']);
        $u_email = filter_var($_POST['u_name'],FILTER_VALIDATE_EMAIL);
        $u_adress = data_in($_POST['u_adress']);
        $u_id = data_in($_POST['u_id']);
        if(($_POST['u_newpassword']!="") && ($_POST['u_newpassword'] == $_POST['u_passwordcheck'])){
            $u_password = password_hash($_POST['u_newpassword'], PASSWORD_BCRYPT);
        }
        $update -> execute(array($u_name,$u_password,$u_cname,$u_sex,$u_birthday,$u_phone,$u_email,$u_adress,$u_id));
        header("Location: admin.php");
    }
    $select = $conn -> prepare("SELECT * FROM `userdata` WHERE u_id = ?");
    $select -> execute(array($_GET['id']));
    $result = $select -> fetch(PDO::FETCH_ASSOC);
?>
<html lang="zh-TW">
    <head>
        <meta charset="utf8">
        <title>管理者修改會員資料</title>
    </head>
    <body>
    <form action="" method="POST">
            <div>帳號名稱<input type="text" name="u_name" value="<?php echo $result['u_name'] ?>"></div>
            <input type="hidden" name="u_password" value="<?php echo $result['u_password'] ?>">
            <div>更改密碼<input type="password" name="u_newpassword"></div>
            <div>確認密碼<input type="password" name="u_passwordcheck"></div>
            <div>中文名稱<input type="text" name="u_cname" value="<?php if(isset($result['u_cname'])){ echo $result['u_cname'];} ?>"></div>
            <div>女<input type="radio" name="u_sex" value="F" <?php if($result["u_sex"]=="F") echo "checked";?>></div>
            <div>男<input type="radio" name="u_sex" value="M" <?php if($result["u_sex"]=="M") echo "checked";?>></div>
            <div>生日<input type="date" name="u_birthday" value="<?php if(isset($result['u_birthday'])){ echo $result['u_birthday'];} ?>"></div>
            <div>手機號碼<input type="text" name="u_phone" value="<?php echo $result['u_phone'] ?>"></div>
            <div>email<input type="text" name="u_email" value="<?php echo $result['u_email'] ?>"></div>
            <div>住址<input type="text" name="u_adress" value="<?php if(isset($result['u_adress'])){ echo $result['u_adress'];} ?>"></div>
            <input name="u_id" type="hidden" value="<?php echo $result['u_id'] ?>">
            <input name="action" type="hidden" value="update">
            <input type="submit" value="送出">
        </form>
    </body>
</html>
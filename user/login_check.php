<?php 
    require_once("../method/connet.php");
    //ob_start();
    session_start();

    if(isset($_SESSION['u_name']) && $_SESSION['u_name']!=""){
        if($_SESSION['u_level'] == "member"){
            header("Location= user_center.php");
        }else{
            header("Location= admin.php");
        }
    }

    $u_phone = $_POST['u_phone'];
    $u_password = $_POST['u_password'];

    if(empty($u_phone)||empty($u_password)){
        echo "請輸入帳號和密碼";
    }else {
        $select = $conn -> prepare("SELECT u_phone, u_password, u_level, u_name, u_id FROM `userdata` WHERE u_phone = :_phone");
        $select -> execute(array(':_phone' => $u_phone));
        $result = $select -> fetch(PDO::FETCH_ASSOC);

        if(!$result){
            echo "帳號不存在";
        }else {
            if(password_verify($u_password, $result['u_password'])){
                $update = $conn -> prepare("UPDATE `userdata` SET u_logins = u_logins + 1, u_logintime = NOW() WHERE u_phone = ?");
                $update -> execute(array($result['u_phone']));
                
                $_SESSION["u_name"] = $result['u_name'];
                $_SESSION["u_level"] = $result['u_level'];
                $_SESSION['u_id'] = $result["u_id"];

                if(isset($_POST['rememberme']) && $_POST['rememberme']=="true"){
                    setcookie("remphone", $u_phone, time()+365*60);
                    setcookie("rempassword", $u_password, time()+365*60 );
                }else {
                    if(isset($_COOKIE['remphone'])){
                        setcookie("remphone", $u_phone, time()-100);
                        setcookie("rempassword", $u_password, time()-100);
                    }
                }

                if($_SESSION['u_level'] == "admin"){
                    header("Location: admin.php");
                }else {
                    header("Location: user_center.php");
                }
            
            }else {
                echo "密碼錯誤";
            }
        }
    //ob_end_flush();
    }
?>

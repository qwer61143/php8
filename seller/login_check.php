<?php 
    require_once("../method/connet.php");
    //ob_start();
    session_start();

    if(isset($_SESSION['seller_name']) && $_SESSION['seller_name']!=""){
            header("Location= seller_center.php");
    }

    $seller_name = $_POST['seller_name'];
    $seller_password = $_POST['seller_password'];

    if(empty($seller_name)||empty($seller_password)){
        echo "請輸入帳號和密碼";
    }else {
        $select = $conn -> prepare("SELECT seller_name, seller_password, seller_id FROM `seller` WHERE seller_name = :_name");
        $select -> execute(array(':_name' => $seller_name));
        $result = $select -> fetch(PDO::FETCH_ASSOC);

        if(!$result){
            echo "帳號不存在";
        }else {
            if(password_verify($seller_password, $result['seller_password'])){
                $update = $conn -> prepare("UPDATE `seller` SET seller_logins = seller_logins + 1, seller_logintime = NOW() WHERE seller_id = ?");
                $update -> execute(array($result['seller_id']));
                
                $_SESSION["seller_name"] = $result['seller_name'];
                $_SESSION["seller_id"] = $result['seller_id'];

                if(isset($_POST['rememberme']) && $_POST['rememberme']=="true"){
                    setcookie("remname", $seller_name, time()+365*60);
                    setcookie("rempassword", $seller_password, time()+365*60 );
                }else {
                    if(isset($_COOKIE['remname'])){
                        setcookie("remname", $seller_name, time()-100);
                        setcookie("rempassword", $seller_password, time()-100);
                    }
                }

            header("Location: seller_center.php");

            }else {
                echo "密碼錯誤";
            }
        }
    //ob_end_flush();
    }
?>

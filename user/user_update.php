<?php
    session_start();

    require_once("../method/connet.php");

    function data_in($data) {
        if(isset($data)) {
            $data = trim($data);
            $data = stripcslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }

    $select = $conn -> prepare("SELECT * FROM `userdata` WHERE u_id=?");
    $select -> execute(array($_SESSION['u_id']));
    $result = $select -> fetch(PDO::FETCH_ASSOC);

    if(isset($_GET['logout']) && ($_GET['logout'] == "true")){
        unset($_SESSION['u_name']);
        unset($_SESSION['u_level']);
        unset($_SESSION['u_id']);
        header("Location:user_login.php");
        exit;
    }

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

        header("Location:user_center.php");
        exit;
    }

    require_once("../method/bootstrap.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf8">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <title>更新會員資料</title>
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
        </style>
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
                            <p class="nav-link">更新個人資料</p>
                        </li>
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
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link " href="../contact.php">需要幫助嗎?</a>
                        </li>
                       
                          <li class="nav-item dropdown">
                            <?php if (isset($_SESSION['u_name']) && $_SESSION['u_name'] != "") { ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['u_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="../index.php?logout=true">登出</a></li>
                                </ul>
                            <?php } else { ?>
                                <a class="nav-link" href="/user/user_login.php">登入</a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="centered-form">
            <form class="w-50" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group">
                    <label for="u_name">帳號名稱</label>
                    <input type="text" class="form-control" id="u_name" name="u_name" value="<?php echo $result['u_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="u_cname">姓名</label>
                    <input type="text" class="form-control" id="u_cname" name="u_cname" value="<?php if(isset($result['u_cname'])){ echo $result['u_cname'];} ?>">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="u_sex" id="u_sex" value="M" <?php if($result["u_sex"]=="M") echo "checked";?>>
                    <label class="form-check-label" for="u_sex">
                        男
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="u_sex" id="u_sex" value="F" <?php if($result["u_sex"]=="F") echo "checked";?>>
                    <label class="form-check-label" for="u_sex">
                        女
                </label>
                    </div>
                <div class="form-group">
                    <label for="u_birthday">生日</label>
                    <input type="date" class="form-control" id="u_birthday" name="u_birthday" value="<?php if(isset($result['u_birthday'])){ echo $result['u_birthday'];} ?>">
                </div>
                <div class="form-group">
                    <label for="u_phone">手機號碼</label>
                    <input type="text" class="form-control" id="u_phone" name="u_phone" value="<?php echo $result['u_phone'] ?>">
                </div>
                <div class="form-group">
                    <label for="u_email">email</label>
                    <input type="text" class="form-control" id="u_email" name="u_email" value="<?php echo $result['u_email'] ?>">
                </div>
                <div class="form-group">
                    <label for="u_adress">聯絡地址</label>
                    <input type="text" class="form-control" id="u_adress" name="u_adress" value="<?php if(isset($result['u_adress'])){ echo $result['u_adress'];} ?>">
                </div>
                    <input name="u_id" type="hidden" value="<?php echo $result['u_id'] ?>">
                    <input name="action" type="hidden" value="update">
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
    </body>
</html>

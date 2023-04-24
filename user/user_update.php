<?php
    session_start();

    require_once "../lib/Util.php";
    require_once "../lib/Connect.php";
    require_once "../lib/User.php";
    require_once "../lib/Validator.php";

    $data = [];

    $db = new Connect;
    $conn = $db->getConnect();

    $select = $conn->prepare("SELECT * FROM `userdata` WHERE id = ?");
    $select->execute(array($_SESSION['id']));
    $result = $select->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['action']) && ($_POST['action'] == "update")) {

        $data = [
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'email'=> $_POST['email'],
            'c_name' => $_POST['c_name'],
            'sex' => $_POST['sex'],
            'birthday' => $_POST['birthday'],
            'address' => $_POST['address'],
            'id' => $_SESSION['id']
        ];

        $user = new USER($data);

        $userData = $user->getUserData();
        $userData['id'] = $_SESSION['id'];
 
        $validator = new ValidateUserUpdate($conn);
        $validator->validateUserUpdate($userData);

        $errors = $validator->getErrors();

        if(empty($errors)) {
            $update = $conn->prepare("UPDATE `userdata` SET `name`=?, `phone`=?, `email`=?, `c_name`=?, `sex`=?, `birthday`=?, `address`=? WHERE id=?");
            $update->execute(array_values($userData));

            $success = "更新成功";
        }else {
            $update_failed = "更新失敗";
        }
    }

    require_once "../method/SweetAlert2.html";
    require_once "../method/bootstrap.html";
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf8">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <title>更新會員資料</title>
    </head>
    <body>
        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="text-center user-profile-title mt-5">用戶中心</div>

        <div class="d-flex flex-column justify-content-center align-items-center mt-5">
            <img src="../imgs/profile-icon.png" alt="profile" class="custom-profile-img">
            <form class="custom-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group mt-4">
                    <label for="name">帳號名稱</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $result['name'] ?>">
                </div>
                <div class="error-message">
                    <?php if(isset($errors) && !empty($errors['name'])) { echo $errors['name'];} ?>
                </div>

                <div class="form-group mt-2">
                    <label for="c_name">姓名</label>
                    <input type="text" class="form-control" id="c_name" name="c_name" value="<?php if(isset($result['c_name'])){ echo $result['c_name'];} ?>">
                </div>
                <div class="error-message">
                    <?php if(isset($errors) && !empty($errors['c_name'])) { echo $errors['c_name'];} ?>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="sex" id="sex" value="M" <?php if($result["sex"]=="M") echo "checked";?>>
                    <label class="form-check-label" for="sex">
                        男
                    </label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="sex" id="sex" value="F" <?php if($result["sex"]=="F") echo "checked";?>>
                    <label class="form-check-label" for="sex">
                        女
                    </label>
                </div>

                <div class="form-group mt-2">
                    <label for="birthday">生日</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" value="<?php if(isset($result['birthday'])){ echo $result['birthday'];} ?>">
                </div>

                <div class="form-group mt-2">
                    <label for="phone">手機號碼</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $result['phone'] ?>">
                </div>
                <div class="error-message">
                    <?php if(isset($errors) && !empty($errors['phone'])) { echo $errors['phone'];} ?>
                </div>

                <div class="form-group mt-2">
                    <label for="email">email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $result['email'] ?>">
                </div>
                <div class="error-message">
                    <?php if(isset($errors) && !empty($errors['email'])) { echo $errors['email'];} ?>
                </div>

                <div class="form-group mt-2">
                    <label for="address">聯絡地址</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php if(isset($result['address'])){ echo $result['address'];} ?>">
                </div>
                <div class="error-message">
                    <?php if(isset($errors) && !empty($errors['address'])) { echo $errors['address'];} ?>
                </div>

                <input name="action" type="hidden" value="update">
                <div class="form-group mt-2 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary mt-2">更新</button>
                </div>
            </form>
           
        </div>

        <?php
            require_once "../view/footer.php";
        ?>

        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '更新成功!',
                    text: '三秒後重新導向用戶中心',
                    icon: 'success',
                    timer: 3000, // 計時器（毫秒）
                }).then(() => {
                    // 重定向至 login.php
                    window.location.href = "user_center.php";
                });
            </script>
        <?php endif; ?>

        <?php if(isset($update_failed) && $update_failed != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $update_failed ?>!',
                })
            </script>
        <?php endif; ?>

    </body>
</html>

<?php 
    session_start();

    require_once "../lib/Connect.php";
    require_once "../lib/User.php";
    require_once "../lib/Validator.php";

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $seller_name = $_POST['seller_name'];
        $seller_password = $_POST['seller_password'];
        $seller_phone = $_POST['seller_phone'];
        $seller_email = $_POST['seller_email'];
        $seller_bank_account = $_POST['seller_bank_account'];
        $seller_paymethod = $_POST['seller_paymethod'];

        $seller_data = [
            'seller_name' => $seller_name,
            'seller_password' => $seller_password,
            'seller_phone' => $seller_phone,
            'seller_email' => $seller_email,
            'seller_bank_account' => $seller_bank_account,
            'seller_paymethod' => $seller_paymethod,
        ];

        $db = new Connect;
        $conn = $db->getConnect();

        $validator = new ValidatorSellerRegister($conn);
        $validator->validateSellerRegister($seller_data);

        $errors = $validator->getErrors();

        if(isset($errors) && empty($errors)) {
            $seller_data['seller_password'] = password_hash($seller_password, PASSWORD_BCRYPT);
            try {
                $insert = $conn->prepare("INSERT INTO `seller`(`seller_name`, `seller_password`, seller_phone, seller_email, seller_bank_account, seller_paymethod, seller_jointime) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $insert->execute(array_values($seller_data));

                $success = '註冊成功';
            }catch(PDOException $e) { 
                $register_failed = '註冊失敗';
            }
        }else {
            $register_failed = '註冊資料有誤';
        }
    }

    require_once "../method/SweetAlert2.html";
    require_once "../method/bootstrap.html";
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
        <title>賣家註冊系統</title>
    </head>

    <body>

        <?php 
            require_once "../view/navbar.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <img src="../imgs/seller.png" alt="加入賣家" title="成為賣家!" class="joinseller-logo mt-5">
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column justify-content-center text-center mt-5">
                        <form class="" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                            <h1>註冊</h1>
                            <input type="text" class="form-control mt-5" name="seller_name" placeholder="請輸入商店帳號">
                            <div class="error-message"> 
                                <?php if(isset($errors) && !empty($errors['seller_name'])) { echo $errors['seller_name'];} ?>
                            </div>
                        
                            <input type="password" class="form-control mt-3"  name="seller_password" placeholder="請輸入密碼">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['seller_password'])) { echo $errors['seller_password'];} ?>
                            </div>

                            <input type="text" class="form-control mt-3" name="seller_phone" placeholder="請輸入手機號碼">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['seller_phone'])) { echo $errors['seller_phone'];} ?>
                            </div>
                        
                            <input type="email" class="form-control mt-3" name="seller_email" placeholder="請輸入電子郵件">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['seller_email'])) { echo $errors['seller_email'];} ?>
                            </div>

                            <input type="text" class="form-control mt-3" name="seller_bank_account" placeholder="請輸入銀行帳號">
                            <div class="error-message">
                                <?php if(isset($errors) && !empty($errors['seller_bank_account'])) { echo $errors['seller_bank_account'];} ?>
                            </div>
                            
                            <div class="mt-3">               
                                <select class="form-select" id="multiple-select-clear-field" name="seller_paymethod" data-placeholder="提供的付款方式" multiple>
                                    <option value="ATM" selected>ATM</option>
                                    <option value="刷卡">刷卡</option>
                                    <option value="貨到付款">貨到付款</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">註冊</button>     
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            require_once "../view/footer.php";
        ?>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

        <?php if(isset($success) && $success != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '註冊成功!',
                    text: '三秒後重新導向登入介面',
                    icon: 'success',
                    timer: 3000, // 計時器（毫秒）
                }).then(() => {
                    // 重定向至 login.php
                    window.location.href = "seller_login.php";
                });
            </script>
        <?php endif; ?>

        <?php if(isset($register_failed) && $register_failed != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $register_failed ?>!',
                })
            </script>
        <?php endif; ?>


        <script>
            $(document).ready(function() {
                $('#multiple-select-clear-field').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                    closeOnSelect: false,
                    allowClear: false,
                });
            });
        </script>
    </body>
</html>
<?php 
    require_once("../method/connet.php");
    require_once("../method/bootstrap.html");

    session_start();

    function data_in($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $formValid = true ;

        if(empty($_POST['seller_name'])) {
            $formValid = false ;
        }else {
            $seller_name = data_in($_POST['seller_name']);
            if(!preg_match("/^[\p{Han}A-Za-z0-9]*$/u",$seller_name)) {
                $formValid = false ;
            }
        }

        if(empty($_POST['seller_phone'])) {
            $formValid = false;
        }else {
            $seller_phone = data_in($_POST['seller_phone']);
            if(!preg_match("/^[0-9]*$/",$seller_phone)) {
                $formValid = false;
            }
        }

        if(empty($_POST['seller_password'])) {
            $formValid = false ;
        }else {
            $seller_password = data_in($_POST['seller_password']);
            if(!preg_match("/^[\p{Han}A-Za-z0-9]*$/u",$seller_password)) {
                $formValid = false ;
            }
        }

        if(empty($_POST['seller_email'])) {
            $formValid = false;
        }else {
            $seller_email = data_in($_POST['seller_email']);
            if(!filter_var($seller_email, FILTER_VALIDATE_EMAIL)){
                $formValid = false;
            }
        }

        if(empty($_POST['seller_address'])){
            $formValid = false;
        }else {
            $seller_address = data_in($_POST['seller_address']);
            if(!preg_match("/^[\p{Han}A-Za-z0-9]*$/u",$seller_address)){
                $formValid = false;
            }
        }

        if(empty($_POST['seller_bank_account'])){
            $formValid = false;
        }else {
            $seller_bank_account = data_in($_POST['seller_bank_account']);
            if(!preg_match("/^[0-9-]+$/",$seller_bank_account)){
                $formValid = false;
            }
        }

        $seller_paymethod = data_in($_POST['seller_paymethod']);

        if($formValid == true){
            $insert = $conn -> prepare("INSERT INTO `seller`(seller_name, seller_phone, seller_password ,seller_email, seller_address, seller_bank_account, seller_paymethod, seller_jointime ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $insert -> execute(array($seller_name, $seller_phone, password_hash($seller_password, PASSWORD_BCRYPT), $seller_email, $seller_address, $seller_bank_account, $seller_paymethod));

            header("Location:seller_login.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/css.css" type="text/css">
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
        <title>賣家註冊系統</title>
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
                            <p class="nav-link">賣家註冊</p>
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

        <div class="centered-form">
            <form class="w-50" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_name">請輸入商店帳號</label>
                        <input type="text" class="form-control" id="seller_name" name="seller_name" oninput="validateName()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="name_error" style="display:none">商店帳號只能有英文，數字，以及中文字!</span>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_phone">請輸入電話/手機</label>
                        <input type="text" class="form-control" id="seller_phone" name="seller_phone" oninput="validatePhone()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="phone_error" style="display:none">請輸入正確的號碼!</span>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_password">請輸入商店密碼</label>
                        <input type="password" class="form-control" id="seller_password" name="seller_password" oninput="validatePassword()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="password_error" style="display:none">密碼只能有英文，數字!</span>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_email">請輸入電子郵件</label>
                        <input type="text" class="form-control" id="seller_email" name="seller_email" oninput="validateEmail()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="email_error" style="display:none">請輸入正確的電子郵件!</span>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_address">請輸入地址</label>
                        <input type="text" class="form-control" id="seller_address" name="seller_address" oninput="validateAddress()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="address_error" style="display:none">地址只能有數字英文及中文!</span>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <div class="col">
                        <label for="seller_bank_account">請輸入銀行帳號</label>
                        <input type="text" class="form-control" id="seller_bank_account" name="seller_bank_account" oninput="validateBank()">
                    </div>
                    <div class="col-auto">
                        <span class="error mt-2" id="bank_account_error" style="display:none">請輸入正確帳號!</span>
                    </div>
                </div>

                <select class="form-select" name="seller_paymethod" id="seller_paymethod" multiple aria-label="multiple select example">
                    <option value="ATM" selected>ATM</option>
                    <option value="刷卡">刷卡</option>
                    <option value="貨到付款">貨到付款</option>
                </select>
                <button type="submit" class="btn btn-primary">註冊</button>
            </form>
        </div>

        <script>

            function validateName() {
                const name = document.getElementById("seller_name");
                const nameError = document.getElementById("name_error");
                const nameRegex = /^[\u4e00-\u9fa5a-zA-Z0-9]+$/;

                if (!nameRegex.test(name.value)) {
                    nameError.style.display = "inline";
                } else {
                    nameError.style.display = "none";
                }
            }

            function validatePhone() {
                const phone = document.getElementById("seller_phone");
                const phoneError = document.getElementById("phone_error");
                const phoneRegex = /^[0-9]+$/;

                if (!phoneRegex.test(phone.value)) {
                    phoneError.style.display = "inline";
                } else {
                    phoneError.style.display = "none";
                }
            }

            function validatePassword() {
                const password = document.getElementById("seller_password");
                const passwordError = document.getElementById("password_error");
                const passwordRegex = /^[a-zA-Z0-9]+$/;

                if (!passwordRegex.test(password.value)) {
                    passwordError.style.display = "inline";
                } else {
                    passwordError.style.display = "none";
                }
            }

            function validateEmail() {
                const email = document.getElementById("seller_email");
                const emailError = document.getElementById("email_error");
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!emailRegex.test(email.value)) {
                    emailError.style.display = "inline";
                } else {
                    emailError.style.display = "none";
                }
            }

            function validateAddress() {
                const address = document.getElementById("seller_address");
                const addressError = document.getElementById("address_error");
                const addressRegex = /^[\u4e00-\u9fa5a-zA-Z0-9\s]+$/;

                if (!addressRegex.test(address.value)) {
                    addressError.style.display = "inline";
                } else {
                    addressError.style.display = "none";
                }
            }

            function validateBank() {
                const bankAccount = document.getElementById("seller_bank_account");
                const bankAccountError = document.getElementById("bank_account_error");
                const accountRegex = /^[0-9-]+$/;

                if (!accountRegex.test(bankAccount.value)) {
                    bankAccountError.style.display = "inline";
                } else {
                    bankAccountError.style.display = "none";
                }
            }

        </script>

    </body>
</html>
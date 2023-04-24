<?php

// 檢查輸入是否為空
class BaseValidator
{
    protected $errors = [];

    public function validateEmpty($input, $value) 
    {
        if(empty($value)) {
            $this->errors[$input] = "*";
        }
    }
    
    public function setError($input, $message) 
    {
        $this->errors[$input] = $message;
    }

    public function getErrors() 
    {
        return $this->errors;
    }

}

// 驗證使用者註冊時的輸入資料
class ValidatorUserRegister extends BaseValidator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validateUserRegister($inputs) 
    {
        foreach ($inputs as $input => $value) {
            $this->validateEmpty($input, $value);
        }
        
        if(!preg_match("/^[A-Za-z0-9]*$/", $inputs['name'])) {
            $this->errors['name'] = "名稱只能包含數字及英文";
        }
        if(!preg_match("/^[A-Za-z0-9]*$/",$inputs['password'])) {
            $this->errors['password'] = "密碼只能包含數字及英文";
        }
        if(!preg_match("/^[0-9]*$/",$inputs['phone'])) {
            $this->errors['phone'] = "請輸入正確的手機號碼";
        }else {
            $query = $this->conn->prepare("SELECT phone FROM userdata WHERE phone = ?");
            $query->execute(array($inputs['phone']));

            if($query->fetch(PDO::FETCH_ASSOC)) {
                $this->errors['phone'] = "該手機已被註冊";
            }
        }
        if(!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "請輸入有效的電子郵件地址";
        }
    }
}

// 驗證賣家註冊時的輸入資料
class ValidatorSellerRegister extends BaseValidator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validateSellerRegister($inputs) 
    {
        foreach ($inputs as $input => $value) {
            $this->validateEmpty($input, $value);
        }
        
        if(!preg_match("/^[A-Za-z0-9]*$/", $inputs['seller_name'])) {
            $this->errors['seller_name'] = "帳號名稱只能包含數字及英文";
        }else {
            $query_name = $this->conn->prepare("SELECT seller_name FROM seller WHERE seller_name = ?");
            $query_name->execute(array($inputs['seller_name']));

            if($query_name -> fetch(PDO::FETCH_ASSOC)) {
                $this->errors['seller_name'] = "該帳號已被註冊";
            }
        }
        if(!preg_match("/^[A-Za-z0-9]*$/",$inputs['seller_password'])) {
            $this->errors['seller_password'] = "密碼只能包含數字及英文";
        }
        if(!preg_match("/^[0-9]*$/",$inputs['seller_phone'])) {
            $this->errors['seller_phone'] = "請輸入正確的手機號碼";
        }else {
            $query_phone = $this->conn->prepare("SELECT seller_phone FROM seller WHERE seller_phone = ?");
            $query_phone->execute(array($inputs['seller_phone']));

            if($query_phone->fetch(PDO::FETCH_ASSOC)) {
                $this->errors['seller_phone'] = "該手機已被註冊";
            }
        }
        if(!filter_var($inputs['seller_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['seller_email'] = "請輸入有效的電子郵件地址";
        }
        if(!preg_match("/^[0-9\-]+$/u", $inputs['seller_bank_account'])) {
            $this->errors['seller_bank_account'] = "銀行帳號只能有數字還有-";
        }
    }
}

// 驗證使用者更新個人資料
class ValidateUserUpdate extends ValidatorUserRegister
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validateUserUpdate($inputs) 
    {
        foreach ($inputs as $input => $value) {
            $this->validateEmpty($input, $value);
        }
        
        if(!preg_match("/^[A-Za-z0-9]*$/", $inputs['name'])) {
            $this->errors['name'] = "名稱只能包含數字及英文";
        }
        if(!preg_match("/^[0-9]*$/",$inputs['phone'])) {
            $this->errors['phone'] = "請輸入正確的手機號碼";
        }else {
            $query = $this->conn->prepare("SELECT id,phone FROM userdata WHERE phone = ?");
            $query->execute(array($inputs['phone']));
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result) {
                if($result['id'] != $inputs['id']) {
                    $this->errors['phone'] = "該手機已被註冊";
                }
            }
        }
        if(!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "請輸入有效的電子郵件地址";
        }
        if (!preg_match("/^[\p{Han}]+$/u", $inputs['c_name'])) {
            $this->errors['c_name'] = "只能有中文字";
        }

        if (!preg_match("/^[\p{Han}a-zA-Z0-9\-]+$/u", $inputs['address'])) {
            $this->errors['address'] = "地址中只能有中文，英文，數字還有-";
        }
    }
}

// 登入檢查
class LoginCheck extends BaseValidator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function loginCheck($phone, $password) 
    {
        $this->validateEmpty('phone', $phone);
        $this->validateEmpty('password', $password);

        if(empty($this->errors)) {
            $select = $this->conn->prepare("SELECT `phone`, `password`, `level`, `name`, `id` FROM `userdata` WHERE `phone` = :_phone");
            $select->execute(array(':_phone' => $phone));

            $result = $select->fetch(PDO::FETCH_ASSOC);

            if(isset($result) && !empty($result)) {
                if(password_verify($password, $result['password'])) {
                    $update = $this->conn->prepare("UPDATE `userdata` SET logincount = logincount + 1, logintime = NOW() WHERE phone = ?");
                    $update -> execute(array($result['phone']));
                    
                    $_SESSION["name"] = $result['name'];
                    $_SESSION["level"] = $result['level'];
                    $_SESSION['id'] = $result["id"];

                    if(isset($_POST['rememberme']) && $_POST['rememberme']=="true") {
                        setcookie("phone", $phone, time()+365*60);
                        setcookie("password", $password, time()+365*60 );
                    }else {
                        if(isset($_COOKIE['phone'])){
                            setcookie("phone", $phone, time()-100);
                            setcookie("password", $password, time()-100);
                        }
                    }
                }else {
                   $this->errors['password'] = "密碼錯誤";
                }
            }else {
                $this->errors['phone']="帳號不存在";
            }
        }
    }
}

class SellerLoginCheck extends BaseValidator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function loginCheck($seller_name, $seller_password) 
    {
        $this->validateEmpty('seller_name', $seller_name);
        $this->validateEmpty('seller_password', $seller_password);

        if(empty($this->errors)) {
            $select = $this->conn->prepare("SELECT `seller_name`, `seller_password`, `seller_id` FROM `seller` WHERE `seller_name` = :_seller_name");
            $select->execute(array(':_seller_name' => $seller_name));

            $result = $select->fetch(PDO::FETCH_ASSOC);

            if(isset($result) && !empty($result)) {
                if(password_verify($seller_password, $result['seller_password'])) {
                    $update = $this->conn->prepare("UPDATE `seller` SET seller_logins = seller_logins + 1, seller_logintime = NOW() WHERE seller_name = ?");
                    $update -> execute(array($result['seller_name']));
                    
                    $_SESSION["seller_name"] = $result['seller_name'];
                    $_SESSION['seller_id'] = $result["seller_id"];

                    if(isset($_POST['rememberme']) && $_POST['rememberme']=="true") {
                        setcookie("seller_name", $seller_name, time()+365*60);
                        setcookie("seller_password", $seller_password, time()+365*60 );
                    }else {
                        if(isset($_COOKIE['seller_name'])){
                            setcookie("seller_name", $seller_name, time()-100);
                            setcookie("seller_password", $seller_password, time()-100);
                        }
                    }
                }else {
                   $this->errors['seller_password'] = "密碼錯誤";
                }
            }else {
                $this->errors['seller_name']="帳號不存在";
            }
        }
    }
}
?>



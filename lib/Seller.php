<?php 

require_once "Util.php";

use MyApp\Utils\Util;

class Seller
{
    protected $seller_name;
    protected $seller_password;
    protected $seller_phone;
    protected $seller_email;
    protected $c_name;
    protected $sex;
    protected $birthday;
    protected $seller_address;
    protected $seller_bank_account;
    protected $seller_paymethod;

    public function __construct($seller_data)
    {
        $this->setSellerData($seller_data);
    }

    // 遍歷數組並且判斷屬性是否存在，存在就將$value賦予該$key
    public function setSellerData($seller_data) {
        foreach($seller_data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = Util::data_in($value);
            }
        }
    }
    
    // 取得資料
    public function getSellerData() 
    {
        $sellerData = [];
        $properties = get_object_vars($this);
        foreach ($properties as $key => $value) {
            if(isset($value)) {
                $sellerData[$key] = $value;
            }
        }
        return $sellerData;
    }

    public function validateSellerData($conn) 
    {
        $validator = new ValidatorSellerRegister($conn);
        $validator->validateSellerRegister($this->getSellerData());
        return $validator->getErrors();
    }

}

?>
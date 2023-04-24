<?php 

require_once "Util.php";

use MyApp\Utils\Util;

class User
{
    protected $name;
    protected $password;
    protected $phone;
    protected $email;
    protected $c_name;
    protected $sex;
    protected $birthday;
    protected $address;

    public function __construct($data)
    {
        $this->setUserData($data);
    }

    // 遍歷數組並且判斷屬性是否存在，存在就將$value賦予該$key
    public function setUserData($data) 
    {
        foreach($data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = Util::data_in($value);
            }
        }
    }
    
    // 取得資料
    public function getUserData() 
    {
        $userData = [];
        // 取得這物件的所有屬性
        $properties = get_object_vars($this);
        // 遍歷並將不為空的加入userData陣列中
        foreach ($properties as $key => $value) {
            if (isset($value)) {
                $userData[$key] = $value;
            }
        }
        return $userData;
    }
}

?>
<?php

require_once "Validator.php";
require_once "Util.php";

use MyApp\Utils\Util;

class Good
{
    private $conn;

    protected $good_name;
    protected $good_pic;
    protected $good_price;
    protected $good_total;
    protected $good_info;
    protected $good_category;
    protected $good_subcategory;
    protected $good_brand;
    protected $good_seller;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function setGoodData($data) 
    {
        foreach($data as $key => $value) {
            if(property_exists($this, $key)) {
                if(isset($value)) {
                    $this->{$key} = Util::data_in($value);
                }
            }
        }
    }

    public function getGoodData() 
    {
        $goodData = [];
        $properties = get_object_vars($this);

        foreach($properties as $key => $value) {
            if(isset($value)) {
                $goodData[$key] = $value;
            }
        }
        return $goodData;
    }

    public function validateGoodsData() {

        $validateEmpty = new BaseValidator;

        foreach($this->getGoodData() as $key => $value) {
            $validateEmpty->validateEmpty($key, $value);
        }

        return $validateEmpty->getErrors();
    }
}
?>
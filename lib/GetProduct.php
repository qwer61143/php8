<?php

class GetCategory
{
    private $conn;
    protected $category;
    protected $subcategory;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function setCategory($category) 
    {
        $this->category = $category;
    }

    public function setSubcategory($subcategory) 
    {
        $this->subcategory = $subcategory;
    }
    
    // 取得該類別下商品，目前只使用主分類
    public function getBrandsAndGoods()
    {
        $query = $this->conn->prepare("SELECT * FROM goods WHERE good_category = :category");
        $query->bindParam(":category", $this->category, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function getGoodsByBrand($brands) 
    {
        $brandsStr = implode(',', array_map(function($brand) {
            return "'" . $brand . "'";
        }, $brands));

        $query_by_brand = $this->conn->prepare("SELECT * FROM goods WHERE good_category = :category AND good_brand IN (" . $brandsStr . ") ");
        $query_by_brand->bindParam(":category", $this->category, PDO::PARAM_STR);
        $query_by_brand->execute();
        $result = $query_by_brand->fetchAll();
        return $result;
    }
}

class GetGOOD
{
    private $conn;
    
    public function __construct($conn)
    {
        $this->conn = $conn; 
    }

    public function getGood($id) {
        $select = $this->conn->prepare("SELECT * FROM goods WHERE good_id = ?");
        $select->execute(array($id));
        $good = $select->fetch(PDO::FETCH_ASSOC);

        return $good;
    } 
}

?>
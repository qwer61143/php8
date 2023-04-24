<?php

namespace MyApp\Utils;

class Util 
{
    public static function data_in($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
    
    public static function logout() 
    {
        unset($_SESSION['name']);
        unset($_SESSION['level']);
        unset($_SESSION['id']);
        unset($_SESSION['seller_name']);
        unset($_SESSION['seller_id']);
    
        header("Location:index.php");
    }
}
?>
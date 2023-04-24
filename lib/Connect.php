<?php

class Connect
{
    private $conn;

    public function __construct()
    {
        $host = "123.240.100.75";
        $dbname = "insidetech";
        $username = "docker_user";
        $password = "qwer61134";
        
        try {
            $this->conn = new pdo("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connet failed:" . $e->getMessage();
        }
    }

    public function getConnect() 
    {
        return $this->conn;
    }
}

?>
<?php
    $db_host = "34.81.156.98";
    $db_username = "root";
    $db_password = "qwer61134";
    $db_name = "insidetech";

        try {
            $conn = new pdo("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
            $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connet failed:".$e -> getMessage();
        }
?>
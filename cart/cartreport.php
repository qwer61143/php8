<?php
    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");

    if(isset($_POST['c_name']) && $_POST['c_name'] != "") {
        $cart = new Cart([
            'cartMaxItem' => 0,
            'itemMaxQuantity' => 0,
            'useCookie' => false,
        ]);
    }
    $c_name = $_POST['c_name'];
    $c_phone = $_POST['c_phone'];
    $c_paymethod = $_POST['paymethod'];
    $c_address = $_POST['c_address'];

    $query = $conn -> prepare("INSERT INTO `orders`(customer_name, customer_phone, payment_method, shipping_address, order_date) VALUES (?, ?, ?, ?, NOW())");
    $query -> execute(array($c_name, $c_phone, $c_paymethod, $c_address));
    $order_id = $conn -> lastInsertId();
    
    if($cart -> getTotalItem() > 0) {
        $allItems = $cart -> getItems();
        foreach($allItems as $items) {
            foreach($items as $item) {
                $item_name = $item['attributes']['name'];
                $item_price = $item['attributes']['price'];
                $quantity = $item['quantity'];

                $insert = $conn -> prepare("INSERT INTO `order_item`(order_id, quantity, price, item_name) VALUES (?, ?, ?, ?)");
                $insert -> execute(array($order_id, $quantity, $item_price, $item_name));
            }
        }
    }
?>
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
    $good_id = $_POST['good_id'];
    $customer_id = $_SESSION['u_id'];

    $order_total = $cart -> getAttributeTotal('price');

    $insert = $conn -> prepare("INSERT INTO `orders`(customer_id, customer_name, order_total, customer_phone, order_good_id, payment_method, shipping_address, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $insert -> execute(array($customer_id, $c_name, $order_total,$c_phone, $good_id, $c_paymethod, $c_address));
    $order_id = $conn -> lastInsertId();
    
    if($cart -> getTotalItem() > 0) {
        $allItems = $cart -> getItems();
        foreach($allItems as $items) {
            foreach($items as $item) {
                $item_name = $item['attributes']['name'];
                $item_price = $item['attributes']['price'];
                $quantity = $item['quantity'];
                $good_id = $item['id'];

                $insert = $conn -> prepare("INSERT INTO `order_item`(order_id, quantity, price, item_name, good_id) VALUES (?, ?, ?, ?, ?)");
                $insert -> execute(array($order_id, $quantity, $item_price, $item_name, $good_id));
            }
        }
    }
?>
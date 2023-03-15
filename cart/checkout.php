<?php 
    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);
?>
    <?php if($cart -> getTotalitem() > 0) { ?>
        <?php
        $i=0;
        $allItems = $cart -> getItems();
        foreach($allItems as $items) {
            foreach($items as $item) {
                $i++;
                echo $i;
                echo "\n";
                echo $item['attributes']['name'];
                echo "\n";
                echo $item['quantity'];
                echo "\n";
                echo number_format($item['attributes']['price']);
                echo "\n";
                echo number_format($item['attributes']['price'] * $item['quantity']);
                echo "\n";
            }
        }
        echo number_format($cart -> getAttributeTotal('price'));
        echo "訂單資訊";
        ?>

        <form action="cartreport.php" method="post">
            <input type="text" name="c_name">姓名
            <input type="text" name="c_email">email
            <input type="text" name="c_phone">phone
            <input type="text" name="c_address">address
            <select name="paymethod" id="paytype">
                <option value="ATM" selected>ATM</option>
                <option value="刷卡">刷卡</option>
                <option value="貨到付款">貨到付款</option>
            </select>
          <input name="cartaction" type="hidden"  value="update">
          <input type="submit" name="updatebtn" value="送出訂購單">
          <input type="button" name="backbtn" value="回上一頁" onClick="window.history.back();">
      </form>
    <?php } else { ?>
    <p>購物車是空的</p>
    <?php } ?>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);

    if (isset($_POST['cartaction']) && ($_POST['cartaction'] == "update")) {
        if (isset($_POST['updateid'])) {
            $i = count($_POST['updateid']);
            for($j = 0; $j < $i; $j++) {
                $good = $cart -> getItem($_POST['updateid'][$j]);
                $cart -> update($good['id'], $_POST['quantity'][$j], [
                    'name' => $good['attributes']['name'],
                    'price' => $good['attributes']['price'],
                ]);
            print_r($cart);
            }
        }
        header("Location:cart.php");
    }
    

    if(isset($_GET['cartaction']) && $_GET['cartaction'] == "remove"){
        $removeID = intval($_GET['removeid']);
        $cart -> remove($removeID);
       header("Location:cart.php");
    }

    if(isset($_GET['cartaction']) && $_GET['cartaction'] == "clear"){
        $cart -> clear();
        header("Location:cart.php");
    }
?>
 
    <?php if($cart->getTotalItem()> 0) { ?>
            <form action="" method="POST">
                <?php
                    $allItems = $cart -> getItems();
                    foreach($allItems as $items) {
                        foreach($items as $item) {
                         ?>
                            <a href="?cartaction=remove&removeid=<?php echo $item['id']; ?>">remove</a>
                            <?php echo $item['attributes']['name']; ?> 
                            <input type="hidden" name="updateid[]" value="<?php echo $item['id'] ?>">
                            <input type="text" name="quantity[]" value="<?php echo $item['quantity'] ?>">
                            <?php echo $item['attributes']['price']; ?>
                            <?php echo "總價是" ?>
                            <?php echo number_format($item['quantity'] * $item['attributes']['price']); ?>
                <?php }} ?>
                        <div>
                            <p>總共是:<?php echo $cart -> getAttributeTotal('price') ?></p>
                            <input type="hidden" name="cartaction" value="update">
                            <input type="submit" name="updatebtn" value="更新購物車">
                            <input type="button" name="clearbtn" value="清空購物車" onClick="window.location.href='?cartaction=clear'">
                            <input type="button" name="paybtn" value="結帳" onClick="window.location.href='checkout.php'">
                            <input type="button" name="backbtn" value="回上一頁" onClick="window.history.back();">
                        </div>
            </form>
    <?php }else { ?>
        <div>Cart is empty</div>
    <?php } ?>

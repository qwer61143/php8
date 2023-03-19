<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    require_once("../method/class.Cart.php");
    require_once("../method/connet.php");
    
    $good_id = $_GET['id'];
    $select = $conn -> prepare("SELECT * FROM `goods` WHERE good_id = ? ");
    $select -> execute(array($good_id));
    $good = $select -> fetch(PDO::FETCH_ASSOC);

    $cart = new Cart([
        'cartMaxItem' => 0,
        'itemMaxQuantity' => 0,
        'useCookie' => false,
    ]);
    
    if(isset($_POST['cartaction']) && $_POST['cartaction'] == 'add'){
        $cart -> add($_POST['id'], $_POST['quantity'],[
        'name' => $_POST['name'],
        'price' => $_POST['price']
        ]);
        header("Location: ../cart/cart.php");
        //exit;
    }
    
?>
    <div>
        <?php echo $good['good_name'] ?>
        <img src=<?php echo $good['good_pic'] ?> width='200' heigh='200' >
        <?php echo $good['good_price']?>
        <?php echo $good['good_uptime']?>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $good_id ?>">
        <input type="hidden" name="name" value="<?php echo $good['good_name'] ?>"> 
        <input type="hidden" name="price" value="<?php echo $good['good_price'] ?>">
        <input type="number" name="quantity">
        <input type="hidden" name="cartaction" value="add">
        <input type="submit" value="加入購物車">
    </form>
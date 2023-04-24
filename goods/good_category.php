<?php
    session_start();

    require_once "../method/class.Cart.php";
    require_once "../lib/Connect.php";
    require_once "../lib/GetProduct.php";
    
    
    $category = $_GET['category'];
    // $subCategory = $_GET['subcategory'];
    

    $db = new Connect;
    $conn = $db->getConnect();
    
    $getCategory = new GetCategory($conn);

    // 取得該分類下有哪些品牌
    if(isset($category) && empty($_GET['brand'])) {
        $getCategory->setCategory($category);
        $goods = $getCategory->getBrandsAndGoods();

        $category_brands = [];
        foreach ($goods as $good) {
            if (!in_array($good['good_brand'], $category_brands)) {
                $category_brands[] = $good['good_brand'];
            }
        }
    }

    // 取的篩選後的分類
    $brands = isset($_GET['brand']) ? $_GET['brand'] : "";

    if(!empty($brands && $brands != "")) {
        $getCategory->setCategory($category);
        $goods = $getCategory->getGoodsByBrand($brands);
        // 將選中品牌轉化成URL格式以傳遞參數
        $brandsUrlParam = implode('&', array_map(function ($brand) {
            echo "</br>";
            return 'brand[]=' . urlencode($brand);
        }, $brands));
        var_dump(urlencode($brandsUrlParam));

        $category_brands = [];
        foreach ($goods as $good) {
            if (!in_array($good['good_brand'], $category_brands)) {
                $category_brands[] = $good['good_brand'];
            }
        }
    }
    
    // 排序方式
    if(isset($_GET['sort_by'])) {
        $sort_by = $_GET['sort_by'];
    }else {
        $sort_by = 'new';
    }
    if ($sort_by === "new") {
        usort($goods, function($a, $b) {
            return strtotime($b['good_uptime']) - strtotime($a['good_uptime']);
        });
    }else if($sort_by === "old"){
        usort($goods, function($a, $b) {
            return strtotime($a['good_uptime']) - strtotime($b['good_uptime']);
        });
    }else if($sort_by === "high_price") {
        usort($goods, function($a, $b) {
            return $b['good_price'] - $a['good_price'];
        });
    }else if($sort_by === "low_price") {
        usort($goods, function($a, $b) {
            return $a['good_price'] - $b['good_price'];
        });
    }

    require_once "../method/SweetAlert2.html";
    require_once "../method/bootstrap.html";
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/css.css">
        <title><?php echo $category ?></title>
    </head>
    <body>

        <?php
            require_once "../view/navbar.php";
        ?>

        <div class="container mt-3">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb ">
                    <li class="breadcrumb-item m-1"><a href="/index.php" class="custom-a m-1">首頁</a></li>
                    <li class="breadcrumb-item m-1"><a href="#" class="custom-a m-1"><?php if(isset($good['good_category'])) { echo $good['good_category']; } ?></a></li>
                    <li class="breadcrumb-item m-1"><a href="#" class="custom-a m-1"><?php if(isset($good['good_subcategory'])) { echo $good['good_subcategory']; } ?></a></li>
                </ol>
            </nav>
        </div>

        <div class="container mt-3 d-flex">
                <div class="col-2 border border-1 brand-container">
                    <div class="brand-bg mb-3 ">
                        <p class="brand ms-4">品牌</p>
                    </div>
                    <form action="" class="m-0">
                        <?php foreach($category_brands as $brand) : ?>
                            <div class="form-check ms-4">
                                <input class="form-check-input fs-4" type="checkbox" value="<?php echo $brand ?>" name="brand[]" id="flexCheckDefault">
                                <label class="form-check-label fs-4" for="flexCheckDefault">
                                    <?php echo $brand ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                        <input type="hidden" name="category" value="<?php echo $category ?>">
                        <input class="brand-bg brand w-100 mt-3" type="submit" value="點擊過濾">
                    </form>
                </div>

                <div class="col-10">
                    <div class="row ms-4">
                        <div class="dropdown d-flex ml-auto col-4 col-md-2">
                            <button class="btn btn-drak dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                    if(isset($_GET['sort_by']) && $_GET['sort_by'] != "") { 
                                        echo $sort_by;
                                    }else {
                                        echo "排序方式";
                                    }
                                ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a href="?category=<?php echo $category ?>&<?php if(!empty($brands)) { echo $brandsUrlParam; } ?>&sort_by=new" class="custom-a">最新</a></li>
                                <li><a href="?category=<?php echo $category ?>&sort_by=old" class="custom-a">最舊</a></li>
                                <li><a href="?category=<?php echo $category ?>&sort_by=high_price" class="custom-a">最貴</a></li>
                                <li><a href="?category=<?php echo $category ?>&sort_by=low_price" class="custom-a">最便宜</a></li>
                                <li><a href="?category=<?php echo $category ?>&sort_by=views">最熱門</a></li>
                            </ul>
                        </div>
                    </div>
            
                    <div class="row ms-4 mt-4">
                        <?php foreach($goods as $good) : ?>
                            <div class="col-12 col-md-3 mb-5 d-flex justify-content-center align-items-center">
                                <div class="card custom-category-card border-0">
                                    <a href="good.php?id=<?php echo $good['good_id'] ?>">
                                        <img src="<?php echo $good['good_pic']?>" class="custom-category-card-img mx-auto border border-1" alt="...">
                                    </a>
                                    <div class="card-body custom-card-body">
                                        <h5 class="custom-category-card-title mb-2"><?php echo $good['good_name']?></h5>
                                        <div class="card-text custom-category-price mb-2"><?php echo "$" . $good['good_price'] ?></div>
                                        <div class="card-text"><?php echo "上架時間:" . $good['good_uptime'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>


        <?php
            require_once "../view/footer.php";
        ?>

        <?php if(isset($cartAddSuccess) && $cartAddSuccess != ""): ?>
            <script>
                // 顯示成功消息
                Swal.fire({
                    title: '已加入購物車!',
                })

                setTimeout(() => {
                    <?php $cartAddSuccess = ""; ?>
                }, 1000);
            </script>
        <?php endif; ?>

    </body>
</html>
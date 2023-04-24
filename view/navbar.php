<?php
    require_once __DIR__ . "/../lib/Connect.php";
    require_once __DIR__ . "/../method/bootstrap.html";
    
    $db = new Connect;
    $conn = $db->getConnect();
  
    $query = $conn->prepare("SELECT DISTINCT good_category FROM goods");
    $query->execute();
    $all_category = $query->fetchAll();
?>
    
    <div class="container custom-navbar-container">
        <div class="row">
            <div class="col-12 col-md-3 d-none d-md-block">
                <a class="navbar-brand" href="/index.php">
                    <img class="logo" src="/imgs/insidetech-logo.png" alt="">
                </a>
            </div>
            <div class="col-8 col-md-5 d-flex justify-content-center align-items-center">
                <form method="get" action="/goods/search.php" name="form1" class="custom-search-bar ">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control custom-navbar-search-form" placeholder="關鍵字">
                        <button type="submit" class="btn ms-2">
                            <img src="/imgs/icons8-search-30.png" alt="">
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-4 col-md-4 d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="dropdown custom-navbar-item d-flex justify-content-center align-items-center flex-column">
                        <button class="btn dropdown-toggle custom-dorpdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/imgs/user.png" alt="用戶登入" title="用戶登入" class="custom-navbar-img">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <!-- 使用者 -->
                            <?php if(isset($_SESSION['name']) && $_SESSION['name'] != "") { ?>
                                <li><a class="dropdown-item" href="/user/user_center.php">用戶中心</a></li>
                                <li><a class="dropdown-item" href="/index.php?logout=true">登出</a></li>
                            <?php } else { if(!isset($_SESSION['seller_name'])) { ?>
                                <li><a class="dropdown-item" href="/user/user_login.php">用戶登入</a></li>
                            <?php  }} ?>
                            <!-- 賣家 -->
                            <?php if(isset($_SESSION['seller_name']) && $_SESSION['seller_name'] != "") { ?>
                                <li><a class="dropdown-item" href="/seller/seller_center.php">賣家中心</a></li>
                                <li><a class="dropdown-item" href="/index.php?logout=true">登出</a></li>
                            <?php } else { if(!isset($_SESSION['name'])) { ?>
                                <li><a class="dropdown-item" href="/seller/seller_login.php">賣家登入</a></li>
                            <?php }} ?>
                        </ul>
                    </div>
                    <span>
                        <?php 
                            if(isset($_SESSION['name']) && $_SESSION['name'] != "") {
                                echo $_SESSION['name'];
                            }else if(isset($_SESSION['seller_name']) && $_SESSION['seller_name'] != ""){
                                echo $_SESSION['seller_name'];
                            }else {
                                echo "用戶";
                            }
                        ?>
                    </span>
                </div>
                
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="custom-navbar-item d-flex justify-content-center align-items-center flex-column">
                        <a class="nav-link" href="/cart/cart.php">
                            <img src="/imgs/wishlist.png" alt="關注列表" title="關注列表" class="custom-navbar-img">
                        </a>
                    </div>
                    <span>願望清單</span>
                </div>

                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="custom-navbar-item d-flex justify-content-center align-items-center flex-column">
                        <a class="nav-link" href="/cart/cart.php">
                            <img src="/imgs/cart.png" alt="購物車" title="購物車" class="custom-navbar-img">
                        </a>
                    </div>
                    <span>購物車</span>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="col-md-3">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle custom-dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            所有分類
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php foreach($all_category as $dorpdown_category) : ?>
                                <li><a class="dropdown-item" href="/goods/good_category.php?category=<?php echo $dorpdown_category['good_category'] ?>"><?php echo $dorpdown_category['good_category'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>
        
            <div class="col-md-7 d-flex justify-content-center align-items-center">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="" class="nav-link custom-nav-item">品牌專館</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link custom-nav-item">二手專區</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link custom-nav-item">電競專區</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link custom-nav-item">限時特賣</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="" class="nav-link custom-nav-item">聯絡我們</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
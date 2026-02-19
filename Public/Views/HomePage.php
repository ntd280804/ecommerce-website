    <?php
$isHomePage = true;

require_once(__DIR__ . '/../../Config/Database.php');
require_once(__DIR__ . '/../Models/Category_Model.php');
require_once(__DIR__ . '/../Models/Product_Model.php');

try {
    $categorymodel = new CategoryModel();
    $ProductModel = new ProductModel();

    $categories = $categorymodel->getAll();
    $topdiscountedproduct = $ProductModel->getTopDiscounted(5); // Fetch top discounted products
    $topratedproduct = $ProductModel->getTopRated(5); // Fetch top rated products

    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['user_role'] ?? 'Default';
    if (!isset($_SESSION['totalAmount'])) {
        $_SESSION['totalAmount'] = 0;
    }
    if (!isset($_SESSION['totalQuantityAmount'])) {
        $_SESSION['totalQuantityAmount'] = 0;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/">
    <title>OGANI</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/style.css" type="text/css">
    <script>
        function toSlug(str) {
            return str.toLowerCase()
                .replace(/đ/g, 'd')
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        function submitHeaderSearch(form) {
            const tukhoa = form.tukhoa.value.trim();
            if (tukhoa) {
                window.location.href = '/search/' + toSlug(tukhoa) + '.html';
                return false;
            }
            return false;
        }
    </script>
</head>

<body>
<div id="preloder"><div class="loader"></div></div>
<!-- Humberger Begin -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="#"><img src="./assets/img/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="./cart.html"><i class="fa fa-shopping-bag"></i> <span><?= htmlspecialchars($_SESSION['totalQuantityAmount']) ?></span></a></li>
            <li><a href="./orders.html"><i class="fa fa-cart-arrow-down"></i></a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./profile.html"><i class="fa fa-user-circle"></i></a></li>
            <?php endif; ?>
        </ul>
        <div class="header__cart__price">Total: <span>$<?= number_format($_SESSION['totalAmount'], 2) ?></span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="./index.php?controller=user&action=logout"><i class="fa fa-sign-out"></i> Logout (<?= $_SESSION['user_name'] ?>)</a>
            <?php else: ?>
                <a href="/login.html"><i class="fa fa-user"></i> Login</a>
            <?php endif; ?>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="active"><a href="home.html">Home</a></li>
            <li><a href="products.html">Shop</a></li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-pinterest-p"></i></a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li>
            <li>Free shipping in town</li>
        </ul>
    </div>
</div>
<!-- Humberger End -->
<!-- Header + Menu -->
<header class="header">
    <div class="header__top">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-6"><div class="header__top__left"><ul><li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li><li>Free shipping in town</li></ul></div></div>
                <div class="col-lg-6"><div class="header__top__right">
                    <div class="header__top__right__auth">
                        <?php if ($userId): ?>
                            <a href="./index.php?controller=user&action=logout"><i class="fa fa-sign-out"></i> Logout (<?= $_SESSION['user_name'] ?>)</a>
                        <?php else: ?>
                            <a href="/login.html"><i class="fa fa-user"></i> Login</a>
                        <?php endif; ?>
                    </div>
                </div></div>
            </div>
        </div>
    </div>

    <!-- Header middle -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3"><div class="header__logo"><a href="home.html"><img src="./assets/img/logo.png" alt=""></a></div></div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <?php $currentPath = $_SERVER['REQUEST_URI'] ?? ''; ?>
                    <ul>
                        <li class="<?= strpos($currentPath, 'products') === false ? 'active' : '' ?>"><a href="home.html">Home</a></li>
                        <li class="<?= strpos($currentPath, 'products') !== false ? 'active' : '' ?>"><a href="products.html">Shop</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li><a href="./cart.html"><i class="fa fa-shopping-bag"></i> <span><?= htmlspecialchars($_SESSION['totalQuantityAmount']) ?></span></a></li>
                        <li><a href="./orders.html"><i class="fa fa-cart-arrow-down"></i></a></li>
                        <?php if ($userId): ?><li><a href="./profile.html"><i class="fa fa-user-circle"></i></a></li><?php endif; ?>
                    </ul>
                    <div class="header__cart__price">Total: <span>$<?= number_format($_SESSION['totalAmount'], 2) ?></span></div>
                </div>
            </div>
        </div>
        <div class="humberger__open"><i class="fa fa-bars"></i></div>
    </div>
</header>

<!-- Hero Section -->
<section class="hero<?= $isHomePage ? '' : ' hero-normal' ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all"><i class="fa fa-bars"></i><span>Categories</span></div>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li><a href="/products/category/<?= urlencode($category['slug']) ?>.html"><?= htmlspecialchars($category['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form method="get" onsubmit="return submitHeaderSearch(this);">
                            <input type="text" name="tukhoa" placeholder="Bạn cần tìm gì?">
                            <button type="submit" class="site-btn">TÌM KIẾM</button>
                        </form>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon"><i class="fa fa-phone"></i></div>
                        <div class="hero__search__phone__text">
                            <h5>+65 11.188.888</h5><span>support 24/7 time</span>
                        </div>
                    </div>
                </div>
                <?php if ($isHomePage): ?>
                    <div class="hero__item set-bg" data-setbg="./assets/img/hero/banner2.jpg">
                        <div class="hero__text">
                            <span>Đồ gia dụng giá rẻ</span>
                            <h2>Gia dụng<br />100% Chính hãng</h2>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="container">

    <!-- Huge Discounts -->
    <div class="row mt-4">
        <h4><b>Huge Discounts</b></h4>
        <div class="categories__slider owl-carousel">
            <?php foreach ($topdiscountedproduct as $product): ?>
                <?php 
                $productSlug = $product['slug'] ?? '';
                if (empty($productSlug)) {
                    continue; // Skip products without slug
                }
                
                $images = !empty($product['images']) ? explode(';', $product['images']) : [];
                $firstImage = $images[0] ?? '';
                $imageSrc = !empty($firstImage) ? $firstImage : './assets/img/product-placeholder.jpg';
                
                // For external URLs, use as-is. For local paths, check existence
                if (!filter_var($imageSrc, FILTER_VALIDATE_URL) && !file_exists(__DIR__ . '/' . $imageSrc)) {
                    $imageSrc = './assets/img/product-placeholder.jpg';
                }
                ?>
                <div class="col-lg-3">
                    <div class="categories__item set-bg" style="border:1px solid #ddd;padding:10px;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);background:#fff;">
                        <img src="<?= $imageSrc ?>" height="170" alt="<?= htmlspecialchars($product['name']) ?>" />
                        <h5>
                            <a href="product/<?= $productSlug ?>.html">
                                <?= htmlspecialchars($product['name']); ?><br>
                                <?php
                                    $price = $product['min_price'] ?? $product['price'] ?? 0;
                                ?>
                                <span style="color:#e60000;font-weight:bold;">
                                    $<?= number_format($price, 2) ?>
                                </span>
                            </a>
                        </h5>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Banner -->
    <div class="row mt-4">
        <div class="col-lg-6"><div class="banner__pic"><img src="./assets/img/hero/banner.jpg" alt=""></div></div>
        <div class="col-lg-6"><div class="banner__pic"><img src="./assets/img/hero/banner1.jpg" alt=""></div></div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="latest-product__text">
                <h4>Sản phẩm nổi bật</h4>
                <div class="latest-product__slider owl-carousel">
                    <?php $i = 1; foreach ($topratedproduct as $product): ?>
                        <?php 
                        $productSlug = $product['slug'] ?? '';
                        if (empty($productSlug)) {
                            continue; // Skip products without slug
                        }
                        
                        $images = !empty($product['images']) ? explode(';', $product['images']) : [];
                        $firstImage = $images[0] ?? '';
                        $imageSrc = !empty($firstImage) ? $firstImage : './assets/img/product-placeholder.jpg';
                        
                        // For external URLs, use as-is. For local paths, check existence
                        if (!filter_var($imageSrc, FILTER_VALIDATE_URL) && !file_exists(__DIR__ . '/' . $imageSrc)) {
                            $imageSrc = './assets/img/product-placeholder.jpg';
                        }
                        ?>
                        <a href="product/<?= $productSlug ?>.html" class="latest-product__item">
                            <div class="latest-product__item__pic">
                                <?php 
                                $images = !empty($product['images']) ? explode(';', $product['images']) : [];
                                $firstImage = $images[0] ?? '';
                                $imageSrc = !empty($firstImage) ? $firstImage : './assets/img/product-placeholder.jpg';
                                
                                // For external URLs, use as-is. For local paths, check existence
                                if (!filter_var($imageSrc, FILTER_VALIDATE_URL) && !file_exists(__DIR__ . '/' . $imageSrc)) {
                                    $imageSrc = './assets/img/product-placeholder.jpg';
                                }
                                ?>
                                <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                                <!-- Badge top -->
                                <div class="toprate-label"><?= $i ?><span>★</span></div>
                            </div>
                            <div class="latest-product__item__text">
                                <h6><?= htmlspecialchars($product['name']); ?></h6>
                                <?php
                                    $price = $product['min_price'] ?? $product['price'] ?? 0;
                                ?>
                                <span><span><?= number_format($price, 2) ?></span></div>
                        </a>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>

    <script src="./assets/js/jquery-3.3.1.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery-ui.min.js"></script>
    <script src="./assets/js/jquery.slicknav.js"></script>
    <script src="./assets/js/mixitup.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/main.js"></script>
    <?php
// ✅ Inject session variables vào sessionStorage bằng JS
echo "<script>
    sessionStorage.setItem('user_id', '" . ($_SESSION['user_id'] ?? '') . "');
    sessionStorage.setItem('user_name', '" . ($_SESSION['user_name'] ?? '') . "');
    sessionStorage.setItem('totalAmount', '" . ($_SESSION['totalAmount'] ?? 0) . "');
    sessionStorage.setItem('totalQuantityAmount', '" . ($_SESSION['totalQuantityAmount'] ?? 0) . "');
    sessionStorage.setItem('isHomePage', '" . ($isHomePage ? "true" : "false") . "');
    sessionStorage.setItem('categories', '" . json_encode($categories, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . "');
</script>";
?>
</body>
</html>

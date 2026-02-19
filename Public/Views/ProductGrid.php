<?php
$isHomePage = false;

require_once(__DIR__ . '/../../Config/Database.php');
require_once(__DIR__ . '/../Models/Category_Model.php');
require_once(__DIR__ . '/../Models/Product_Model.php');

$categorymodel = new CategoryModel();
$ProductModel = new ProductModel();

$categories = $categorymodel->getAll();
$topdiscountedproduct = $ProductModel->getTopDiscounted(); // assuming this function exists
$topratedproduct = $ProductModel->getTopRated(); // assuming this function exists

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['user_role'] ?? 'Default';
if (!isset($_SESSION['totalAmount'])) {
    $_SESSION['totalAmount'] = 0;
}
if (!isset($_SESSION['totalQuantityAmount'])) {
    $_SESSION['totalQuantityAmount'] = 0;
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
<!-- Page Preloader -->
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
            <li>Free shipping within city</li>
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
                            <input type="text" name="tukhoa" placeholder="What are you looking for?">
                            <button type="submit" class="site-btn">SEARCH</button>
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
                            <span>Affordable home appliances</span>
                            <h2>Home Appliances<br />100% Authentic</h2>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Product Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Categories</h4>
                        <ul>
                            <?php foreach ($categories as $category): ?>
                                <li><a href="./products/category/<?= urlencode($category['slug']) ?>.html">
                                    <?php echo htmlspecialchars($category['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort" style="position: relative; z-index: 999;">
                                <span>Filter</span>
                                <?php
                                $currentSort = $_GET['sort'] ?? 'default';
                                ?>
                                <select name="sort" onchange="updateSort(this.value);" style="position: relative; z-index: 1001; background: #fff;">
                                    <option value="default" <?= $currentSort == 'default' ? 'selected' : '' ?>>Default</option>
                                    <option value="asc" <?= $currentSort == 'asc' ? 'selected' : '' ?>>Price Low to High</option>
                                    <option value="desc" <?= $currentSort == 'desc' ? 'selected' : '' ?>>Price High to Low</option>
                                    <option value="rating_asc" <?= $currentSort == 'rating_asc' ? 'selected' : '' ?>>Rating Low to High</option>
                                    <option value="rating_desc" <?= $currentSort == 'rating_desc' ? 'selected' : '' ?>>Rating High to Low</option>
                                </select>
                            </div>

                            <script>
                            function updateSort(sort) {
                                // Get current pathname
                                let path = window.location.pathname;
                                // Remove old sort if exists
                                path = path.replace(/\/sort-[a-zA-Z0-9-_]+\.html$/, '.html');
                                // Handle case with .html at end
                                if (path.endsWith('.html')) {
                                    path = path.slice(0, -5); // remove .html
                                }
                                // Add new sort
                                if (sort && sort !== 'default') {
                                    path += '/sort-' + sort;
                                }
                                path += '.html';
                                // Keep other query strings (if any)
                                const params = new URLSearchParams(window.location.search);
                                params.delete('sort');
                                let query = params.toString();
                                if (query) {
                                    window.location.href = path + '?' + query;
                                } else {
                                    window.location.href = path;
                                }
                            }
                            </script>


                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span><?php echo $totalProducts; ?></span> Products Found</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                <?php foreach ($products as $product): ?>
    <?php
        $images = $ProductModel->getImagesByslug($product['slug']);
        
    ?>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            <div class="product__item__pic" style="position: relative;">
                <?php echo $ProductModel->getAvatarImages($images, 270); ?>

                <?php
                    $avgRating = isset($product['avg_rating']) ? round($product['avg_rating'], 1) : 0;
                ?>
                <?php if ($avgRating >= 0): ?>
                    <div style="
                        position: absolute;
                        top: 14px;
                        right: 10px;
                        background: #fffbe6;
                        color: #ffb300;
                        padding: 4px 12px 4px 8px;
                        font-weight: bold;
                        border-radius: 18px;
                        font-size: 15px;
                        z-index: 20;
                        display: flex;
                        align-items: center;
                        gap: 5px;
                        box-shadow: 0 2px 8px rgba(255,193,7,0.10);
                        border: 1.5px solid #ffe082;
                    ">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="#ffc107" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 1px 2px #fffde7);">
                            <polygon points="10,2 12.4,7.5 18.3,7.6 13.6,11.6 15.2,17.3 10,14 4.8,17.3 6.4,11.6 1.7,7.6 7.6,7.5" stroke="#ffb300" stroke-width="0.5" fill="#ffc107"/>
                        </svg>
                        <span style="color:#ff9800; font-weight: bold; font-size: 15px;"> <?= $avgRating ?> </span>
                    </div>
                <?php endif; ?>

                <form action="./index.php?controller=cart&action=addcart" method="POST" style="position: absolute; bottom: 10px; right: 10px; z-index: 20; margin: 0;">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" style="
                        background: #fff;
                        border: none;
                        border-radius: 50%;
                        width: 38px;
                        height: 38px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        transition: box-shadow 0.2s, background 0.2s;
                    " title="Add to Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#ff9800" stroke-width="2">
                            <circle cx="10" cy="20.5" r="1.5"/>
                            <circle cx="18" cy="20.5" r="1.5"/>
                            <path d="M2 2h2l2.4 12.3a2 2 0 0 0 2 1.7h7.6a2 2 0 0 0 2-1.7L20 6H6"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="product__item__text">
                <h6>
                    <a href="product/<?= $product['slug'] ?>.html">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </h6>
                <?php
$minPrice = $product['min_price'] ?? 0;
$maxPrice = $product['max_price'] ?? 0;

// Calculate total stock from all variants
$totalStock = 0;
if (isset($product['variants']) && is_array($product['variants'])) {
    foreach ($product['variants'] as $variant) {
        $totalStock += (int)($variant['stock_quantity'] ?? 0);
    }
}
?>
<div class="product-price-stock">
    <h5 style="color: red;">
        <?php if ($minPrice == $maxPrice): ?>
            $<?= number_format($minPrice, 2) ?>
        <?php else: ?>
            $<?= number_format($minPrice, 2) ?> - $<?= number_format($maxPrice, 2) ?>
        <?php endif; ?>
    </h5>
    <div class="stock-status" style="margin-top: 5px;">
        <?php if ($totalStock > 0): ?>
            <span style="color: #28a745; font-size: 0.9rem;">
                ✓ In Stock (<?= $totalStock ?> items)
            </span>
        <?php else: ?>
            <span style="color: #dc3545; font-size: 0.9rem; font-weight: bold;">
                ✗ Out of Stock
            </span>
        <?php endif; ?>
    </div>
</div>

            </div>
        </div>
    </div>
<?php endforeach; ?>

                </div>

                <style>
                .pagination a.active {
                    background: #7fad39;
                    color: #fff !important;
                    font-weight: bold;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(127,173,57,0.10);
                    border: none;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 36px;
                    min-height: 36px;
                    font-size: 18px;
                }
                .pagination a {
                    color: #333;
                    padding: 6px 14px;
                    margin: 0 2px;
                    text-decoration: none;
                    border: 1px solid #eee;
                    transition: background 0.2s, color 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 36px;
                    min-height: 36px;
                    font-size: 18px;
                }
                .pagination a:hover:not(.active) {
                    background: #eaf7d6;
                }
                </style>
                <div class="product__pagination">
                    <div class="pagination">
                        <?php
                        // Define friendly pagination base url
                        $pageBaseUrl = '';
                        if (!empty($_GET['tukhoa'])) {
                            // Search
                            $tukhoa = urlencode($_GET['tukhoa']);
                            $pageBaseUrl = "./search/{$tukhoa}";
                        } elseif (!empty($_GET['category'])) {
                            // Filter by category
                            $cat = urlencode($_GET['category']);
                            $pageBaseUrl = "./products/category/{$cat}";
                        } else {
                            // All products
                            $pageBaseUrl = "./products";
                        }

                        // Keep other parameters (sort, search, ...), remove controller, action
                        $extraParams = $_GET;
                        unset($extraParams['page'], $extraParams['category'], $extraParams['tukhoa'], $extraParams['controller'], $extraParams['action']);

                        for ($i = 1; $i <= $totalPages; $i++):
                            $url = $pageBaseUrl;
                            if ($i > 1) {
                                $url .= "/trang-{$i}.html";
                            } else {
                                $url .= ".html";
                            }
                            if (!empty($extraParams)) {
                                $url .= '?' . http_build_query($extraParams);
                            }
                        ?>
                            <a href="<?php echo $url; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Product Section End -->

<?php include(__DIR__ . '/../Includes/Header.html'); ?>
    <script src="./assets/js/jquery-3.3.1.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery-ui.min.js"></script>
    <script src="./assets/js/jquery.slicknav.js"></script>
    <script src="./assets/js/mixitup.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/main.js"></script>
<?php include(__DIR__ . '/../Includes/Footer.html'); ?>
</body>
</html>

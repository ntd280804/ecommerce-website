<?php
$isHomePage = false;

require_once(__DIR__ . '/../../Config/Database.php');
require_once(__DIR__ . '/../Models/Category_Model.php');
require_once(__DIR__ . '/../Models/Product_Model.php');

$categorymodel = new CategoryModel();
$ProductModel = new ProductModel();

$categories = $categorymodel->getAll();
$topdiscountedproduct = $ProductModel->getTopDiscounted(); // giả sử hàm này tồn tại
$topratedproduct = $ProductModel->getTopRated(); // giả sử hàm này tồn tại

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
    <base href="/Public/">
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
                window.location.href = '/Public/tim-kiem/' + toSlug(tukhoa) + '.html';
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
            <li><a href="./gio-hang.html"><i class="fa fa-shopping-bag"></i> <span><?= htmlspecialchars($_SESSION['totalQuantityAmount']) ?></span></a></li>
            <li><a href="./don-hang.html"><i class="fa fa-cart-arrow-down"></i></a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./thong-tin-ca-nhan.html"><i class="fa fa-user-circle"></i></a></li>
            <?php endif; ?>
        </ul>
        <div class="header__cart__price">Tổng tiền: <span><?= number_format($_SESSION['totalAmount'], 0, ',', '.') ?> VNĐ</span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="./index.php?controller=user&action=logout"><i class="fa fa-sign-out"></i> Đăng xuất (<?= $_SESSION['user_name'] ?>)</a>
            <?php else: ?>
                <a href="/Public/dang-nhap.html"><i class="fa fa-user"></i> Đăng nhập</a>
            <?php endif; ?>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="active"><a href="trang-chu.html">Trang chủ</a></li>
            <li><a href="tat-ca-san-pham.html">Cửa hàng</a></li>
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
            <li>Freeship nội thành</li>
        </ul>
    </div>
</div>
<!-- Humberger End -->
<!-- Header + Menu -->
<header class="header">
    <div class="header__top">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-6"><div class="header__top__left"><ul><li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li><li>Freeship nội thành</li></ul></div></div>
                <div class="col-lg-6"><div class="header__top__right">
                    <div class="header__top__right__auth">
                        <?php if ($userId): ?>
                            <a href="./index.php?controller=user&action=logout"><i class="fa fa-sign-out"></i> Đăng xuất (<?= $_SESSION['user_name'] ?>)</a>
                        <?php else: ?>
                            <a href="/Public/dang-nhap.html"><i class="fa fa-user"></i> Đăng nhập</a>
                        <?php endif; ?>
                    </div>
                </div></div>
            </div>
        </div>
    </div>

    <!-- Header middle -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3"><div class="header__logo"><a href="trang-chu.html"><img src="./assets/img/logo.png" alt=""></a></div></div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <?php $currentPath = $_SERVER['REQUEST_URI'] ?? ''; ?>
                    <ul>
                        <li class="<?= strpos($currentPath, 'tat-ca-san-pham') === false ? 'active' : '' ?>"><a href="trang-chu.html">Trang chủ</a></li>
                        <li class="<?= strpos($currentPath, 'tat-ca-san-pham') !== false ? 'active' : '' ?>"><a href="tat-ca-san-pham.html">Cửa hàng</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li><a href="./gio-hang.html"><i class="fa fa-shopping-bag"></i> <span><?= htmlspecialchars($_SESSION['totalQuantityAmount']) ?></span></a></li>
                        <li><a href="./don-hang.html"><i class="fa fa-cart-arrow-down"></i></a></li>
                        <?php if ($userId): ?><li><a href="./thong-tin-ca-nhan.html"><i class="fa fa-user-circle"></i></a></li><?php endif; ?>
                    </ul>
                    <div class="header__cart__price">Tổng tiền: <span><?= number_format($_SESSION['totalAmount'], 0, ',', '.') ?> VNĐ</span></div>
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
                    <div class="hero__categories__all"><i class="fa fa-bars"></i><span>Danh mục</span></div>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li><a href="/Public/tat-ca-san-pham/danh-muc/<?= urlencode($category['slug']) ?>.html"><?= htmlspecialchars($category['name']) ?></a></li>
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
<!-- Product Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Danh mục</h4>
                        <ul>
                            <?php foreach ($categories as $category): ?>
                                <li><a href="./tat-ca-san-pham/danh-muc/<?= urlencode($category['slug']) ?>.html">
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
                                <span>Lọc</span>
                                <?php
                                $currentSort = $_GET['sort'] ?? 'default';
                                ?>
                                <select name="sort" onchange="updateSort(this.value);" style="position: relative; z-index: 1001; background: #fff;">
                                    <option value="default" <?= $currentSort == 'default' ? 'selected' : '' ?>>Default</option>
                                    <option value="asc" <?= $currentSort == 'asc' ? 'selected' : '' ?>>Giá từ thấp đến cao</option>
                                    <option value="desc" <?= $currentSort == 'desc' ? 'selected' : '' ?>>Giá từ cao đến thấp</option>
                                    <option value="rating_asc" <?= $currentSort == 'rating_asc' ? 'selected' : '' ?>>Đánh giá từ thấp đến cao</option>
                                    <option value="rating_desc" <?= $currentSort == 'rating_desc' ? 'selected' : '' ?>>Đánh giá từ cao đến thấp</option>
                                </select>
                            </div>

                            <script>
                            function updateSort(sort) {
                                // Lấy pathname hiện tại
                                let path = window.location.pathname;
                                // Xóa sort cũ nếu có
                                path = path.replace(/\/sort-[a-zA-Z0-9-_]+\.html$/, '.html');
                                // Xử lý trường hợp có .html ở cuối
                                if (path.endsWith('.html')) {
                                    path = path.slice(0, -5); // bỏ .html
                                }
                                // Thêm sort mới
                                if (sort && sort !== 'default') {
                                    path += '/sort-' + sort;
                                }
                                path += '.html';
                                // Giữ lại các query string khác (nếu có)
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
                                <h6><span><?php echo $totalProducts; ?></span> Sản phẩm được tìm thấy</h6>
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
                    " title="Thêm vào giỏ hàng">
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
                    <a href="san-pham/<?= $product['slug'] ?>.html">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </h6>
                <?php
$role = $_SESSION['user_role'] ?? 'default';
$price = match(strtolower($role)) {
    'vip1' => $product['price_vip1'] ?? $product['price'],
    'vip2' => $product['price_vip2'] ?? $product['price'],
    default => $product['price'],
};
?>
<h5 style="color: red;">
    <?= number_format($price, 0, ',', '.') ?> VNĐ
</h5>

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
                        // Xác định base url phân trang thân thiện
                        $pageBaseUrl = '';
                        if (!empty($_GET['tukhoa'])) {
                            // Tìm kiếm
                            $tukhoa = urlencode($_GET['tukhoa']);
                            $pageBaseUrl = "./tim-kiem/{$tukhoa}";
                        } elseif (!empty($_GET['category'])) {
                            // Lọc theo danh mục
                            $cat = urlencode($_GET['category']);
                            $pageBaseUrl = "./tat-ca-san-pham/danh-muc/{$cat}";
                        } else {
                            // Tất cả sản phẩm
                            $pageBaseUrl = "./tat-ca-san-pham";
                        }

                        // Giữ lại các tham số khác (sort, search, ...), loại bỏ controller, action
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("/Public/Includes/Header.html")
        .then(res => res.text())
        .then(data => {
            document.body.insertAdjacentHTML("afterbegin", data);
        });

    fetch("/Public/Includes/Footer.html")
        .then(res => res.text())
        .then(data => {
            document.body.insertAdjacentHTML("beforeend", data);
        });
});
</script>

    <script src="./assets/js/jquery-3.3.1.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery-ui.min.js"></script>
    <script src="./assets/js/jquery.slicknav.js"></script>
    <script src="./assets/js/mixitup.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>

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
<div class="container">
    <h2>Đánh giá đơn hàng #<?= htmlspecialchars($order['id']) ?></h2>
    <form action="index.php?controller=review&action=create&order_id=<?= htmlspecialchars($order['id']) ?>" method="POST">
    <div class="form-group">
    <label for="rating">Đánh giá (1-5 sao):</label>
    <div id="star-rating" style="font-size: 1.5rem; cursor: pointer;">
        <input type="hidden" name="rating" id="rating" value="5" required>
        <span data-value="1" class="star">&#9734;</span>
        <span data-value="2" class="star">&#9734;</span>
        <span data-value="3" class="star">&#9734;</span>
        <span data-value="4" class="star">&#9734;</span>
        <span data-value="5" class="star">&#9734;</span>
    </div>
</div>

<script>
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating');
    
    function setStars(rating) {
        stars.forEach((star, index) => {
            if(index < rating) {
                star.textContent = '★'; // sao đầy
                star.style.color = '#f39c12';
            } else {
                star.textContent = '☆'; // sao trống
                star.style.color = '#ccc';
            }
        });
    }
    
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = parseInt(star.getAttribute('data-value'));
            ratingInput.value = val;
            setStars(val);
        });
    });
    
    // Khởi tạo mặc định 5 sao trống (rating 0 hoặc 5 tùy bạn)
    setStars(parseInt(ratingInput.value));
</script>

        <div class="form-group">
            <label for="content">Nội dung đánh giá:</label>
            <textarea name="content" class="form-control" rows="5" placeholder="Nhập nhận xét của bạn..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
    </form>
</div>
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
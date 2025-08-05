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
<!-- Product Details Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
            <div class="product__details__pic">
                <!-- Avatar chính (ảnh đại diện đầu tiên) -->

                <div class="product__details__pic__item">
                    <?php echo str_replace('<img ', '<img id="main-product-avatar" ', $ProductModel->getAvatarImages($images, 400)); ?>
                </div>

                <!-- Thumbnails hiển thị tất cả ảnh nhỏ -->
                <div class="product__details__pic__thumbs" id="product-thumbs" style="display: flex; gap: 10px; margin-top: 12px;">
                    <?php
                    $arrImages = array_values(array_filter(array_map('trim', explode(';', $images))));
                    foreach ($arrImages as $idx => $img) {
                        $active = $idx === 0 ? 'thumb-active' : '';
                        echo "<img class='slider-thumb $active' data-imgbigurl='" . htmlspecialchars($img) . "' src='" . htmlspecialchars($img) . "' alt='Product Image' style='cursor:pointer; width:60px; height:60px; object-fit:cover; border-radius:8px; border:2px solid #eee;'>";
                    }
                    ?>
                </div>
            </div>

            <style>
            .slider-thumb.thumb-active {
                border: 2.5px solid #ff9800 !important;
                box-shadow: 0 2px 8px rgba(255,152,0,0.12);
            }
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var thumbs = document.querySelectorAll('#product-thumbs .slider-thumb');
                var mainImg = document.getElementById('main-product-avatar');
                if (thumbs.length > 0 && mainImg) {
                    thumbs.forEach(function(thumb, idx) {
                        thumb.addEventListener('click', function() {
                            mainImg.src = thumb.getAttribute('data-imgbigurl');
                            document.querySelectorAll('#product-thumbs .slider-thumb').forEach(function(t) {
                                t.classList.remove('thumb-active');
                            });
                            thumb.classList.add('thumb-active');
                        });
                    });
                }
            });
            </script>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h2 style="font-size:1.5rem; margin-bottom: 12px; line-height:1.2;"><?= htmlspecialchars($product['name']) ?></h2>
                    <?php
                        $avg = round($ratingData['avg_rating'], 1);
                        $total = $ratingData['total_reviews'];

                        $fullStars = floor($avg);
                        $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    ?>

                    <div class="product__details__rating" style="display: flex; align-items: center; gap: 8px;">
                        <?php for ($i = 0; $i < $fullStars; $i++): ?>
                            <i class="fa fa-star"></i>
                        <?php endfor; ?>

                        <?php if ($halfStar): ?>
                            <i class="fa fa-star-half-o"></i>
                        <?php endif; ?>

                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                            <i class="fa fa-star-o"></i>
                        <?php endfor; ?>

                        <span style="font-weight: bold; margin-left: 6px;"><?= $avg ?> / 5</span>
                        <span>(<?= $total ?> đánh giá)</span>
                    </div>
                    <?php
$role = $_SESSION['user_role'] ?? 'default';
$price = match (strtolower($role)) {
    'vip1' => $product['price_vip1'] ?? $product['price'],
    'vip2' => $product['price_vip2'] ?? $product['price'],
    default => $product['price'],
};
?>
<div class="product__details__price" style="font-size:1.2rem; font-weight:bold; color:#e60000; margin-bottom:8px;">
    <?= number_format($price, 0, ',', '.') ?> VNĐ
</div>

                    <h3 style="font-size:1.1rem; margin: 10px 0 6px 0;">Tóm tắt sản phẩm</h3>
                    <p><?= nl2br(htmlspecialchars($product['summary'])) ?></p>
                        <form action="./index.php?controller=cart&action=addcart" method="POST" aria-label="Thêm vào giỏ hàng">
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="number" name="qty" value="1" min="1">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="primary-btn">Thêm vào giỏ hàng</button>
                        </form>
                    


                    <ul>
                    <li><b>Danh mục</b> <span><?= htmlspecialchars($ProductModel->getCategoryNameById($product['category_id'])) ?></span></li>
                    
                        
                        <li><b>Vận chuyển</b> <span>Giao hàng trong ngày.</span></li>
                        
                        <li><b>Chia sẽ trên</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                               aria-selected="true">Mô tả</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                               aria-selected="false">Nhận xét <span>(<?= $total ?>)</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h2 style="font-size:1.1rem; margin-bottom:8px;">Mô tả sản phẩm</h2>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                        <div class="product__details__tab__desc">
                            <h2 style="font-size:1.1rem; margin-bottom:8px;">Đánh giá khách hàng</h2>
                            <?php if (!empty($productReviews)) : ?>
                                <?php foreach ($productReviews as $review) : ?>
                                    <div class="review">
                                        <div class="review-header" style="display: flex; align-items: center; gap: 15px;">
                                            <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                                            <span><?= date('F j, Y', strtotime($review['created_at'])) ?></span>

                                            <?php
                                            $reviewRating = floatval($review['rating']);
                                            $fullStars = floor($reviewRating);
                                            $halfStar = ($reviewRating - $fullStars) >= 0.5 ? 1 : 0;
                                            $emptyStars = 5 - $fullStars - $halfStar;
                                            ?>

                                            <div class="review-rating" style="color: #f8ce0b;">
                                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                                    <i class="fa fa-star"></i>
                                                <?php endfor; ?>

                                                <?php if ($halfStar): ?>
                                                    <i class="fa fa-star-half-o"></i>
                                                <?php endif; ?>

                                                <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                                    <i class="fa fa-star-o"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    </div>

                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>Chưa có đánh giá, hãy là người đánh giá đầu tiên!</p>
                            <?php endif; ?>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Product Details Section End -->

<?php
// Thêm title động chuẩn SEO
echo "<script>document.title = '" . htmlspecialchars(
    (isset(
        $product['name']) ? $product['name'] . " | OGANI" : "Chi tiết sản phẩm | OGANI")
) . "';</script>";
 ?>
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
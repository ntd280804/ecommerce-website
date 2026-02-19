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
    <style>
        .checkout__payment__method {
            margin-top: 10px;
        }
        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border: 2px solid #f0f0f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option:hover {
            border-color: #7fad39;
            background-color: #f9f9f9;
        }
        .payment-option input[type="radio"] {
            margin-right: 15px;
            width: 18px;
            height: 18px;
        }
        .payment-option input[type="radio"]:checked + .checkmark {
            background-color: #7fad39;
            border-color: #7fad39;
        }
        .payment-info {
            flex: 1;
        }
        .payment-info strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-size: 16px;
        }
        .payment-info small {
            color: #666;
            font-size: 14px;
        }
        .payment-option input[type="radio"]:checked ~ .payment-info strong {
            color: #7fad39;
        }
        .disabled-payment-option {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }
        .disabled-payment-option:hover {
            border-color: #f0f0f0;
            background-color: #f5f5f5;
        }
        .disabled-payment-option input[type="radio"] {
            cursor: not-allowed;
        }
        .disabled-payment-option .payment-info strong {
            color: #999;
        }
        .disabled-payment-option .payment-info small {
            color: #bbb;
        }
    </style>
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
                window.location.href = '/Public/search/' + toSlug(tukhoa) + '.html';
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
                <a href="/Public/login.html"><i class="fa fa-user"></i> Login</a>
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
                <div class="col-lg-6"><div class="header__top__left"><ul><li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li><li>Freeship nội thành</li></ul></div></div>
                <div class="col-lg-6"><div class="header__top__right">
                    <div class="header__top__right__auth">
                        <?php if ($userId): ?>
                            <a href="./index.php?controller=user&action=logout"><i class="fa fa-sign-out"></i> Logout (<?= $_SESSION['user_name'] ?>)</a>
                        <?php else: ?>
                            <a href="/Public/login.html"><i class="fa fa-user"></i> Login</a>
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
                            <li><a href="/Public/products/category/<?= urlencode($category['slug']) ?>.html"><?= htmlspecialchars($category['name']) ?></a></li>
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
        <div class="container">
            <div class="row">
            </div>
            <div class="checkout__form">
                <h4>Bill Details</h4>
                <form action="./index.php?controller=order&action=placeorder" method="POST">
                    <div class="row">
                        <!-- Payment and recipient information -->
                        <div class="col-lg-12">
                            <h4>Payment and Recipient Information</h4>

                            <div class="checkout__input">
                                <p>Receiver Name<span>*</span></p>
                                <input type="text" name="receiver_name" required placeholder="Enter receiver name">
                            </div>

                            <div class="checkout__input">
                                <p>Receiver Phone<span>*</span></p>
                                <input type="tel" name="receiver_phone" required placeholder="Enter phone number">
                            </div>

                            <div class="checkout__input">
                                <p>Receiver Address<span>*</span></p>
                                <input type="text" name="receiver_address" required placeholder="Enter address">
                            </div>

                            <div class="checkout__input">
                                <p>Payment Method<span>*</span></p>
                                <div class="checkout__payment__method">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        <span class="checkmark"></span>
                                        <div class="payment-info">
                                            <strong>Cash on Delivery (COD)</strong>
                                            <small>Pay when you receive the order</small>
                                        </div>
                                    </label>
                                    <label class="payment-option disabled-payment-option">
                                        <input type="radio" name="payment_method" value="online" disabled>
                                        <span class="checkmark"></span>
                                        <div class="payment-info">
                                            <strong>Online Payment</strong>
                                            <small>Pay now with credit card or banking</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Your order -->
                        <div class="col-lg-12 col-md-12">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Product <span>Total</span></div>
                                <ul>
                                    <?php foreach ($cartItems as $item): ?>
                                    <li><?php echo htmlspecialchars($item['product_name']); ?><span><?php echo number_format($item['price'] * $item['qty'], 2); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="checkout__order__total">Total <span><?php echo number_format($totalAmount, 2); ?></span></div>
                                <button type="submit" class="site-btn">Place Order</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    <!-- Checkout Section End -->
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
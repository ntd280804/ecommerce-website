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
            const searchTerm = form.search.value.trim();
            if (searchTerm) {
                window.location.href = '/search/' + toSlug(searchTerm) + '.html';
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
            <li>Free shipping</li>
        </ul>
    </div>
</div>
<!-- Humberger End -->
<!-- Header + Menu -->
<header class="header">
    <div class="header__top">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-6"><div class="header__top__left"><ul><li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li><li>Free shipping</li></ul></div></div>
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
                            <li><a href="/products/category/<?= urlencode($category['slug']) ?>.html"><?= htmlspecialchars($category['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form method="get" onsubmit="return submitHeaderSearch(this);">
                            <input type="text" name="search" placeholder="Search...">
                            <button type="submit" class="site-btn">Search</button>
                        </form>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon"><i class="fa fa-phone"></i></div>
                        <div class="hero__search__phone__text">
                            <h5>+65 11.188.888</h5><span>Support 24/7</span>
                        </div>
                    </div>
                </div>
                <?php if ($isHomePage): ?>
                    <div class="hero__item set-bg" data-setbg="./assets/img/hero/banner2.jpg">
                        <div class="hero__text">
                            <span>Shopping Cart</span>
                            <h2>Shopping Cart</h2>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Shoping Cart Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td class="shoping__cart__item">
                                        <?php echo $cartModel->getAvatarImages($item['images'], 100); ?>
                                        <h5><?php echo $item['product_name']; ?></h5>
                                        <?php if (!empty($item['variant_name'])): ?>
                                            <small class="text-muted">Variant: <?php echo htmlspecialchars($item['variant_name']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="shoping__cart__price">
                                        $<?php echo number_format($item['price'], 2); ?>
                                    </td>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
    // Function to handle quantity updates
    function updateQuantity(input, change) {
        const form = input.closest('form');
        const currentQty = parseInt(input.value) || 0;
        const newQty = Math.max(1, currentQty + change); // Ensure quantity doesn't go below 1
        
        input.value = newQty;
        form.submit();
    }

    // Initialize quantity controls
    document.querySelectorAll('.quantity-control').forEach(control => {
        const input = control.querySelector('.quantity-input');
        const minusBtn = control.querySelector('.qtybtn.minus');
        const plusBtn = control.querySelector('.qtybtn.plus');
        
        // Handle minus button click
        minusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const currentQty = parseInt(input.value) || 1;
            if (currentQty > 1) {
                updateQuantity(input, -1);
            } else {
                // If quantity would go below 1, remove the item
                const form = input.closest('form');
                const productId = form.querySelector('input[name="product_id"]').value;
                const variantId = form.querySelector('input[name="variant_id"]')?.value;
                let deleteUrl = `./index.php?controller=cart&action=deletecart&product_id=${productId}`;
                if (variantId) {
                    deleteUrl += `&variant_id=${variantId}`;
                }
                window.location.href = deleteUrl;
            }
        });
        
        // Handle plus button click
        plusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            updateQuantity(input, 1);
        });
        
        // Handle manual input
        input.addEventListener('change', function() {
            const currentQty = parseInt(input.value) || 1;
            if (currentQty < 1) {
                input.value = 1;
            }
            input.value = Math.max(1, currentQty); // Ensure minimum quantity is 1
            input.closest('form').submit();
        });
    });
});
                                    </script>
                                    <td class="shoping__cart__quantity">
                                        <form action="./index.php?controller=cart&action=updatecart" method="POST" class="quantity-control">
                                            <div class="quantity">
                                                <div class="cart-qty">
                                                    <span class="qtybtn minus">-</span>
                                                    <input type="number" name="qty" class="quantity-input" value="<?php echo $item['qty']; ?>" min="1">
                                                    <span class="qtybtn plus">+</span>
                                                </div>
                                            </div>
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <?php if (!empty($item['variant_id'])): ?>
                                                <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                    <td class="shoping__cart__total">
                                        $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <a href="./index.php?controller=cart&action=deletecart&product_id=<?php echo $item['product_id']; ?><?php if (!empty($item['variant_id'])): ?>&variant_id=<?php echo $item['variant_id']; ?><?php endif; ?>">
                                            <span class="icon_close"></span>
                                        </a>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="./home.html" class="primary-btn cart-btn">Continue Shopping</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Total <span>$<?php echo number_format($totalAmount, 2); ?></span></li>
                    </ul>
                    <a href="./checkout.html" class="primary-btn">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
<!-- Shoping Cart Section End -->

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
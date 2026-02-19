<?php
$isHomePage = false;

require_once(__DIR__ . '/../../Config/Database.php');
require_once(__DIR__ . '/../Models/Category_Model.php');
require_once(__DIR__ . '/../Models/Product_Model.php');

$categorymodel = new CategoryModel();
$ProductModel = new ProductModel();

$categories = $categorymodel->getAll();
$topdiscountedproduct = $ProductModel->getTopDiscounted();
$topratedproduct = $ProductModel->getTopRated();

// Get product with variants
$slug = $_GET['slug'] ?? '';
$product = $ProductModel->getByslug($slug);

if (!$product) {
    echo "Product does not exist!";
    exit;
}

// Prepare price range display
if (isset($product['min_price']) && isset($product['max_price'])) {
    if ($product['min_price'] == $product['max_price']) {
        $product['price_range'] = '$' . number_format($product['min_price'], 2);
    } else {
        $product['price_range'] = '$' . number_format($product['min_price'], 2) . ' - $' . number_format($product['max_price'], 2);
    }
} else {
    $product['price_range'] = '$' . number_format($product['price'] ?? 0, 2);
}

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
                <div class="col-lg-6"><div class="header__top__left"><ul><li><i class="fa fa-envelope"></i> hello@NguyenTranDinh</li><li>Freeship nội thành</li></ul></div></div>
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
<!-- Product Details Section Begin -->
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
            <div class="product__details__pic">
                <!-- Main avatar (first representative image) -->

                <div class="product__details__pic__item">
                    <?php 
                    $images = $ProductModel->getImagesByslug($product['slug']);
                    echo str_replace('<img ', '<img id="main-product-avatar" ', $ProductModel->getAvatarImages($images, 400)); 
                    ?>
                </div>

                <!-- Thumbnails display all small images -->
                <div class="product__details__pic__thumbs" id="product-thumbs" style="display: flex; gap: 10px; margin-top: 12px;">
                    <?php
                    $images = $ProductModel->getImagesByslug($product['slug']);
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
                    <div class="product__details__price" style="font-size:1.2rem; font-weight:bold; color:#e60000; margin-bottom:8px;">
                        <?= htmlspecialchars($product['price_range']) ?>
                    </div>

                    <!-- Product Variants Selection -->
                    <?php if (!empty($product['variants'])): ?>
                    <div class="product__variants" style="margin-bottom: 25px;">
                        <h4 style="font-size:1.1rem; margin: 15px 0 15px 0; color: #333;">Select variant:</h4>
                        
                        <!-- Variants Grid -->
                        <div class="variants-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 15px;">
                            <?php foreach ($product['variants'] as $index => $variant): ?>
                            <div class="variant-card" style="border: 2px solid #eee; border-radius: 12px; padding: 15px; cursor: pointer; transition: all 0.3s ease; position: relative;" 
                                 onclick="selectVariant(<?= $index ?>, <?= $variant['id'] ?>, <?= $variant['price'] ?>, '<?= htmlspecialchars($variant['name']) ?>', <?= $variant['stock_quantity'] ?>)">
                                
                                <!-- Variant Header -->
                                <div class="variant-header" style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <div class="variant-radio" style="width: 20px; height: 20px; border: 2px solid #ddd; border-radius: 50%; margin-right: 10px; position: relative; background: #fff;">
                                        <div class="radio-dot" style="width: 10px; height: 10px; background: #ff9800; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;"></div>
                                    </div>
                                    <div class="variant-name" style="font-weight: 600; color: #333; font-size: 0.95rem;">
                                        <?= htmlspecialchars($variant['name']) ?>
                                    </div>
                                </div>
                                
                                <!-- Variant Price -->
                                <div class="variant-price" style="color: #e60000; font-weight: bold; font-size: 1.1rem; margin-bottom: 8px; display: none;">
                                    <span class="price-value">$<?= number_format($variant['price'], 2) ?></span>
                                </div>
                                
                                <!-- Variant Stock -->
                                <div class="variant-stock" style="font-size: 0.85rem; color: #666;">
                                    <?php if ($variant['stock_quantity'] > 0): ?>
                                        <span style="color: #28a745;"> In Stock</span> 
                                        <span style="margin-left: 5px;">(<?= $variant['stock_quantity'] ?> items)</span>
                                    <?php else: ?>
                                        <span style="color: #dc3545;"> Out of Stock</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Variant Image (if available) -->
                                <?php if (!empty($variant['image_url'])): ?>
                                <div class="variant-image" style="position: absolute; top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                    <img src="<?= htmlspecialchars($variant['image_url']) ?>" alt="<?= htmlspecialchars($variant['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Selected Variant Info -->
                        <div class="selected-variant-info" style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #ff9800; margin-top: 10px;">
                            <div style="font-size: 0.9rem; color: #666; margin-bottom: 5px;">Selected:</div>
                            <div id="selectedInfo" style="font-weight: 600; color: #333;">
                                <?= htmlspecialchars($product['variants'][0]['name']) ?>
                            </div>
                            <div id="selectedPrice" style="font-weight: bold; color: #e60000; margin-top: 5px; display: none;">
                                $<?= number_format($product['variants'][0]['price'], 2) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <h3 style="font-size:1.1rem; margin: 10px 0 6px 0;">Product Summary</h3>
                    <p><?= nl2br(htmlspecialchars($product['summary'])) ?></p>
                    
                    <form action="./index.php?controller=cart&action=addcart" method="POST" id="addToCartForm">
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="number" name="qty" id="quantity" value="1" min="1">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="<?= $product['variants'][0]['id'] ?>">
                        <input type="hidden" name="variant_name" id="selectedVariantName" value="<?= htmlspecialchars($product['variants'][0]['name']) ?>">
                        <input type="hidden" name="variant_price" id="selectedVariantPrice" value="<?= $product['variants'][0]['price'] ?>">
                        <input type="hidden" name="variant_stock" id="selectedVariantStock" value="<?= $product['variants'][0]['stock_quantity'] ?>">
                        
                        <button type="submit" class="primary-btn" id="addToCartBtn">
                            <i class="fa fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                    
                    <!-- Stock Status Notification -->
                    <div id="stockNotification" style="display: none; margin-top: 10px; padding: 10px; border-radius: 5px;"></div>

                    <script>
                    function showStockNotification(stock) {
                        const notification = document.getElementById('stockNotification');
                        if (!notification) return;
                        
                        if (stock <= 0) {
                            notification.textContent = 'This variant is currently out of stock.';
                            notification.style.backgroundColor = '#f8d7da';
                            notification.style.color = '#721c24';
                            notification.style.display = 'block';
                        } else if (stock < 5) {
                            notification.textContent = `Only ${stock} item(s) left in stock!`;
                            notification.style.backgroundColor = '#fff3cd';
                            notification.style.color = '#856404';
                            notification.style.display = 'block';
                        } else {
                            notification.style.display = 'none';
                        }
                    }
                    
                    function selectVariant(index, variantId, price, name, stock) {
                        // Update form hidden inputs
                        document.getElementById('selectedVariantId').value = variantId;
                        document.getElementById('selectedVariantName').value = name;
                        document.getElementById('selectedVariantPrice').value = price;
                        document.getElementById('selectedVariantStock').value = stock;
                        
                        // Update selected info display
                        document.getElementById('selectedInfo').textContent = name;
                        const selectedPrice = document.getElementById('selectedPrice');
                        selectedPrice.textContent = '$' + price.toFixed(2);
                        selectedPrice.style.display = 'block';
                        
                        // Show price for selected variant, hide others
                        document.querySelectorAll('.variant-card').forEach((card, i) => {
                            const priceDiv = card.querySelector('.variant-price');
                            const radioDot = card.querySelector('.radio-dot');
                            if (i === index) {
                                card.style.borderColor = '#ff9800';
                                card.style.backgroundColor = '#fff8e1';
                                card.style.boxShadow = '0 4px 12px rgba(255,152,0,0.15)';
                                if (radioDot) radioDot.style.display = 'block';
                                if (priceDiv) priceDiv.style.display = 'block';
                            } else {
                                card.style.borderColor = '#eee';
                                card.style.backgroundColor = '#fff';
                                card.style.boxShadow = 'none';
                                if (radioDot) radioDot.style.display = 'none';
                                if (priceDiv) priceDiv.style.display = 'none';
                            }
                        });
                        
                        // Update button state based on stock
                        updateAddToCartButton(stock);
                        
                        // Show stock notification
                        showStockNotification(stock);
                    }
                    
                    function updateAddToCartButton(stock) {
                        const addToCartBtn = document.getElementById('addToCartBtn');
                        const stockNotification = document.getElementById('stockNotification');
                        
                        if (stock <= 0) {
                            addToCartBtn.textContent = 'Out of Stock';
                            addToCartBtn.disabled = true;
                            addToCartBtn.style.backgroundColor = '#dc3545';
                            addToCartBtn.style.cursor = 'not-allowed';
                        } else {
                            addToCartBtn.textContent = 'Add to Cart';
                            addToCartBtn.disabled = false;
                            addToCartBtn.style.backgroundColor = '';
                            addToCartBtn.style.cursor = 'pointer';
                        }
                    }
                    
                    // Initialize on page load - don't show any prices initially
                    document.addEventListener('DOMContentLoaded', function() {
                        selectVariant(0, <?= $product['variants'][0]['id'] ?>, <?= $product['variants'][0]['price'] ?>, '<?= htmlspecialchars($product['variants'][0]['name']) ?>', <?= $product['variants'][0]['stock_quantity'] ?>);
                        // Hide all variant prices initially
                        document.querySelectorAll('.variant-price').forEach(priceDiv => {
                            priceDiv.style.display = 'none';
                        });
                        // Hide selected price initially
                        const selectedPrice = document.getElementById('selectedPrice');
                        if (selectedPrice) {
                            selectedPrice.style.display = 'none';
                        }
                    });
                    </script>
                    


                    <ul>
                    <li><b>Category</b> <span><?= htmlspecialchars($ProductModel->getCategoryNameById($product['category_id'])) ?></span></li>
                    
                        
                        <li><b>Shipping</b> <span>Same day delivery.</span></li>
                        
                        <li><b>Share on</b>
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
                               aria-selected="true">Description</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h2 style="font-size:1.1rem; margin-bottom:8px;">Product Description</h2>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Product Details Section End -->

<?php
// Add dynamic SEO title
echo "<script>document.title = '" . htmlspecialchars(
    (isset(
        $product['name']) ? $product['name'] . " | OGANI" : "Product Details | OGANI")
) . "';</script>";
 ?>
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
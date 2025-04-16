<?php
$isHomePage = false;
require("Includes/Header.php");
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="./assets/img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                        <a href="#"><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></a>
                        <a href="#"><?= htmlspecialchars($product['name']) ?></a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
            <div class="product__details__pic">
                <!-- Avatar chính (ảnh đại diện đầu tiên) -->
                <div class="product__details__pic__item">
                    <?php echo $productmodel->getAvatarImages($images, 400); ?>
                </div>

                <!-- Slider hiển thị tất cả ảnh -->
                <div class="product__details__pic__slider owl-carousel">
                    <?php
                    $arrImages = explode(';', $images);
                    foreach ($arrImages as $img) {
                        if (!empty($img)) {
                            echo "<img data-imgbigurl='" . htmlspecialchars($img) . "' src='" . htmlspecialchars($img) . "' alt='Product Image'>";
                        }
                    }
                    ?>
                </div>
            </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="product__details__rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half-o"></i>
                        <span>(<?= rand(10, 100) ?> reviews)</span>
                    </div>
                    <div class="product__details__price">$<?= number_format($product['price'], 2) ?></div>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1">
                            </div>
                        </div>
                    </div>
                    <a href="#" class="primary-btn">ADD TO CART</a>
                    <a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
                    <ul>
                        <li><b>Availability</b> <span><?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></span></li>
                        <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                        
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
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                               aria-selected="false">Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                               aria-selected="false">Reviews <span>(1)</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Product Description</h6>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Additional Information</h6>
                                <p>Category: <?= htmlspecialchars($product['category_id'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Customer Reviews</h6>
                                <p>This product is really great! Totally worth it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->

<?php require("Includes/Footer.php"); ?>

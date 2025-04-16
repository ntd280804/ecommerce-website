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
                        <a href="./index.php?controller=product&action=index&category=<?php echo urlencode($category['id']); ?>"><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></a>
                        <span><?= htmlspecialchars($product['name']) ?></span>
                        
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
                    <?php
                        $avg = round($ratingData['avg_rating'], 1);
                        $total = $ratingData['total_reviews'];

                        $fullStars = floor($avg);
                        $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    ?>

                    <div class="product__details__rating">
                        <?php for ($i = 0; $i < $fullStars; $i++): ?>
                            <i class="fa fa-star"></i>
                        <?php endfor; ?>

                        <?php if ($halfStar): ?>
                            <i class="fa fa-star-half-o"></i>
                        <?php endif; ?>

                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                            <i class="fa fa-star-o"></i>
                        <?php endfor; ?>

                        <span>(<?= $total ?> reviews)</span>
                    </div>

                    <div class="product__details__price">$<?= number_format($product['price'], 2) ?></div>
                    <p><?= nl2br(htmlspecialchars($product['summary'])) ?></p>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1">
                            </div>
                        </div>
                    </div>
                    <a href="#" class="primary-btn">ADD TO CART</a>
                    <ul>
                    <li><b>Category</b> <span><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></span></li>
                    
                        <li><b>Availability</b> <span><?= $product['stock']?></span></li>
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
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                               aria-selected="false">Reviews <span>(<?= $total ?>)</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Product Description</h6>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                        <div class="product__details__tab__desc">
                            <h6>Customer Reviews</h6>
                            <?php if (!empty($productReviews)) : ?>
                                <?php foreach ($productReviews as $review) : ?>
                                    <div class="review">
                                        <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                                        <span><?= date('F j, Y', strtotime($review['created_at'])) ?></span>
                                        <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No reviews yet. Be the first to review this product!</p>
                            <?php endif; ?>
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

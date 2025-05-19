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


                    <div class="product__details__price"><?= number_format($product['discounted_price'], 2) ?>VNĐ</div>
                    <p><?= nl2br(htmlspecialchars($product['summary'])) ?></p>
                    <?php if ($product['stock'] > 0): ?>
                        <form action="./index.php?controller=cart&action=addcart" method="POST">
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="primary-btn">Thêm vào giỏ hàng</button>
                        </form>
                    <?php else: ?>
                        <div class="out-of-stock">
                            <p style="color: red; font-weight: bold;">Hết hàng</p>
                        </div>
                    <?php endif; ?>


                    <ul>
                    <li><b>Danh mục</b> <span><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></span></li>
                    
                        <li><b>Tồn kho</b> <span><?= $product['stock']?></span></li>
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
                                <h6>Mô tả sản phẩm</h6>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                        <div class="product__details__tab__desc">
                            <h6>Đánh giá khách hàng</h6>
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
</section>
<!-- Product Details Section End -->

<?php require("Includes/Footer.php"); ?>

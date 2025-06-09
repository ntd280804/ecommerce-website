<?php
$isHomePage = true; // Set this variable to true for the home page
require("Includes/Header.php"); 
?>
    <!-- Categories Section Begin -->
    <section class="categories">
    <div class="container">
        <div class="row">
            <h4><b>Ưu đãi khủng</b></h4>
            <div class="categories__slider owl-carousel">
                <?php foreach ($topdiscountedproduct as $product): ?>
                    <?php
                        $images = $productmodel->getImagesById($product['id']);
                        $discountPercent = round($product['discount_percent'], 0); // Lấy % giảm và làm tròn
                    ?>
                    <div class="col-lg-3">
                    <div class="categories__item set-bg" style="border: 1px solid #ddd; padding: 10px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background-color: #fff; position: relative;">
                        
                        <!-- Nhãn giảm giá -->
                        <?php if ($discountPercent > 0): ?>
                            <div style="
                                position: absolute;
                                top: 10px;
                                left: 10px;
                                background: #ff4d4d;
                                color: white;
                                font-weight: bold;
                                padding: 5px 10px;
                                border-radius: 5px;
                                font-size: 14px;
                                z-index: 10;
                            ">
                                -<?= $discountPercent ?>%
                            </div>
                        <?php endif; ?>

                        <?php if ($product['stock'] == 0): ?>
                            <div style="
                                position: absolute;
                                bottom: 140px;
                                left: 0;
                                width: 100%;
                                background: #b22222;
                                color: white;
                                padding: 5px 0;
                                font-weight: bold;
                                border-radius: 0 0 8px 8px;
                                font-size: 14px;
                                text-align: center;
                                z-index: 10;
                            ">
                                Hết hàng
                            </div>
                        <?php endif; ?>

                        <?php echo $productmodel->getAvatarImages($images, 170); ?>
                        
                        <h5>
                            <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                                <br>
                                <span style="color:#e60000; font-weight: bold;">
                                    <?= number_format($product['discounted_price'], 0, ',', '.') ?> VNĐ
                                </span>
                                <br>
                                <del style="color:#999; font-size: 14px;">
                                    <?= number_format($product['price'], 0, ',', '.') ?> VNĐ
                                </del>
                            </a>
                        </h5>
                    </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

    <!-- Categories Section End -->

    <!-- Banner Begin -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="./assets/img/hero/banner.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                    <img src="./assets/img/hero/banner1.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Latest Product Section Begin -->
<section class="latest-product spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="latest-product__text">
                    <h4>Sản phẩm nổi bật</h4>
                    <div class="latest-product__slider owl-carousel">
                    <?php
$i = 1; // Khởi tạo biến đếm
foreach ($topratedproduct as $product): 
    $isOutOfStock = isset($product['stock']) && $product['stock'] == 0;
?>
    <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="latest-product__item" style="position: relative;">
        <div class="latest-product__item__pic" style="position: relative;">
            <img src="<?php echo explode(';', $product['images'])[0]; ?>" alt="">

            <div class="toprate-label" style="
                position: absolute;
                top: 10px;
                left: 10px;
                background: #ff4d4d;
                color: white;
                font-weight: bold;
                padding: 5px 10px;
                border-radius: 50%;
                font-size: 16px;
                ">
                <?= $i ?>
            </div>

            <?php if ($isOutOfStock): ?>
                <div style="
                    position: absolute;
                    bottom: 40px;
                    left: 0px;
                    background: #b22222;
                    color: white;
                    padding: 5px 32px;
                    font-weight: bold;
                    border-radius: 5px;
                    font-size: 14px;
                    z-index: 10;
                ">
                    Hết hàng
                </div>
            <?php endif; ?>
        </div>
        <div class="latest-product__item__text">
            <h6><?php echo htmlspecialchars($product['name']); ?></h6>
            <span><?php echo number_format($product['discounted_price'], 0, ',', '.'); ?> VNĐ</span>
            <br>
            <small>
                <?php
                    $avgRating = isset($product['avg_rating']) ? round($product['avg_rating'], 1) : 0;
                    $reviewCount = isset($product['review_count']) ? (int)$product['review_count'] : 0;
                ?>
                Rating: <?= $avgRating ?> ★ | Reviews: <?= $reviewCount ?>
            </small>
        </div>
    </a>
<?php 
    $i++; // Tăng biến đếm lên 1
endforeach; 
?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Product Section End -->


    <?php
require("Includes/Footer.php"); 
?>
<?php
$isHomePage = true; // Set this variable to true for the home page
require("Includes/Header.php"); 
?>
    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    
                    <?php foreach ($products as $product): ?>
                        <?php
                            $images = $productmodel->getImagesById($product['id']);
                            ?>
                    <div class="col-lg-3">

                        <div class="categories__item set-bg">
                        <?php echo $productmodel->getAvatarImages($images, 400); ?>
                            <h5>
                                <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
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
                        <img src="./assets/img/hero/banner.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                    <img src="./assets/img/hero/banner.png" alt="">
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
                    <h4>Top Rated Products</h4>
                    <div class="latest-product__slider owl-carousel">
                        <?php 
                        $chunks = array_chunk($topratedproduct, 3); // Mỗi slide chứa 3 sản phẩm
                        foreach ($chunks as $group): ?>
                            <div class="latest-product__slider__item">
                                <?php foreach ($group as $product): ?>
                                    <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="latest-product__item">
                                        <div class="latest-product__item__pic">
                                            <img src="<?php echo explode(';', $product['images'])[0]; ?>" alt="">
                                        </div>
                                        <div class="latest-product__item__text">
                                            <h6><?php echo htmlspecialchars($product['name']); ?></h6>
                                            <span><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
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
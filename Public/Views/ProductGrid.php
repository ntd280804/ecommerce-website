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
                    <h2>Organi Shop</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Trang chủ</a>
                        <span>Cửa hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Danh mục</h4>
                        <ul>
                            <?php foreach ($categories as $category): ?>
                                <li><a href="index.php?controller=product&action=index&category=<?php echo urlencode($category['id']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort">
                                <span>Lọc</span>
                                <?php
                                $currentSort = $_GET['sort'] ?? 'default';
                                ?>
                                <select name="sort" onchange="updateSort(this.value);" style="position: relative; z-index: 200;">

                                    <option value="default" <?= $currentSort == 'default' ? 'selected' : '' ?>>Default</option>
                                    <option value="asc" <?= $currentSort == 'asc' ? 'selected' : '' ?>>Giá từ thấp đến cao</option>
                                    <option value="desc" <?= $currentSort == 'desc' ? 'selected' : '' ?>>Giá từ cao đến thấp</option>
                                    <option value="rating_asc" <?= $currentSort == 'rating_asc' ? 'selected' : '' ?>>Đánh giá từ thấp đến cao</option>
                                    <option value="rating_desc" <?= $currentSort == 'rating_desc' ? 'selected' : '' ?>>Đánh giá từ cao đến thấp</option>
                                </select>
                            </div>

                            <script>
                            function updateSort(sort) {
                                const params = new URLSearchParams(window.location.search);
                                params.set('sort', sort);
                                window.location.href = window.location.pathname + '?' + params.toString();
                            }
                            </script>


                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span><?php echo $totalProducts; ?></span> Sản phẩm được tìm thấy</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                <?php foreach ($products as $product): ?>
    <?php
        $images = $productmodel->getImagesById($product['id']);
        $discountPercent = 0;
        if ($product['price'] > 0) {
            $discountPercent = round((($product['price'] - $product['discounted_price']) / $product['price']) * 100);
        }
    ?>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            <div class="product__item__pic" style="position: relative;">
                <?php echo $productmodel->getAvatarImages($images, 270); ?>

                <?php if ($discountPercent > 0): ?>
                    <div style="
                        position: absolute;
                        top: 10px;
                        left: 10px;
                        background: #ff4d4d;
                        color: white;
                        padding: 5px 8px;
                        font-weight: bold;
                        border-radius: 5px;
                        font-size: 14px;
                        z-index: 10;
                    ">
                        -<?= $discountPercent ?>%
                    </div>
                <?php endif; ?>

                <?php
                    $avgRating = isset($product['avg_rating']) ? round($product['avg_rating'], 1) : 0;
                ?>
                <?php if ($avgRating >= 0): ?>
                    <div style="
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: #f39c12;
                        color: white;
                        padding: 5px 8px;
                        font-weight: bold;
                        border-radius: 5px;
                        font-size: 14px;
                        z-index: 10;
                        display: flex;
                        align-items: center;
                        gap: 4px;
                    ">
                        <span style="font-size: 16px;">⭐</span> <?= $avgRating ?>
                    </div>
                <?php endif; ?>

                <?php if ($product['stock'] == 0): ?>
                    <div style="
                        position: absolute;
                        bottom: 120px;
                        left: 0px;
                        background: #b22222;
                        color: white;
                        padding: 5px 103px;
                        font-weight: bold;
                        border-radius: 5px;
                        font-size: 14px;
                        z-index: 10;
                    ">
                        Hết hàng
                    </div>
                <?php endif; ?>

                <ul class="product__item__pic__hover">
                    <?php if ($product['stock'] > 0): ?>
                    <li>
                        <form action="./index.php?controller=cart&action=addcart" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        </form>
                    </li>
                    <?php else: ?>
                    <li>
                        <button disabled style="background:#ccc; border:none; padding:5px 10px; border-radius:4px; cursor:not-allowed;">
                            Hết hàng
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="product__item__text">
                <h6>
                    <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </h6>
                <h5 style="color: red;">
                    <?php echo number_format($product['discounted_price'], 2); ?> VNĐ
                </h5>

                <h5><del><?php echo number_format($product['price'], 2); ?> VNĐ</del></h5>
            </div>
        </div>
    </div>
<?php endforeach; ?>

                </div>

                <div class="product__pagination">
                    <div class="pagination">
                        <?php
                        // Lấy tham số GET hiện tại
                        $queryParams = $_GET;
                        for ($i = 1; $i <= $totalPages; $i++):
                            $queryParams['page'] = $i; // cập nhật page mới
                            $queryString = http_build_query($queryParams);
                        ?>
                            <a href="index.php?<?php echo $queryString; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->

<?php
require("Includes/Footer.php"); 
?>

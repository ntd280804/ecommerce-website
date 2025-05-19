<?php
$isHomePage=false;
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
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
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
                                
                            <li><a href="index.php?controller=product&action=index&category=<?php echo urlencode($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></a></li>
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
                                <span>Sort By</span>
                                <?php
                                $currentSort = $_GET['sort'] ?? 'default';
                                ?>

                                <select name="sort" onchange="updateSort(this.value);">
                                    <option value="default" <?= $currentSort == 'default' ? 'selected' : '' ?>>Default</option>
                                    <option value="asc" <?= $currentSort == 'asc' ? 'selected' : '' ?>>Giá từ thấp đến cao</option>
                                    <option value="desc" <?= $currentSort == 'desc' ? 'selected' : '' ?>>Giá từ cao đến thấp</option>
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
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic" >
                                    <?php echo $productmodel->getAvatarImages($images, 400); ?>
                                        <ul class="product__item__pic__hover">
                                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>

                                    <div class="product__item__text">
                                        <h6>
                                            <a href="./index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                        </h6>
                                        <h5><?php echo number_format($product['discounted_price'], 2); ?>VNĐ</h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>

                    <div class="product__pagination">
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="index.php?controller=product&action=index&page=<?php echo $i; ?>" 
                                class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
<?php
require("Includes/Footer.php"); 
?>
<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 

?>
<div class="container">

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Cập nhật sản phẩm</h1>
                    </div>
                    <form class="user" method="post" action="./index.php?controller=product&action=update" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="name" name="name" aria-describedby="name"
                                placeholder="Nhập tên sản phẩm" value="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="form-group">
    <label>Các hình ảnh của sản phẩm</label>

    <?php
    $images = explode(';', $product['images']);
    if (!empty($images)) :
    ?>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php foreach ($images as $img) : ?>
                <?php if (!empty($img)) : ?>
                    <div style="text-align: center;">
                        <img src="<?= htmlspecialchars($img) ?>" height="100px" alt="Ảnh sản phẩm"><br>
                        <a href="" 
                           onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')">Xóa</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <input type="file" class="form-control form-control-user mt-2"
           id="images" name="images[]" aria-describedby="images" multiple>
</div>


                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="slug" name="slug" aria-describedby="slug"
                                placeholder="Nhập slug sản phẩm" value="<?= htmlspecialchars($product['slug']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tóm tắt</label>
                            <textarea name="summary" class="form-control" placeholder="Nhập..."><?= htmlspecialchars($product['summary']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" placeholder="Nhập..."><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 mb-sm-0">
                                <input type="text" class="form-control form-control-user"
                                    id="stock" name="stock" aria-describedby="stock"
                                    placeholder="Nhập số lượng" value="<?= htmlspecialchars($product['stock']) ?>">
                            </div>
                            
                            <div class="col-sm-4 mb-sm-0">
                            <input type="text" class="form-control form-control-user"
                                id="price" name="price" aria-describedby="price"
                                placeholder="Nhập giá gốc" value="<?= htmlspecialchars($product['price']) ?>">
                            </div>
                            <div class="col-sm-4 mb-sm-0">
                            <input type="text" class="form-control form-control-user"
                                id="disscounted_price" name="disscounted_price" aria-describedby="disscounted_price"
                                placeholder="Nhập giá bán" value="<?= htmlspecialchars($product['disscounted_price']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Danh mục</label>
                            <select class="form-control" name="category_id">
                                <option>Chọn danh mục</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>" 
                                        <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="form-group">
                        <label class="form-label">Thương hiệu</label>
                            <select class="form-control"  name="brand_id">
                                <option>Chọn thương hiệu</option>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= htmlspecialchars($brand['id']) ?>"
                                        <?= $brand['id'] == $product['brand_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Cập nhật</button>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php
require("Includes/Footer.php"); 
?>

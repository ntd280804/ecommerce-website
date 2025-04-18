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
                        <h1 class="h4 text-gray-900 mb-4">Thêm sản phẩm</h1>
                    </div>
                    <form class="user" method="post" action="./index.php?controller=product&action=store" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="name" name="name" aria-describedby="name"
                                placeholder="Nhập tên sản phẩm">
                        </div>
                        <div class="form-group">
                            <label>Các hình ảnh của sản phẩm</label>
                            <input type="file" class="form-control form-control-user"
                                id="images" name="images[]" aria-describedby="images"
                                multiple
                                >
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="slug" name="slug" aria-describedby="slug"
                                placeholder="Nhập slug sản phẩm">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tóm tắt</label>
                            <textarea name="summary" class="form-control" placeholder="Nhập..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" placeholder="Nhập..."></textarea>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mb-sm-0">
                                <input type="text" class="form-control form-control-user"
                                    id="stock" name="stock" aria-describedby="stock"
                                    placeholder="Nhập số lượng">
                            </div>
                            
                            <div class="col-sm-4 mb-sm-0">
                            <input type="text" class="form-control form-control-user"
                                id="price" name="price" aria-describedby="price"
                                placeholder="Nhập giá gốc">
                            </div>
                            <div class="col-sm-4 mb-sm-0">
                            <input type="text" class="form-control form-control-user"
                                id="discounted_price" name="discounted_price" aria-describedby="discounted_price"
                                placeholder="Nhập giá bán">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Danh mục</label>
                            <select class="form-control" name="category_id">
                                <option>Chọn danh mục</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Thương hiệu</label>
                            <select class="form-control"  name="brand_id">
                                <option>Chọn thương hiệu</option>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= htmlspecialchars($brand['id']) ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Tạo mới</button>
                        
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

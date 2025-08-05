<?php
require("Includes/Header.php");
require_once("../Config/Database.php");
?>
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Cập nhật sản phẩm</h1>
                        </div>
                        <form class="user" method="post" action="./index.php?controller=product&action=update" enctype="multipart/form-data" id="product-form">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                            <div class="form-group">
                                <label>Tên sản phẩm</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên sản phẩm" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Hình ảnh sản phẩm</label>
                                <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php
                                    $images = explode(';', $product['images']);
                                    foreach ($images as $index => $img) :
                                        if (!empty($img)) :
                                    ?>
                                        <div class="image-item" data-index="<?= $index ?>" style="text-align: center;">
                                            <img src="<?= htmlspecialchars($img) ?>" height="100px" alt="Ảnh sản phẩm"><br>
                                            <a href="javascript:void(0)" class="delete-image" data-src="<?= htmlspecialchars($img) ?>" style="color: red;">Xóa</a>
                                        </div>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                                <input type="file" class="form-control mt-2" id="images" name="images[]" multiple>
                                <input type="hidden" id="imageList" name="imageList">
                            </div>

                            <div class="form-group">
                                <label>Slug sản phẩm</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Nhập slug sản phẩm" value="<?= htmlspecialchars($product['slug']) ?>">
                            </div>

                            <div class="form-group">
                                <label>Tóm tắt</label>
                                <textarea name="summary" class="form-control" placeholder="Nhập tóm tắt"><?= htmlspecialchars($product['summary']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control" placeholder="Nhập mô tả"><?= htmlspecialchars($product['description']) ?></textarea>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>Giá gốc</label>
                                    <input type="number" min="0" step="0.01" class="form-control" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                                </div>
                                <div class="col-sm-4">
                                    <label>Giá VIP 1</label>
                                    <input type="number" min="0" step="0.01" class="form-control" name="price_vip1" value="<?= htmlspecialchars($product['price_vip1']) ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label>Giá VIP 2</label>
                                    <input type="number" min="0" step="0.01" class="form-control" name="price_vip2" value="<?= htmlspecialchars($product['price_vip2']) ?>">
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Danh mục</label>
                                <select class="form-control" name="category_id" required>
                                    <option>Chọn danh mục</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Thương hiệu</label>
                                <select class="form-control" name="brand_id" required>
                                    <option>Chọn thương hiệu</option>
                                    <?php foreach ($brands as $brand) : ?>
                                        <option value="<?= htmlspecialchars($brand['id']) ?>" <?= $brand['id'] == $product['brand_id'] ? 'selected' : '' ?>><?= htmlspecialchars($brand['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button class="btn btn-primary mt-3" type="submit">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let imagesArray = <?= json_encode($images) ?>;

function renderImages() {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    imagesArray.forEach((src, index) => {
        const div = document.createElement('div');
        div.className = 'image-item';
        div.style.textAlign = 'center';
        div.innerHTML = `<img src="${src}" height="100px"><br>
                         <a href="javascript:void(0)" class="delete-image" data-index="${index}" style="color: red;">Xóa</a>`;
        preview.appendChild(div);
    });
    document.getElementById('imageList').value = imagesArray.join(';');

    document.getElementById('images').disabled = imagesArray.length >= 10;
}

document.getElementById('images').addEventListener('change', function(event) {
    const files = Array.from(event.target.files);

    if (imagesArray.length + files.length > 10) {
        alert('Tối đa chỉ được chọn 10 ảnh.');
        event.target.value = '';
        return;
    }

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagesArray.push(e.target.result);
            renderImages();
        };
        reader.readAsDataURL(file);
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-image')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        imagesArray.splice(index, 1);
        renderImages();
    }
});

renderImages();

document.getElementById('product-form').addEventListener('submit', function(e) {
    if (imagesArray.length === 0) {
        e.preventDefault();
        alert('Vui lòng chọn ít nhất một ảnh sản phẩm.');
    }
});

</script>

<?php
require("Includes/Footer.php");
?>

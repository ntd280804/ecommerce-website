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
                        <!-- Product Basic Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                        id="name" name="name" aria-describedby="name"
                                        placeholder="Nhập tên sản phẩm" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                        id="slug" name="slug" aria-describedby="slug"
                                        placeholder="Nhập slug sản phẩm" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tóm tắt</label>
                                    <textarea name="summary" class="form-control" placeholder="Nhập tóm tắt..." required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả chi tiết..." required></textarea>
                                </div>
                                
                                <!-- Product Images -->
                                <div class="form-group">
                                    <label class="form-label">Hình ảnh sản phẩm</label>
                                    <input type="file" class="form-control" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-sm-0">
                                        <label class="form-label">Danh mục</label>
                                        <select class="form-control" name="category_id" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                                                    </div>
                            </div>
                        </div>

                        <!-- Product Variants -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Phân loại sản phẩm</h6>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addVariant()">
                                    <i class="fas fa-plus"></i> Thêm phân loại
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="variantsContainer">
                                    <!-- First variant (required) -->
                                    <div class="variant-item border rounded p-3 mb-3">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="variant_name[]" placeholder="Tên phân loại" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" name="variant_sku[]" placeholder="SKU" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="number" min="0" step="0.01" class="form-control" name="variant_price[]" placeholder="Giá" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="number" min="0" class="form-control" name="variant_stock[]" placeholder="Tồn kho" value="0">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="file" class="form-control" name="variant_images[]" accept="image/*">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant(this)" style="display:none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-lg" type="submit">Tạo mới</button>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
function previewImages(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    const files = event.target.files;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.style.marginRight = '10px';
            img.style.marginBottom = '10px';
            img.style.border = '1px solid #ddd';
            img.style.borderRadius = '4px';
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    }
}

function addVariant() {
    const container = document.getElementById('variantsContainer');
    const variantCount = container.querySelectorAll('.variant-item').length;
    
    const variantHtml = `
        <div class="variant-item border rounded p-3 mb-3">
            <div class="form-group row">
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="variant_name[]" placeholder="Tên phân loại" required>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="variant_sku[]" placeholder="SKU" required>
                </div>
                <div class="col-sm-2">
                    <input type="number" min="0" step="0.01" class="form-control" name="variant_price[]" placeholder="Giá" required>
                </div>
                <div class="col-sm-2">
                    <input type="number" min="0" class="form-control" name="variant_stock[]" placeholder="Tồn kho" value="0">
                </div>
                <div class="col-sm-2">
                    <input type="file" class="form-control" name="variant_images[]" accept="image/*">
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHtml);
    updateRemoveButtons();
}

function removeVariant(button) {
    button.closest('.variant-item').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const variants = document.querySelectorAll('.variant-item');
    const removeButtons = document.querySelectorAll('[onclick="removeVariant(this)"]');
    
    // Hide remove button if only one variant
    removeButtons.forEach((btn, index) => {
        btn.style.display = variants.length > 1 ? 'block' : 'none';
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
});
</script>

<?php
require("Includes/Footer.php"); 
?>

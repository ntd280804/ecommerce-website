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
                            <h1 class="h4 text-gray-900 mb-4">Update Product</h1>
                        </div>
                        <form class="user" method="post" action="./index.php?controller=product&action=update" enctype="multipart/form-data" id="product-form">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Product Images</label>
                                <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php
                                    $images = explode(';', $product['images']);
                                    foreach ($images as $index => $img) :
                                        if (!empty($img)) :
                                    ?>
                                        <div class="image-item" data-index="<?= $index ?>" style="text-align: center;">
                                            <img src="<?= htmlspecialchars($img) ?>" height="100px" alt="Product image"><br>
                                            <a href="javascript:void(0)" class="delete-image" data-src="<?= htmlspecialchars($img) ?>" style="color: red;">Delete</a>
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
                                <label>Product Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter product slug" value="<?= htmlspecialchars($product['slug']) ?>">
                            </div>

                            <div class="form-group">
                                <label>Summary</label>
                                <textarea name="summary" class="form-control" placeholder="Enter summary"><?= htmlspecialchars($product['summary']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" placeholder="Enter description"><?= htmlspecialchars($product['description']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category_id" required>
                                    <option>Select Category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Product Variants Section -->
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label><strong>Product Variants</strong></label>
                                    <button type="button" class="btn btn-sm btn-success" onclick="addVariant()">+ Add Variant</button>
                                </div>
                                <div id="variants-container">
                                    <?php
                                    require_once __DIR__ . '/../Models/ProductVariant_Model.php';
                                    $variantModel = new ProductVariantModel();
                                    $variants = $variantModel->getByProductId($product['id']);
                                    foreach ($variants as $index => $variant):
                                    ?>
                                        <div class="variant-item border rounded p-3 mb-3" data-variant-id="<?= $variant['id'] ?>">
                                            <input type="hidden" name="variant_id[]" value="<?= $variant['id'] ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Variant Name</label>
                                                    <input type="text" class="form-control" name="variant_name[]" value="<?= htmlspecialchars($variant['name']) ?>" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>SKU</label>
                                                    <input type="text" class="form-control" name="variant_sku[]" value="<?= htmlspecialchars($variant['sku']) ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Price</label>
                                                    <input type="number" min="0" step="0.01" class="form-control" name="variant_price[]" value="<?= htmlspecialchars($variant['price']) ?>" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Stock</label>
                                                    <input type="number" min="0" class="form-control" name="variant_stock[]" value="<?= htmlspecialchars($variant['stock_quantity']) ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Image</label>
                                                    <input type="file" class="form-control" name="variant_images[]" accept="image/*">
                                                    <?php if ($variant['image_url']): ?>
                                                        <small class="d-block mt-1">
                                                            <img src="<?= htmlspecialchars($variant['image_url']) ?>" height="30" alt="Variant image">
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>&nbsp;</label><br>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant(this)">×</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button class="btn btn-primary mt-3" type="submit">Update</button>
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
                         <a href="javascript:void(0)" class="delete-image" data-index="${index}" style="color: red;">Delete</a>`;
        preview.appendChild(div);
    });
    document.getElementById('imageList').value = imagesArray.join(';');

    document.getElementById('images').disabled = imagesArray.length >= 10;
}

document.getElementById('images').addEventListener('change', function(event) {
    const files = Array.from(event.target.files);

    if (imagesArray.length + files.length > 10) {
        alert('Maximum 10 images allowed.');
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
        alert('Please select at least one product image.');
    }
});

// Variant management functions
function addVariant() {
    const container = document.getElementById('variants-container');
    const variantCount = container.children.length;
    
    const variantHtml = `
        <div class="variant-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label>Variant Name</label>
                    <input type="text" class="form-control" name="variant_name[]" required>
                </div>
                <div class="col-md-2">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="variant_sku[]">
                </div>
                <div class="col-md-2">
                    <label>Price</label>
                    <input type="number" min="0" step="0.01" class="form-control" name="variant_price[]" required>
                </div>
                <div class="col-md-2">
                    <label>Stock</label>
                    <input type="number" min="0" class="form-control" name="variant_stock[]">
                </div>
                <div class="col-md-2">
                    <label>Image</label>
                    <input type="file" class="form-control" name="variant_images[]" accept="image/*">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant(this)">×</button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHtml);
}

function removeVariant(button) {
    if (confirm('Are you sure you want to delete this variant?')) {
        button.closest('.variant-item').remove();
    }
}

</script>

<?php
require("Includes/Footer.php");
?>

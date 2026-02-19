<?php require("Includes/Header.php"); ?>
<?php require_once("../Config/Database.php"); ?>

<!-- Display bulk action message -->
<?php if (isset($_SESSION['bulk_message'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['bulk_message']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['bulk_message']); ?>
<?php endif; ?>

<!-- Display success message -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Tabs to filter by status -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo (empty($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'active') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=active">active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'inactive') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=inactive">inactive</a>
    </li>
</ul>

<!-- DataTable -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
        <div class="bulk-actions d-none">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                    <i class="fas fa-check"></i> Kích hoạt
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('inactive')">
                    <i class="fas fa-times"></i> Vô hiệu hóa
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
            <span class="ml-2 text-muted" id="selected-count">0 đã chọn</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form id="bulkActionForm" method="POST" action="./index.php?controller=product&action=bulkAction">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th width="50">ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Phân loại</th>
                            <th>Trạng thái</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php if (!empty($products)) : ?>
                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ids[]" value="<?= $product['id'] ?>" class="form-check-input product-checkbox">
                                    </td>
                                    <td><?= htmlspecialchars($product['id']) ?></td>
                                    <td><?= $productmodel->getAvatarImages($product['images'], '100px') ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></td>
                                    <td><?= htmlspecialchars($product['price_range'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($product['total_stock'] ?? 0) ?></td>
                                    <td>
                                        <?php if (!empty($product['variants'])): ?>
                                            <div class="variant-info">
                                                <?php foreach ($product['variants'] as $variant): ?>
                                                    <?php if (is_array($variant)): ?>
                                                        <div class="variant-item" style="border: 1px solid #ddd; padding: 5px; margin: 2px 0; font-size: 12px;">
                                                            <?php if (isset($variant['name'])): ?>
                                                                <strong><?= htmlspecialchars($variant['name']) ?></strong><br>
                                                            <?php endif; ?>
                                                            <?php if (isset($variant['price'])): ?>
                                                                Giá: <?= number_format($variant['price'], 0, ',', '.') ?> VNĐ<br>
                                                            <?php endif; ?>
                                                            <?php if (isset($variant['stock_quantity'])): ?>
                                                                Tồn: <?= htmlspecialchars($variant['stock_quantity']) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Không có phân loại</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $product['status'] == 'active' ? 'success' : 'secondary' ?>">
                                            <?= htmlspecialchars($product['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                                href="./index.php?controller=product&action=edit&id=<?= $product['id'] ?>"
                                                onclick="return confirm('Bạn có chắc muốn sửa sản phẩm này?');">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <?php if ($product['status'] == 'active') : ?>
                                            <a class="btn btn-danger btn-sm"
                                                href="./index.php?controller=product&action=toggleStatus&id=<?= $product['id'] ?>&status=inactive"
                                                onclick="return confirm('Bạn có chắc muốn vô hiệu hóa sản phẩm này?');">
                                                <i class="fas fa-times"></i> inactive
                                            </a>
                                        <?php else : ?>
                                            <a class="btn btn-success btn-sm"
                                                href="./index.php?controller=product&action=toggleStatus&id=<?= $product['id'] ?>&status=active"
                                                onclick="return confirm('Bạn có chắc muốn kích hoạt sản phẩm này?');">
                                                <i class="fas fa-check"></i> active
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for bulk actions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.querySelector('.bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });
    
    // Individual checkbox change
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateSelectedCount();
        });
    });
    
    function updateSelectAllCheckbox() {
        const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
    }
    
    function updateSelectedCount() {
        const checkedCount = Array.from(productCheckboxes).filter(cb => cb.checked).length;
        selectedCount.textContent = checkedCount + ' đã chọn';
        
        if (checkedCount > 0) {
            bulkActions.classList.remove('d-none');
        } else {
            bulkActions.classList.add('d-none');
        }
    }
    
    // Bulk action function
    window.bulkAction = function(action) {
        const checkedBoxes = Array.from(productCheckboxes).filter(cb => cb.checked);
        
        if (checkedBoxes.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm!');
            return;
        }
        
        let confirmMessage = '';
        switch(action) {
            case 'activate':
                confirmMessage = `Bạn có chắc muốn kích hoạt ${checkedBoxes.length} sản phẩm đã chọn?`;
                break;
            case 'inactive':
                confirmMessage = `Bạn có chắc muốn vô hiệu hóa ${checkedBoxes.length} sản phẩm đã chọn?`;
                break;
            case 'delete':
                confirmMessage = `Bạn có chắc muốn xóa ${checkedBoxes.length} sản phẩm đã chọn? Hành động này không thể hoàn tác!`;
                break;
        }
        
        if (confirm(confirmMessage)) {
            const form = document.getElementById('bulkActionForm');
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'bulk_action';
            actionInput.value = action;
            form.appendChild(actionInput);
            
            form.submit();
        }
    };
});
</script>

<?php require("Includes/Footer.php"); ?>

<?php require("Includes/Header.php"); ?>
<?php require_once("../Config/Database.php"); ?>

<!-- Tabs to filter by status -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo (empty($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'active') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=active">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'inactive') ? 'active' : ''; ?>" 
           href="./index.php?controller=product&action=index&status=inactive">Inactive</a>
    </li>
</ul>

<!-- DataTable -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Images</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        
                        <th>Price</th>
                        <th>Price VIP 1</th>
                        <th>Price VIP 2</th>
                        <th>Status</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><?= $productmodel->getAvatarImages($product['images'], '100px') ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($productmodel->getCategoryNameById($product['category_id'])) ?></td>
                            <td><?= htmlspecialchars($productmodel->getBrandNameById($product['brand_id'])) ?></td>
                            
                            <td><?= htmlspecialchars($product['price']) ?></td>
                            <td><?= htmlspecialchars($product['price_vip1']) ?></td>
                            <td><?= htmlspecialchars($product['price_vip2']) ?></td>
                            <td><?= htmlspecialchars($product['status']) ?></td>

                            <td>
                            <a class="btn btn-warning"
                                    href="./index.php?controller=product&action=edit&id=<?= $product['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn sửa sản phẩm này?');">Edit</a>

                                <?php if ($product['status'] == 'Active') : ?>
                                    <a class="btn btn-danger"
                                        href="./index.php?controller=product&action=toggleStatus&id=<?= $product['id'] ?>&status=<?= $product['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn vô hiệu hóa sản phẩm này?');">Inactivate</a>
                                <?php else : ?>
                                    <a class="btn btn-success"
                                        href="./index.php?controller=product&action=toggleStatus&id=<?= $product['id'] ?>&status=<?= $product['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn kích hoạt sản phẩm này?');">Activate</a>
                                <?php endif; ?>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require("Includes/Footer.php"); ?>

<?php require("Includes/Header.php"); ?>
<?php require_once("../Config/Database.php"); ?>

<!-- Tabs to filter by status -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo (empty($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
           href="./index.php?controller=category&action=index&status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'active') ? 'active' : ''; ?>" 
           href="./index.php?controller=category&action=index&status=active">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'inactive') ? 'active' : ''; ?>" 
           href="./index.php?controller=category&action=index&status=inactive">Inactive</a>
    </li>
</ul>

<!-- DataTable -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh mục sản phẩm</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Operations</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <td><?= htmlspecialchars($category['id']) ?></td>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td><?= htmlspecialchars($category['slug']) ?></td>
                                <td><?= htmlspecialchars($category['status']) ?></td>
                                <td>
                                <a class="btn btn-warning"
                                    href="./index.php?controller=category&action=edit&id=<?= $category['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn sửa danh mục này?');">Edit
                                </a>
                                    
                                <?php if ($category['status'] == 'Active') : ?>
                                    <!-- If the category is active, show a button to deactivate it -->
                                    <a class="btn btn-danger"
                                    href="./index.php?controller=category&action=toggleStatus&id=<?= $category['id'] ?>&status=<?= $category['status'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn vô hiệu hóa thương hiệu này?');">Inactivate</a>
                                <?php else : ?>
                                    <!-- If the category is inactive, show a button to activate it -->
                                    <a class="btn btn-success"
                                    href="./index.php?controller=category&action=toggleStatus&id=<?= $category['id'] ?>&status=<?= $category['status'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn kích hoạt thương hiệu này?');">Activate</a>
                                <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require("Includes/Footer.php"); ?>

<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 
?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách thương hiệu</h6>
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
                    <?php if (!empty($brands)) : ?>
                        <?php foreach ($brands as $brand) : ?>
                            <tr>
                                <td><?= htmlspecialchars($brand['id']) ?></td>
                                <td><?= htmlspecialchars($brand['name']) ?></td>
                                <td><?= htmlspecialchars($brand['slug']) ?></td>
                                <td><?= htmlspecialchars($brand['status']) ?></td>
                                <td>
                                    <a class="btn btn-warning">Edit</a>
                                    <a class="btn btn-danger"
                                    href="./index.php?controller=brand&action=delete&id=<?= $brand['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa mục này?');">Delete</a>
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

<?php
require("Includes/Footer.php"); 
?>

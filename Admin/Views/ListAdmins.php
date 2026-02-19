<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo (empty($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
           href="./index.php?controller=admin&action=index&status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'active') ? 'active' : ''; ?>" 
           href="./index.php?controller=admin&action=index&status=active">active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'inactive') ? 'active' : ''; ?>" 
           href="./index.php?controller=admin&action=index&status=inactive">inactive</a>
    </li>
</ul>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách người quản trị</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Cập nhật</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Cập nhật</th>
                        <th>Thao tác</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($admins)) : ?>
                        <?php foreach ($admins as $admin) : ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['id']) ?></td>
                                <td><?= htmlspecialchars($admin['name']) ?></td>
                                <td><?= htmlspecialchars($admin['email']) ?></td>
                                <td><?= htmlspecialchars($admin['phone']) ?></td>
                                <td><?= htmlspecialchars($admin['address']) ?></td>
                                <td><?= htmlspecialchars($admin['status']) ?></td>
                                <td><?= htmlspecialchars($admin['type']) ?></td>
                                <td><?= htmlspecialchars($admin['created_at']) ?></td>
                                <td><?= htmlspecialchars($admin['updated_at']) ?></td>
                                <td>
                                    <!-- <a class="btn btn-warning"
                                    href="./index.php?controller=admin&action=newpass&id=<?= $admin['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn cập nhật mật khẩu cho người dùng này?');">New Password</a> -->
                                    
                                    
                                    <?php if ($admin['status'] == 'active') : ?>
                                        
                                        <a class="btn btn-danger"
                                        href="./index.php?controller=admin&action=toggleStatus&id=<?= $admin['id'] ?>&status=<?= $admin['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn vô hiệu hóa người dùng này?');">Inactivate</a>
                                    <?php else : ?>
                                        <!-- If the admin is inactive, show a button to activate it -->
                                        <a class="btn btn-success"
                                        href="./index.php?controller=admin&action=toggleStatus&id=<?= $admin['id'] ?>&status=<?= $admin['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn kích hoạt người dùng này?');">Activate</a>
                                    <?php endif; ?>
                                    <a class="btn btn-danger"
                                    href="./index.php?controller=admin&action=delete&id=<?= $admin['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa người dùng này?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center">Không có người dùng nào.</td>
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

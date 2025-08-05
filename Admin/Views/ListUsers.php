<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo (empty($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
           href="./index.php?controller=user&action=index&status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'active') ? 'active' : ''; ?>" 
           href="./index.php?controller=user&action=index&status=active">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_GET['status'] == 'inactive') ? 'active' : ''; ?>" 
           href="./index.php?controller=user&action=index&status=inactive">Inactive</a>
    </li>
</ul>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
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
                        <th>Vai trò</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Cập nhật</th>
                        <th>Thao tác</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                <td><?= htmlspecialchars($user['address']) ?></td>
                                <td><?= htmlspecialchars($user['status']) ?></td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td><?= htmlspecialchars($user['updated_at']) ?></td>
                                <td>
                                    <form method="post" action="./index.php?controller=user&action=updateRole" style="display:inline-block; min-width:130px;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <select name="role" class="form-control" onchange="this.form.submit()" style="font-size:14px; height:35px;">
                                            <option value="Default" <?= $user['role'] == 'Default' ? 'selected' : '' ?>>Default</option>
                                            <option value="Vip1" <?= $user['role'] == 'Vip1' ? 'selected' : '' ?>>Vip1</option>
                                            <option value="Vip2" <?= $user['role'] == 'Vip2' ? 'selected' : '' ?>>Vip2</option>
                                        </select>
                                    </form>


                                    <a class="btn btn-warning"
                                    href="./index.php?controller=user&action=newpass&id=<?= $user['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn cập nhật mật khẩu cho người dùng này?');">New Password</a>
                                    
                                    
                                    <?php if ($user['status'] == 'Active') : ?>
                                        
                                        <a class="btn btn-danger"
                                        href="./index.php?controller=user&action=toggleStatus&id=<?= $user['id'] ?>&status=<?= $user['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn vô hiệu hóa người dùng này?');">Inactivate</a>
                                    <?php else : ?>
                                        <!-- If the user is inactive, show a button to activate it -->
                                        <a class="btn btn-success"
                                        href="./index.php?controller=user&action=toggleStatus&id=<?= $user['id'] ?>&status=<?= $user['status'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn kích hoạt người dùng này?');">Activate</a>
                                    <?php endif; ?>
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

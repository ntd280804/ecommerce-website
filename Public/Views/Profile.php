<?php
$isHomePage = false;
require("Includes/Header.php");
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h4 text-gray-900">Thông tin tài khoản</h1>
                    </div>

                    <form class="user">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" id="id" class="form-control form-control-user" value="<?= htmlspecialchars($user['id']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" id="name" class="form-control form-control-user" value="<?= htmlspecialchars($user['name']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control form-control-user" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" class="form-control form-control-user" value="******" readonly>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" id="phone" class="form-control form-control-user" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea id="address" class="form-control form-control-user" rows="3" readonly><?= htmlspecialchars($user['address']) ?></textarea>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="./index.php?controller=user&action=editprofile" class="btn btn-primary btn-user btn-block">Chỉnh sửa thông tin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require("Includes/Footer.php");
?>

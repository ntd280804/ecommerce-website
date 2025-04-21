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
                            <h1 class="h4 text-gray-900 mb-4">Thêm người quản trị</h1>
                        </div>
                        <form class="user" method="post" action="./index.php?controller=admin&action=store" onsubmit="return validateForm()">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Nhập tên người dùng" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="mail" name="mail" placeholder="Nhập email người dùng" required>
                            </div>
                            <div class="form-group position-relative">
                                <input type="password" class="form-control form-control-user" id="pass" name="pass" placeholder="Nhập mật khẩu người dùng" required>
                                <i class="fa fa-eye position-absolute" id="togglePass" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            <div class="form-group position-relative">
                                <input type="password" class="form-control form-control-user" id="repass" name="repass" placeholder="Nhập lại mật khẩu" required>
                                <i class="fa fa-eye position-absolute" id="toggleRepass" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="phone" name="phone" placeholder="Nhập số điện thoại người dùng" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="address" name="address" placeholder="Nhập địa chỉ người dùng" required>
                            </div>
                            
                            <div class="form-group">
                            <label class="form-label">Vai trò</label>
                            <select class="form-control" name="type" id="type" required>
                                <option>Chọn vai trò</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>

                            <button class="btn btn-primary btn-user btn-block" type="submit" id="submitBtn">Tạo mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validate password match
function validateForm() {
    var password = document.getElementById("pass").value;
    var confirmPassword = document.getElementById("repass").value;

    if (password !== confirmPassword) {
        alert("Mật khẩu và mật khẩu xác nhận không khớp!");
        return false;
    }

    return true;
}

// Toggle password visibility
document.getElementById('togglePass').addEventListener('click', function() {
    var passField = document.getElementById('pass');
    passField.type = (passField.type === 'password') ? 'text' : 'password';
});

document.getElementById('toggleRepass').addEventListener('click', function() {
    var repassField = document.getElementById('repass');
    repassField.type = (repassField.type === 'password') ? 'text' : 'password';
});
</script>

<?php
require("Includes/Footer.php"); 
?>

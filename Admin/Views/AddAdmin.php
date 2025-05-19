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
                                
                            </div>
                            <div class="form-group position-relative">
                                <input type="password" class="form-control form-control-user" id="repass" name="repass" placeholder="Nhập lại mật khẩu" required>
                                
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
    var email = document.querySelector('input[name="mail"]').value;
    var phone = document.querySelector('input[name="phone"]').value;

    // Kiểm tra mật khẩu: ít nhất 1 chữ in hoa, 1 ký tự đặc biệt, dài hơn 6 ký tự
    var passwordRegex = /^(?=.*[A-Z])(?=.*[\W_]).{7,}$/;
    if (!passwordRegex.test(password)) {
        alert("Mật khẩu phải có ít nhất 1 chữ in hoa, 1 ký tự đặc biệt và dài hơn 6 ký tự!");
        return false;
    }

    // Kiểm tra xác nhận mật khẩu
    if (password !== confirmPassword) {
        alert("Mật khẩu và xác nhận mật khẩu không khớp!");
        return false;
    }

    // Kiểm tra email có dạng tên@miền.đuôi (đuôi ít nhất 2 ký tự)
    var emailRegex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;

    if (!emailRegex.test(email)) {
        alert("Email không hợp lệ!");
        return false;
    }

    // Kiểm tra số điện thoại: chỉ chứa số, 9-15 ký tự, hoặc có thể bỏ trống
    var phoneRegex = /^\d{9,15}$/;
    if (phone.length > 0 && !phoneRegex.test(phone)) {
        alert("Số điện thoại không hợp lệ! Chỉ được chứa từ 9 đến 15 chữ số.");
        return false;
    }

    return true; // Cho phép submit form
}

</script>

<?php
require("Includes/Footer.php"); 
?>

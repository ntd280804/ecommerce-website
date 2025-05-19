<?php
$isHomePage = false;
require("Includes/Header.php");

// Giả sử thông tin user được lấy từ cơ sở dữ liệu
// Ví dụ: $user = $userModel->getUserById($_SESSION['user_id']);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h4 text-gray-900">Chỉnh sửa thông tin tài khoản</h1>
                    </div>

                    <form class="user" method="POST" action="./index.php?controller=user&action=handleupdateprofile"onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" id="id" class="form-control form-control-user" 
                                   value="<?= htmlspecialchars($user['id']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" id="name" name="name" class="form-control form-control-user" 
                                   value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-user" 
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password" class="form-control form-control-user" 
                                   placeholder="Nhập mật khẩu mới (để trống nếu không thay đổi)">
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="passwordagain" name="passwordagain" class="form-control form-control-user" 
                                   placeholder="Nhập lại mật khẩu mới (để trống nếu không thay đổi)">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" class="form-control form-control-user" 
                                   value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea id="address" name="address" class="form-control form-control-user" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-user btn-block">Lưu thay đổi</button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="./index.php?controller=user&action=index" class="btn btn-secondary btn-user btn-block">Hủy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("passwordagain").value;
    var email = document.querySelector('input[name="email"]').value;
    var phone = document.querySelector('input[name="phone"]').value;

    // Kiểm tra nếu mật khẩu có ký tự thì kiểm tra regex
    if (password.length > 0 || confirmPassword.length > 0) {
        // Kiểm tra mật khẩu: ít nhất 1 chữ in hoa, 1 ký tự đặc biệt, dài hơn 6 ký tự
        var passwordRegex = /^(?=.*[A-Z])(?=.*[\W_]).{7,}$/;
        if (!passwordRegex.test(password)) {
            alert("Mật khẩu phải có ít nhất 1 chữ in hoa, 1 ký tự đặc biệt và dài hơn 6 ký tự!");
            return false;
        }
        if (password !== confirmPassword) {
            alert("Mật khẩu và xác nhận mật khẩu không khớp!");
            return false;
        }
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

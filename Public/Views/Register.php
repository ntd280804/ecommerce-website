<?php
$isHomePage = false;
require("Includes/Header.php");
?>

<div class="container">

<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Tạo tài khoản</h1>
                            </div>
                            <?php if (!empty($error_message)): ?>
                                <p style="color:red;"><?php echo $error_message; ?></p>
                            <?php endif; ?>

                            <form class="user" method="POST" action="./index.php?controller=user&action=handleRegister" onsubmit="return checkPasswordMatch()">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control form-control-user"
                                        placeholder="Tên" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                        placeholder="Địa chỉ email" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" id="password" class="form-control form-control-user"
                                            placeholder="Mật khẩu" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-user"
                                            placeholder="Nhập lại mật khẩu" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control form-control-user"
                                        placeholder="Số điện thoại">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="address" class="form-control form-control-user"
                                        placeholder="Địa chỉ">
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">Đăng ký</button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=forgotpassword">Quên mật khẩu?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=login">Bạn đã có tài khoản? Đăng nhập!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</div>

<script>
function checkPasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    
    if (password !== confirmPassword) {
        alert("Mật khẩu và xác nhận mật khẩu không khớp!");
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
</script>

<?php
require("Includes/Footer.php");
?>

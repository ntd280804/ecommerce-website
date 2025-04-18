<?php
$isHomePage = false; // Set this variable to true for the home page
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
                    <div class="col-lg-12 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Chào mừng trở lại!</h1>
                            </div>
                            <?php if (!empty($error_message)): ?>
                                <p style="color:red;"><?php echo $error_message; ?></p>
                            <?php endif; ?>

                            <form class="user" method="POST" action="./index.php?controller=user&action=handleLogin">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                        placeholder="Địa chỉ email..." required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        placeholder="Mật khẩu" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Đăng nhập</button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=forgotpassword">Quên mật khẩu?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=register">Tạo tài khoản!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</div>


    <?php
require("Includes/Footer.php"); 
?>
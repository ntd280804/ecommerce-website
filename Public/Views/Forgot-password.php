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
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Quên mật khẩu?</h1>
                                <p class="mb-4">Hãy nhập email mà bạn đã đăng kí tài khoản và chúng tôi sẽ đặt lại mật khẩu mới cho bạn</p>
                            </div>
                            

                            <form class="user" method="POST" action="./index.php?controller=user&action=handleForgot_password">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                        placeholder="Địa chỉ email..." required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        placeholder="Nhập mật khẩu" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="passwordagain" class="form-control form-control-user"
                                        placeholder="Nhập lại mật khẩu" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Đặt lại mật khẩu</button>
                            </form>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const form = document.querySelector('form.user');
                                    form.addEventListener('submit', function(e) {
                                        const pass = form.password.value.trim();
                                        const passAgain = form.passwordagain.value.trim();

                                        const passRegex = /^(?=.*[A-Z])(?=.*[\W_]).{7,}$/;

                                        if (!passRegex.test(pass)) {
                                            e.preventDefault();
                                            alert('Mật khẩu phải có ít nhất 1 chữ in hoa, 1 ký tự đặc biệt và dài hơn 6 ký tự!');
                                            form.password.focus();
                                            return false;
                                        }

                                        if (pass !== passAgain) {
                                            e.preventDefault();
                                            alert('Mật khẩu nhập lại không khớp. Vui lòng kiểm tra lại.');
                                            form.passwordagain.focus();
                                            return false;
                                        }
                                    });
                                });
                                </script>


                            <hr>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=register">Tạo tài khoản!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="./index.php?controller=user&action=login">Đã có tài khoản! Đăng nhập!</a>
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
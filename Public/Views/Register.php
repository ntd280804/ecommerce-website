<?php
$isHomePage = false; // Set this variable to true for the home page
$isLoginPage = true; // Set this variable to true for the login page
require("Includes/Header.php"); 
?>

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Tạo tài khoản</h1>
                            </div>
                            <form class="user">

                                <div class="form-group">                                
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Tên">                                         
                                </div>  
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Địa chỉ email">
                                </div>                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Mật khẩu">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Nhập lại mật khẩu">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="phone" class="form-control form-control-user" id="exampleInputPhone"
                                        placeholder="Số điện thoại">
                                </div>

                                <div class="form-group">
                                    <input type="address" class="form-control form-control-user" id="exampleInputAddress"
                                        placeholder="Địa chỉ">
                                </div>

                                <a href="login.html" class="btn btn-primary btn-user btn-block">
                                    Đăng ký
                                </a>
                                <hr>
                                
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Quên mật khẩu !</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">Bạn đã có tài khoản? Đăng nhập!</a>
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
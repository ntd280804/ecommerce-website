<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 

?>


<div class="container">

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Cập nhật mật khẩu</h1>
                    </div>
                    <form class="user" method="post" action="./index.php?controller=user&action=update"onsubmit="return validateForm()">

                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user"
                                id="password" name="password" aria-describedby="password"
                                placeholder="Nhập password mới" >
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user"
                                id="repassword" name="repassword" aria-describedby="repassword"
                                placeholder="Nhập lại password mới" >
                        </div>
                        
                        <button class="btn btn-primary" type="submit">Cập nhật</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("repassword").value;


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

    return true; // Cho phép submit form
}
</script>
</div>
<?php
require("Includes/Footer.php"); 
?>

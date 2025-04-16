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
                    <form class="user" method="post" action="./index.php?controller=user&action=update">

                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="password" name="password" aria-describedby="password"
                                placeholder="Nhập password mới" >
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
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

</div>
<?php
require("Includes/Footer.php"); 
?>

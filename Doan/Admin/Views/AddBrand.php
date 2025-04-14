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
                        <h1 class="h4 text-gray-900 mb-4">Thêm thương hiệu</h1>
                    </div>
                    <form class="user" method="post" action="./index.php?controller=brand&action=store">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="name" name="name" aria-describedby="name"
                                placeholder="Nhập tên thương hiệu">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user"
                                id="slug" name="slug" aria-describedby="name"
                                placeholder="Nhập slug thương hiệu">
                        </div>
                        
                        <button class="btn btn-primary" type="submit">Tạo mới</button>
                        
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

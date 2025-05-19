<?php
$isHomePage=false;
require("Includes/Header.php"); 
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Shopping Cart</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <span>Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lương</th>
                                <th>Tổng tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td class="shoping__cart__item">
                                        <?php echo $cartModel->getAvatarImages($item['images'], 100); ?>
                                        <h5><?php echo $item['product_name']; ?></h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        <?php echo number_format($item['discounted_price'], 2); ?>VNĐ
                                    </td>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
    // Lặp qua từng ô quantity
    document.querySelectorAll('.pro-qty').forEach(function (qtyBox) {
        const form = qtyBox.closest('form');
        const input = qtyBox.querySelector('input');

        // Thêm sự kiện click cho nút + và -
        qtyBox.querySelectorAll('.qtybtn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                // Delay 1 chút để value cập nhật xong rồi xử lý
                setTimeout(() => {
                    let qty = parseInt(input.value);
                    if (isNaN(qty) || qty < 1) {
                        // Lấy product_id từ form để redirect xóa sản phẩm
                        const productId = form.querySelector('input[name="product_id"]').value;
                        window.location.href = `./index.php?controller=cart&action=deletecart&product_id=${productId}`;
                    } else {
                        form.submit();
                    }
                }, 100);
            });
        });

        // Thêm sự kiện onchange cho input số lượng (gõ tay)
        input.addEventListener('change', function () {
            let qty = parseInt(input.value);
            if (isNaN(qty) || qty < 1) {
                const productId = form.querySelector('input[name="product_id"]').value;
                window.location.href = `./index.php?controller=cart&action=deletecart&product_id=${productId}`;
            } else {
                form.submit();
            }
        });
    });
});

                                    </script>
                                    <td class="shoping__cart__quantity">
                                        <form action="./index.php?controller=cart&action=updatecart" method="POST">
                                            <div class="quantity">
                                                <div class="pro-qty">
                                                    <!-- Chỉnh sửa input để cập nhật theo mũi tên -->
                                                    <input type="number" name="qty" value="<?php echo $item['qty']; ?>" min="1" onchange="this.form.submit();"> <!-- Thêm sự kiện onchange để tự động submit khi thay đổi -->
                                                </div>
                                            </div>
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        </form>
                                    </td>


                                    <td class="shoping__cart__total">
                                        <?php echo number_format($item['discounted_price'] * $item['qty'], 2); ?>VNĐ
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <a href="./index.php?controller=cart&action=deletecart&product_id=<?php echo $item['product_id']; ?>">
                                            <span class="icon_close"></span>
                                        </a>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="./index.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Total <span><?php echo number_format($totalAmount, 2); ?>VNĐ</span></li>
                    </ul>
                    <a href="./index.php?controller=order&action=checkout" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shoping Cart Section End -->


<?php
require("Includes/Footer.php"); 
?>
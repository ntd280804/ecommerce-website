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
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
            </div>
            <div class="checkout__form">
                <h4>Chi tiết hóa đơn</h4>
                <form action="./index.php?controller=order&action=placeorder" method="POST">
                    <div class="row">
                        <!-- Thông tin thanh toán và người nhận -->
                        <div class="col-lg-12">
                            <h4>Thông tin thanh toán và người nhận</h4>

                            <div class="checkout__input">
                                <p>Tên người nhận<span>*</span></p>
                                <input type="text" name="receiver_name" required placeholder="Nhập tên người nhận">
                            </div>

                            <div class="checkout__input">
                                <p>Số điện thoại người nhận<span>*</span></p>
                                <input type="tel" name="receiver_phone" required placeholder="Nhập số điện thoại">
                            </div>

                            <div class="checkout__input">
                                <p>Địa chỉ người nhận<span>*</span></p>
                                <input type="text" name="receiver_address" required placeholder="Nhập địa chỉ">
                            </div>

                            <div class="checkout__input">
                                <p>Phương thức thanh toán<span>*</span></p>
                                <select name="payment_method" required>
                                    <option value="Cash">Tiền mặt</option>
                                    <option value="Bank Transfer">Chuyển khoản</option>
                                </select>
                            </div>
                        </div>

                        <!-- Đơn hàng của bạn -->
                        <div class="col-lg-12 col-md-12">
                            <div class="checkout__order">
                                <h4>Đơn hàng của bạn</h4>
                                <div class="checkout__order__products">Sản phẩm <span>Tổng tiền</span></div>
                                <ul>
                                    <?php foreach ($cartItems as $item): ?>
                                    <li><?php echo htmlspecialchars($item['product_name']); ?><span><?php echo number_format($item['discounted_price'] * $item['qty'], 2); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="checkout__order__total">Tổng tiền <span><?php echo number_format($totalAmount, 2); ?></span></div>
                                <button type="submit" class="site-btn">Đặt hàng</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
    <!-- Checkout Section End -->


<?php
require("Includes/Footer.php"); 
?>
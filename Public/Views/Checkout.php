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
                <h4>Billing Details</h4>
                <form action="./index.php?controller=order&action=placeorder" method="POST">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products <span>Total</span></div>
                                <ul>
                                    <?php foreach ($cartItems as $item): ?>
                                    <li><?php echo $item['product_name']; ?><span><?php echo number_format($item['discounted_price'] * $item['qty'], 2); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="checkout__order__total">Total <span><?php echo number_format($totalAmount, 2); ?></span></div>
                                <button type="submit" class="site-btn">PLACE ORDER</button>
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
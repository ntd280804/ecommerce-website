<?php
$isHomePage = false; // Set this variable to true for the home page
require("Includes/Header.php"); 
?>
<div class="container">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h6>
    </div>
    <div class="card-body">
        <p><strong>Người dùng:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
        <p><strong>Tổng tiền:</strong> <?= htmlspecialchars($order['total']) ?> VNĐ</p>
        <p>
            <strong>Trạng thái:</strong>
            <?= htmlspecialchars($order['status']) ?>

        </p>

        <hr>
        <h6>Danh sách sản phẩm:</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderDetails)) : ?>
                    <?php
                    
                        ?>
                    <?php foreach ($orderDetails as $item) : ?>
                        <?php
                        $productImagePath = $productModel->getImagesById($item['product_id']);
                        $productAvatar = $productModel->getAvatarImages($productImagePath, 100)
                        ?>
                        <tr>
                            <td>
                                <?= $productAvatar ?>
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['qty']) ?></td>
                            <td><?= htmlspecialchars($item['price']) ?></td>
                            <td><?= htmlspecialchars($item['qty'] * $item['price']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require("Includes/Footer.php"); ?>

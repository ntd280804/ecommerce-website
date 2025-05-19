<?php require("Includes/Header.php"); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h6>
    </div>
    <div class="card-body">
        <p><strong>Người dùng:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
        <p><strong>Tổng tiền:</strong> <?= htmlspecialchars($order['total']) ?> VNĐ</p>
        <p>
            <strong>Trạng thái:</strong>
            <?= htmlspecialchars($order['status']) ?>

            <form method="post" action="./index.php?controller=order&action=toggleStatus" style="display: inline-block; margin-left: 10px;">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                <select name="status" onchange="this.form.submit()" class="form-control d-inline-block" style="width: auto; display: inline-block;">
                    <?php
                    $statuses = ['Processing', 'Confirmed', 'Shipping', 'Delivered', 'Cancelled'];
                    foreach ($statuses as $statusOption) :
                    ?>
                        <option value="<?= $statusOption ?>" <?= $order['status'] === $statusOption ? 'selected' : '' ?>>
                            <?= $statusOption ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

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
        <hr>
        <h6>Đánh giá sản phẩm:</h6>

        <?php if (!empty($reviews)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Ngày đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review) : ?>
                        <tr>
                            <td><?= htmlspecialchars($review['product_name']) ?></td>
                            <td><?= str_repeat('⭐', (int)$review['rating']) ?></td>
                            <td><?= htmlspecialchars($review['comment']) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($review['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Chưa có đánh giá nào cho đơn hàng này.</p>
        <?php endif; ?>

    </div>
</div>

<?php require("Includes/Footer.php"); ?>

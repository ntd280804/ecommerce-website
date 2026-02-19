<?php require("Includes/Header.php"); ?>

<?php
// Helper function to get status badge class
function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'badge-secondary',
        'processing' => 'badge-info',
        'shipping' => 'badge-warning',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
        'refunded' => 'badge-dark'
    ];
    
    $status = strtolower($status);
    return $classes[$status] ?? 'badge-secondary';
}

// Helper function to translate status
function translateStatus($status) {
    $translations = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'refunded' => 'Hoàn tiền'
    ];
    
    $status = strtolower($status);
    return $translations[$status] ?? $status;
}
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h6>
    </div>
    <div class="card-body">
    <p><strong>Người dùng:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
        <p><strong>Vai trò:</strong> <?= htmlspecialchars($order['user_role']) ?></p>
        <p><strong>Tên người nhận:</strong> <?= htmlspecialchars($order['receiver_name']) ?></p>
        <p><strong>Số điện thoại người nhận:</strong> <?= htmlspecialchars($order['receiver_phone']) ?></p>
        <p><strong>Địa chỉ người nhận:</strong> <?= htmlspecialchars($order['receiver_address']) ?></p>
        <hr>
        <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Tổng tiền:</strong> <?= number_format($order['grand_total'], 0, ',', '.') ?> VNĐ</p>
        <p><strong>Trạng thái thanh toán:</strong> 
            <span class="badge <?= $order['payment_status'] === 'paid' ? 'badge-success' : 'badge-warning' ?>">
                <?= $order['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' ?>
            </span>
        </p>
        <hr>
        <p>
            <strong>Trạng thái:</strong>
            <span class="badge <?= getStatusBadgeClass($order['status']) ?>">
                <?= translateStatus($order['status']) ?>
            </span>

            <form method="post" action="./index.php?controller=order&action=toggleStatus" style="display: inline-block; margin-left: 10px;">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                <select name="status" onchange="this.form.submit()" class="form-control d-inline-block" style="width: auto; display: inline-block;">
                    <?php
                    $statuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled', 'refunded'];
                    $statusLabels = [
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý', 
                        'shipping' => 'Đang giao hàng',
                        'completed' => 'Đã giao hàng',
                        'cancelled' => 'Đã hủy',
                        'refunded' => 'Hoàn tiền'
                    ];
                    foreach ($statuses as $statusOption) :
                    ?>
                        <option value="<?= $statusOption ?>" <?= strtolower($order['status']) === $statusOption ? 'selected' : '' ?>>
                            <?= $statusLabels[$statusOption] ?>
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
                    <?php foreach ($orderDetails as $item) : ?>
                        <?php
                            $productImagePath = $productModel->getImagesById($item['product_id']);
                            $productAvatar = $productModel->getAvatarImages($productImagePath, 100);
                            

                            $total = $item['price'] * $item['qty'];
                        ?>
                        <tr>
                            <td><?= $productAvatar ?></td>
                            <td>
                                <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                                <?php if (!empty($item['variant_name'])): ?>
                                    <small class="text-muted">Variant: <?= htmlspecialchars($item['variant_name']) ?></small><br>
                                <?php endif; ?>
                                <small class="text-muted">SKU: <?= htmlspecialchars($item['sku']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($item['qty']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                            <td><?= number_format($total, 0, ',', '.') ?> VNĐ</td>
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

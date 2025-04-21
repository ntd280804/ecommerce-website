<?php
$isHomePage = false; // Đặt biến này để có thể sử dụng trong Header nếu cần
require("Includes/Header.php");
?>

<div class="container">


    <?php if (!empty($orders)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= number_format($order['total'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <a href="index.php?controller=order&action=detail&id=<?= htmlspecialchars($order['id']) ?>" class="btn btn-info">Chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>

<?php
require("Includes/Footer.php");
?>

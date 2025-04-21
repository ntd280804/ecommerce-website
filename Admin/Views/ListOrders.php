<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 
?>
<!-- Tabs to filter by status -->
<ul class="nav nav-tabs mb-3" id="orderStatusTabs" role="tablist">
    <?php
    $statuses = [
        'all' => 'Tất cả',
        'Processing' => 'Đang xử lý',
        'Confirmed' => 'Đã xác nhận',
        'Shipping' => 'Đang giao',
        'Delivered' => 'Đã giao',
        'Cancelled' => 'Đã hủy'
    ];
    $currentStatus = $_GET['status'] ?? 'all';
    ?>

    <?php foreach ($statuses as $key => $label) : ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($currentStatus === $key) ? 'active' : '' ?>"
               href="./index.php?controller=order&action=index&status=<?= urlencode($key) ?>">
               <?= $label ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Operations</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars($order['user_id']) ?></td>
                                <td><?= htmlspecialchars($order['total']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td>
                                    <a class="btn btn-warning"
                                    href="./index.php?controller=order&action=detail&id=<?= $order['id'] ?>"
                                    >Detail</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require("Includes/Footer.php"); 
?>

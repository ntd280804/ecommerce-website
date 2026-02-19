<?php
require("Includes/Header.php"); 
require_once("../Config/Database.php"); 

// Helper function to format currency
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

// Helper function to get status badge class
function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'badge-secondary',
        'processing' => 'badge-info',
        'confirmed' => 'badge-primary',
        'shipping' => 'badge-warning',
        'delivered' => 'badge-success',
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
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'refunded' => 'Hoàn tiền'
    ];
    
    $status = strtolower($status);
    return $translations[$status] ?? $status;
}
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Tổng đơn hàng: </span>
            <span class="font-weight-bold"><?= count($orders) ?></span>
        </div>
    </div>

    <!-- Status Tabs -->
    <ul class="nav nav-tabs mb-4" id="orderStatusTabs" role="tablist">
        <?php
        $statuses = [
            'all' => 'Tất cả',
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Hoàn tiền'
        ];
        $currentStatus = $_GET['status'] ?? 'all';
        ?>

        <?php foreach ($statuses as $key => $label) : ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= ($currentStatus === $key) ? 'active' : '' ?>"
                   href="./index.php?controller=order&action=index&status=<?= urlencode($key) ?>">
                   <?= $label ?>
                   <?php if ($key !== 'all') : ?>
                       <span class="badge badge-light ml-1">
                           <?= array_reduce($orders, function($carry, $item) use ($key) {
                               return $carry + (strtolower($item['status']) === $key ? 1 : 0);
                           }, 0) ?>
                       </span>
                   <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Order List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
            <div class="d-flex">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm đơn hàng...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>MÃ ĐƠN HÀNG</th>
                            <th>NGÀY ĐẶT</th>
                            <th>KHÁCH HÀNG</th>
                            <th>SẢN PHẨM</th>
                            <th>TỔNG TIỀN</th>
                            <th>TRẠNG THÁI</th>
                            <th>TRẠNG THÁI THANH TOÁN</th>
                            <th>THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)) : ?>
                            <?php foreach ($orders as $order) : 
                                $orderDate = new DateTime($order['created_at']);
                                $now = new DateTime();
                                $interval = $now->diff($orderDate);
                                $isNew = $interval->days === 0 && $interval->h < 24;
                            ?>
                                <tr class="<?= $isNew ? 'table-info' : '' ?>">
                                    <td>
                                        <div class="font-weight-bold">#<?= htmlspecialchars($order['order_number']) ?></div>
                                        <?php if ($isNew) : ?>
                                            <span class="badge badge-info">Mới</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= $orderDate->format('d/m/Y') ?></div>
                                        <small class="text-muted"><?= $orderDate->format('H:i') ?></small>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold"><?= htmlspecialchars($order['receiver_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($order['receiver_phone']) ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <i class="fas fa-box-open"></i>
                                                <?= $order['item_count'] ?> sản phẩm
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold text-primary">
                                        <?= formatCurrency($order['grand_total']) ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= getStatusBadgeClass($order['status']) ?> p-2">
                                            <?= translateStatus($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $order['payment_status'] === 'paid' ? 'badge-success' : 'badge-warning' ?> p-2">
                                            <?= $order['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="./index.php?controller=order&action=detail&id=<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-primary" 
                                           data-toggle="tooltip" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (in_array(strtolower($order['status']), ['pending', 'processing'])) : ?>
                                            <button class="btn btn-sm btn-success update-status" 
                                                    data-order-id="<?= $order['id'] ?>"
                                                    data-toggle="tooltip" 
                                                    title="Cập nhật trạng thái">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p class="mb-0">Không có đơn hàng nào</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateModalLabel">Cập nhật trạng thái đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="statusUpdateForm" method="POST" action="./index.php?controller=order&action=toggleStatus">
                <input type="hidden" name="order_id" id="orderIdInput">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="statusSelect">Trạng thái mới</label>
                        <select class="form-control" id="statusSelect" name="status" required>
                            <option value="processing">Đang xử lý</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="shipping">Đang giao hàng</option>
                            <option value="delivered">Đã giao hàng</option>
                            <option value="cancelled">Hủy đơn hàng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require("Includes/Footer.php"); ?>

<style>
    .badge {
        font-size: 0.8em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .status-badge {
        min-width: 100px;
        text-align: center;
    }
    .order-actions .btn {
        margin: 0 2px;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Status update modal
        $('.update-status').click(function() {
            var orderId = $(this).data('order-id');
            $('#orderIdInput').val(orderId);
            $('#statusUpdateModal').modal('show');
        });
        
        // Search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#dataTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        
        // DataTable initialization
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        
        $('#dataTable').DataTable({
            "order": [[1, "desc"]], // Sort by date by default
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Disable sorting on action column
            ]
        });
    });
</script>

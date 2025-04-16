<?php require("Includes/Header.php"); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Comment</th>
                        <th>Rating</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reviews)) : ?>
                        <?php foreach ($reviews as $review) : ?>
                            <tr>
                                <td><?= htmlspecialchars($review['id']) ?></td>
                                <td><?= htmlspecialchars($review['user_name']) ?></td>
                                <td><?= htmlspecialchars($review['product_name']) ?></td>
                                <td style="text-align: left;">
                                    <textarea readonly style="
                                        width: 100%;
                                        height: 80px;
                                        resize: none;
                                        border: 1px solid #ccc;
                                        background: transparent;
                                        box-sizing: border-box;
                                        text-align: left;
                                        overflow-y: auto;
                                    "><?= htmlspecialchars($review['comment']) ?></textarea>
                                </td>
                                <td><?= htmlspecialchars($review['rating']) ?></td>
                                <td><?= htmlspecialchars($review['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require("Includes/Footer.php"); ?>

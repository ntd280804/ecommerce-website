<!-- In App/Views/products.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>
<?php include('E:/Xampp/htdocs/Doan/Core/Components/Header.php'); ?>
    <h1>Danh sách sản phẩm</h1>
    
    <?php if (!empty($products) && is_array($products)): ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <?= htmlspecialchars($product['name']) ?> - <?= number_format($product['price'], 0, ',', '.') ?> VND
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Không có sản phẩm nào.</p>
    <?php endif; ?>
    <?php include('E:/Xampp/htdocs/Doan/Core/Components/Footer.php'); ?>
</body>
</html>

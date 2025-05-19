<?php
$isHomePage = false; // Set this variable to true for the home page
require("Includes/Header.php"); 
?>
<div class="container">
    <h2>Đánh giá đơn hàng #<?= htmlspecialchars($order['id']) ?></h2>
    <form action="index.php?controller=review&action=create&order_id=<?= htmlspecialchars($order['id']) ?>" method="POST">
    <div class="form-group">
    <label for="rating">Đánh giá (1-5 sao):</label>
    <div id="star-rating" style="font-size: 1.5rem; cursor: pointer;">
        <input type="hidden" name="rating" id="rating" value="5" required>
        <span data-value="1" class="star">&#9734;</span>
        <span data-value="2" class="star">&#9734;</span>
        <span data-value="3" class="star">&#9734;</span>
        <span data-value="4" class="star">&#9734;</span>
        <span data-value="5" class="star">&#9734;</span>
    </div>
</div>

<script>
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating');
    
    function setStars(rating) {
        stars.forEach((star, index) => {
            if(index < rating) {
                star.textContent = '★'; // sao đầy
                star.style.color = '#f39c12';
            } else {
                star.textContent = '☆'; // sao trống
                star.style.color = '#ccc';
            }
        });
    }
    
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = parseInt(star.getAttribute('data-value'));
            ratingInput.value = val;
            setStars(val);
        });
    });
    
    // Khởi tạo mặc định 5 sao trống (rating 0 hoặc 5 tùy bạn)
    setStars(parseInt(ratingInput.value));
</script>

        <div class="form-group">
            <label for="content">Nội dung đánh giá:</label>
            <textarea name="content" class="form-control" rows="5" placeholder="Nhập nhận xét của bạn..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
    </form>
</div>
<?php
require("Includes/Footer.php"); 
?>
<?php
class CartModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Assuming PDO connection
    }

    public function getCartItems($userId) {
        // Correctly join the cart table with products and fetch product details
        $sql = "SELECT c.id AS cart_id,c.product_id, c.qty, p.name AS product_name, p.price, p.discounted_price, p.images, p.slug AS product_slug
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id AND p.status = 'Active'";  // Ensuring only active products are shown

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAvatarImages($arrstr,$height)
    {
        $arr = explode(';', $arrstr);
        return "<img src='$arr[0]' height='$height' alt='Product Image' />";
    }
    public function removeFromCart($userId, $productId)
    {
        // Xóa sản phẩm khỏi giỏ hàng của người dùng
        $sql = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
    }
    public function clearCart($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
    
    public function addToCart($userId, $productId, $quantity)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $sqlCheck = "SELECT id, qty FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Nếu đã có thì cập nhật số lượng
            $newQty = $existing['qty'] + $quantity;
            $sqlUpdate = "UPDATE cart SET qty = :qty WHERE id = :id";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'qty' => $newQty,
                'id' => $existing['id']
            ]);
        } else {
            // Nếu chưa có thì thêm mới
            $sqlInsert = "INSERT INTO cart (user_id, product_id, qty) VALUES (:user_id, :product_id, :qty)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->execute([
                'user_id' => $userId,
                'product_id' => $productId,
                'qty' => $quantity
            ]);
        }
    }

    public function getCartTotalQuantity($userId) {
        $sql = "SELECT SUM(c.qty) AS total_quantity
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id AND p.status = 'Active'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_quantity'] ?? 0; // Trả về 0 nếu không có gì trong giỏ
    }
    public function updateCartItemQuantity($userId, $productId, $quantity) {
        // Truy vấn SQL để cập nhật số lượng sản phẩm trong giỏ hàng
        $sql = "UPDATE cart 
                SET qty = :qty
                WHERE user_id = :user_id AND product_id = :product_id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'qty' => $quantity,
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }
    
    public function getCartTotal($userId) {
        // Calculate the total price considering the product's price and quantity
        $sql = "SELECT SUM(
                        CASE
                            WHEN p.discounted_price IS NOT NULL THEN p.discounted_price * c.qty
                            ELSE p.price * c.qty
                        END
                    ) AS total
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id AND p.status = 'Active'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Return total or 0 if no result
        return isset($result['total']) ? (float)$result['total'] : 0; // Ensure total is 0 if no result
    }
    
}
?>

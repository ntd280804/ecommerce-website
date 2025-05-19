<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM orders";
        } else {
            $query = "SELECT * FROM orders WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("
            SELECT orders.*, users.name AS user_name
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getOrderDetail($id) {
        $stmt = $this->conn->prepare("SELECT od.*, p.name as product_name 
                                      FROM order_details od 
                                      JOIN products p ON od.product_id = p.id 
                                      WHERE od.order_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatus($orderId, $status) {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();
        
            // Cập nhật trạng thái đơn hàng
            $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
        
            // Nếu trạng thái là 'Delivered', giảm số lượng tồn kho
            if ($status === 'Delivered') {
                // Lấy danh sách sản phẩm trong đơn hàng
                $stmt = $this->conn->prepare("
                    SELECT product_id, qty 
                    FROM order_details 
                    WHERE order_id = :orderId
                ");
                $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                // Giảm tồn kho của từng sản phẩm
                foreach ($products as $product) {
                    // Kiểm tra số lượng tồn kho
                    $checkStock = $this->conn->prepare("
                        SELECT stock 
                        FROM products 
                        WHERE id = :productId
                    ");
                    $checkStock->bindParam(':productId', $product['product_id'], PDO::PARAM_INT);
                    $checkStock->execute();
                    $stock = $checkStock->fetchColumn();
    
                    if ($stock === false) {
                        throw new Exception("Product ID không tồn tại: " . $product['product_id']);
                    }
    
                    if ($stock < $product['qty']) {
                        throw new Exception("Không đủ tồn kho cho sản phẩm ID: " . $product['product_id']);
                    }
    
                    // Giảm tồn kho
                    $stmt = $this->conn->prepare("
                        UPDATE products 
                        SET stock = stock - :quantity 
                        WHERE id = :productId
                    ");
                    $stmt->bindParam(':quantity', $product['qty'], PDO::PARAM_INT);
                    $stmt->bindParam(':productId', $product['product_id'], PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        
            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->conn->rollBack();
            // Log lỗi chi tiết
            error_log("Lỗi khi cập nhật trạng thái đơn hàng: " . $e->getMessage());
            return false;
        }
    }
    
    
}

<?php
require_once(__DIR__ . '/../../Config/Database.php'); 

class OrderModel {
    private $conn;

    public function __construct($db = null) {
        $this->conn = $db ?: Database::connect();
    }
    // Hàm tạo đơn hàng sử dụng stored procedure với payment method
    public function createOrder($userId, $totalAmount, $cartItems, $receiver_name, $receiver_phone, $receiver_address, $payment_method = 'cod') {
        try {
            error_log('Starting createOrder with params: ' . print_r(func_get_args(), true));
            
            // Get cart ID for the user
            $stmt = $this->conn->prepare("
                SELECT id FROM carts 
                WHERE user_id = :user_id AND status = 'active'
                ORDER BY created_at DESC LIMIT 1
            ");
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cart) {
                throw new Exception("No active cart found for user");
            }

            $cartId = $cart['id'];
            error_log("Found cart ID: $cartId for user ID: $userId");

            // First, let's check if the stored procedure exists
            $checkSp = $this->conn->query("SHOW PROCEDURE STATUS WHERE Db = DATABASE() AND Name = 'sp_create_order'");
            if ($checkSp->rowCount() == 0) {
                throw new Exception("Stored procedure sp_create_order does not exist");
            }

            // Log before calling stored procedure
            error_log("Calling sp_create_order with: user_id=$userId, cart_id=$cartId, payment_method=$payment_method");
            
            // Call the stored procedure with error handling
            try {
                $stmt = $this->conn->prepare("CALL sp_create_order(:user_id, :cart_id, :payment_method, :receiver_name, :receiver_phone, :receiver_address, @order_id)");

                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
                $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
                $stmt->bindParam(':receiver_name', $receiver_name, PDO::PARAM_STR);
                $stmt->bindParam(':receiver_phone', $receiver_phone, PDO::PARAM_STR);
                $stmt->bindParam(':receiver_address', $receiver_address, PDO::PARAM_STR);
                
                if (!$stmt->execute()) {
                    $errorInfo = $stmt->errorInfo();
                    throw new Exception("Stored procedure execution failed: " . ($errorInfo[2] ?? 'Unknown error'));
                }
                
                // Close the cursor to free up the connection for next query
                $stmt->closeCursor();
                
                // Try to get the order ID using the OUT parameter first
                $stmt = $this->conn->query("SELECT @order_id AS order_id");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // If no result from OUT parameter, try LAST_INSERT_ID()
                if (empty($result['order_id'])) {
                    $stmt->closeCursor();
                    $stmt = $this->conn->query("SELECT LAST_INSERT_ID() AS order_id");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                }

                if (empty($result['order_id'])) {
                    throw new Exception("Failed to retrieve order ID after creation");
                }
                
                $orderId = $result['order_id'];
                error_log("Successfully created order with ID: $orderId");
                
                return $orderId;
                
            } catch (PDOException $pdoEx) {
                error_log("PDO Exception in createOrder: " . $pdoEx->getMessage());
                throw new Exception("Database error while creating order: " . $pdoEx->getMessage());
            }

        } catch (Exception $e) {
            error_log("Order creation failed: " . $e->getMessage());
            throw $e;
        }
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
    public function getProductIdsByOrderId($orderId) {
        $stmt = $this->conn->prepare("
            SELECT product_id 
            FROM order_details 
            WHERE order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("
            SELECT oi.*, p.name as product_name, pv.name as variant_name, pv.product_id
            FROM order_items oi
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            LEFT JOIN products p ON pv.product_id = p.id
            WHERE oi.order_id = :orderId
        ");
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrdersByUserId($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                orders.*, 
                users.name AS user_name
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.user_id = :userId
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Inventory management methods
    public function updateInventoryForOrderCreation($orderId) {
        try {
            $this->conn->beginTransaction();
            
            // Get order items with variant info
            $stmt = $this->conn->prepare("
                SELECT oi.variant_id, oi.qty
                FROM order_items oi
                WHERE oi.order_id = :orderId
            ");
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($items as $item) {
                // Update inventory: -1 stock_quantity, +1 reserved_quantity
                $stmt = $this->conn->prepare("
                    UPDATE product_variants 
                    SET stock_quantity = stock_quantity - :qty,
                        reserved_quantity = reserved_quantity + :qty,
                        version = version + 1
                    WHERE id = :variant_id 
                    AND stock_quantity >= :qty
                ");
                $stmt->bindParam(':qty', $item['qty'], PDO::PARAM_INT);
                $stmt->bindParam(':variant_id', $item['variant_id'], PDO::PARAM_INT);
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update inventory for variant {$item['variant_id']}");
                }
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Insufficient stock for variant {$item['variant_id']}");
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Inventory update failed for order creation: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function updateInventoryForOrderStatusChange($orderId, $newStatus) {
        try {
            $this->conn->beginTransaction();
            
            // Get current order status and items
            $stmt = $this->conn->prepare("
                SELECT o.status, oi.variant_id, oi.qty
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                WHERE o.id = :orderId
            ");
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($result)) {
                throw new Exception("Order not found or has no items");
            }
            
            $currentStatus = $result[0]['status'];
            
            foreach ($result as $item) {
                $variantId = $item['variant_id'];
                $qty = $item['qty'];
                
                if ($currentStatus !== 'delivered' && $newStatus === 'delivered') {
                    // Order delivered: -1 reserved_quantity
                    $stmt = $this->conn->prepare("
                        UPDATE product_variants 
                        SET reserved_quantity = reserved_quantity - :qty,
                            version = version + 1
                        WHERE id = :variant_id 
                        AND reserved_quantity >= :qty
                    ");
                    $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
                    $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                    
                    if (!$stmt->execute() || $stmt->rowCount() === 0) {
                        throw new Exception("Failed to release reserved stock for variant {$variantId}");
                    }
                    
                } elseif ($currentStatus !== 'cancelled' && $newStatus === 'cancelled') {
                    // Order cancelled: +1 stock_quantity, -1 reserved_quantity
                    $stmt = $this->conn->prepare("
                        UPDATE product_variants 
                        SET stock_quantity = stock_quantity + :qty,
                            reserved_quantity = reserved_quantity - :qty,
                            version = version + 1
                        WHERE id = :variant_id 
                        AND reserved_quantity >= :qty
                    ");
                    $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
                    $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                    
                    if (!$stmt->execute() || $stmt->rowCount() === 0) {
                        throw new Exception("Failed to restore stock for variant {$variantId}");
                    }
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Inventory update failed for status change: " . $e->getMessage());
            throw $e;
        }
    }
    
}


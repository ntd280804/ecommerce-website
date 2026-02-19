<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getByStatus($status) {
        $query = "
            SELECT 
                o.*, 
                u.name AS user_name,
                u.email AS user_email,
                COUNT(oi.id) AS item_count,
                SUM(oi.price * oi.qty) AS total_amount
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
        ";
        
        if ($status !== 'all') {
            $query .= " WHERE o.status = :status";
        }
        
        $query .= " GROUP BY o.id ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("
            SELECT 
                o.*, 
                u.name AS user_name,
                u.email AS user_email,
                u.phone AS user_phone,
                u.address AS user_address,
                u.role AS user_role
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getOrderDetail($id) {
        $stmt = $this->conn->prepare("
            SELECT 
                oi.*, 
                p.name as product_name,
                pv.name as variant_name,
                pv.product_id,
                (oi.price * oi.qty) as subtotal
            FROM order_items oi
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            LEFT JOIN products p ON pv.product_id = p.id
            WHERE oi.order_id = :id
            ORDER BY oi.id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllOrders() {
        $stmt = $this->conn->prepare("
            SELECT 
                o.*,
                u.name as user_name,
                u.role as user_role
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($orderId, $status) {
        try {
            // Debug logging
            error_log("DEBUG: Updating order ID $orderId to status: $status");
            
            // Start transaction
            $this->conn->beginTransaction();
            
            // Only update inventory for specific status changes
            if (in_array($status, ['completed', 'cancelled'])) {
                error_log("DEBUG: Updating inventory for status: $status");
                // Update inventory based on status change
                $this->updateInventoryForOrderStatusChange($orderId, $status);
            } else {
                error_log("DEBUG: Skipping inventory update for status: $status");
            }
            
            // Update order status
            $stmt = $this->conn->prepare("
                UPDATE orders 
                SET status = :status 
                WHERE id = :id");
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                error_log("DEBUG: Successfully updated order status");
            } else {
                error_log("DEBUG: Failed to update order status");
                $errorInfo = $stmt->errorInfo();
                error_log("DEBUG: SQL Error: " . implode(', ', $errorInfo));
            }
        
            // Commit transaction
            $this->conn->commit();
            error_log("DEBUG: Transaction committed successfully");
            return true;
        } catch (Exception $e) {
            // Rollback on error only if transaction is active
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
                error_log("DEBUG: Transaction rolled back");
            }
            // Log detailed error
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateInventoryForOrderStatusChange($orderId, $newStatus) {
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
            
            if ($currentStatus !== 'completed' && $newStatus === 'completed') {
                // Order completed: -1 reserved_quantity
                $stmt = $this->conn->prepare("
                    UPDATE product_variants 
                    SET reserved_quantity = reserved_quantity - :qty
                    WHERE id = :variant_id 
                    AND reserved_quantity >= :qty
                ");
                $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
                $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                
                if (!$stmt->execute() || $stmt->rowCount() === 0) {
                    $errorDetails = "Variant {$variantId}, qty: {$qty}";
                    throw new Exception("Failed to release reserved stock for {$errorDetails} - not enough reserved stock");
                }
                
            } elseif ($currentStatus !== 'cancelled' && $newStatus === 'cancelled') {
                // Order cancelled: +1 stock_quantity, -1 reserved_quantity
                $stmt = $this->conn->prepare("
                    UPDATE product_variants 
                    SET stock_quantity = stock_quantity + :qty,
                        reserved_quantity = reserved_quantity - :qty
                    WHERE id = :variant_id 
                    AND reserved_quantity >= :qty
                ");
                $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
                $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                
                if (!$stmt->execute() || $stmt->rowCount() === 0) {
                    $errorDetails = "Variant {$variantId}, qty: {$qty}";
                    throw new Exception("Failed to restore stock for {$errorDetails} - not enough reserved stock");
                }
            }
        }
        
        return true;
    }
    
    
}

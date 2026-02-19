<?php
require_once(__DIR__ . '/../../Config/Database.php'); // Database configuration

class CartModel {
    private $conn;

    public function __construct($db = null) {
        $this->conn = $db ?: Database::connect();
    }

    public function getCartItems($userId, $userRole = null) {
        $sql = "SELECT 
                    ci.id AS cart_item_id,
                    p.id AS product_id,
                    p.name AS product_name,
                    p.images,
                    pv.id AS variant_id,
                    pv.name AS variant_name,
                    pv.price,
                    pv.stock_quantity,
                    pv.reserved_quantity,
                    pv.stock_quantity AS available_stock,
                    ci.qty,
                    (pv.price * ci.qty) AS item_total
                FROM carts c
                JOIN cart_items ci ON c.id = ci.cart_id
                JOIN product_variants pv ON ci.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = :user_id 
                AND c.status = 'active'
                AND p.status = 'active'";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process images and calculate prices
        foreach ($items as &$item) {
            if (isset($item['images'])) {
                $item['images'] = $this->getAvatarImages($item['images'], 100);
            }
            $item['price'] = (float)$item['price'];
            $item['item_total'] = (float)$item['item_total'];
            $item['stock_quantity'] = (int)$item['stock_quantity'];
            $item['reserved_quantity'] = (int)$item['reserved_quantity'];
            $item['available_stock'] = (int)$item['available_stock'];
            $item['qty'] = (int)$item['qty'];
        }

        return $items;
    }

    public function getAvatarImages($arrstr,$height)
    {
        $arr = explode(';', $arrstr);
        return "<img src='$arr[0]' height='$height' alt='Product Image' />";
    }

    public function addToCart($userId, $productId, $quantity, $variantId = null) {
        if (!$variantId) {
            return [
                'success' => false, 
                'message' => 'Variant ID is required',
                'error_details' => 'No variant ID provided'
            ];
        }

        // Start transaction
        $this->conn->beginTransaction();

        try {
            // Get or create cart
            $cartId = $this->getOrCreateCart($userId);
            
            // Check if variant exists and has enough stock
            $sql = "SELECT id, stock_quantity, reserved_quantity, version, name 
                    FROM product_variants 
                    WHERE id = :variant_id AND product_id = :product_id
                    FOR UPDATE";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'variant_id' => $variantId,
                'product_id' => $productId
            ]);
            
            $variant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$variant) {
                throw new Exception("Product variant not found. Variant ID: $variantId, Product ID: $productId");
            }
            
            $availableStock = $variant['stock_quantity'];
            
            if ($availableStock < $quantity) {
                return [
                    'success' => false, 
                    'message' => 'Not enough stock available',
                    'available_stock' => $availableStock,
                    'error_details' => "Insufficient stock. Available: $availableStock, Requested: $quantity"
                ];
            }
            
            // Check if item already in cart
            $sql = "SELECT id, qty FROM cart_items 
                    WHERE cart_id = :cart_id AND variant_id = :variant_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'cart_id' => $cartId,
                'variant_id' => $variantId
            ]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingItem) {
                // Update existing cart item
                $newQty = $existingItem['qty'] + $quantity;
                
                if ($newQty > $availableStock) {
                    return [
                        'success' => false, 
                        'message' => 'Not enough stock available',
                        'available_stock' => $availableStock,
                        'error_details' => "Cannot update quantity. New quantity ($newQty) exceeds available stock ($availableStock)"
                    ];
                }
                
                $sql = "UPDATE cart_items SET qty = :qty WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    'qty' => $newQty,
                    'id' => $existingItem['id']
                ]);
                
                if (!$result) {
                    throw new Exception("Failed to update cart item quantity");
                }
                
                // Removed reserved_quantity update as per requirement
            } else {
                // Add new item to cart
                $sql = "INSERT INTO cart_items (cart_id, variant_id, qty) 
                        VALUES (:cart_id, :variant_id, :qty)";
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    'cart_id' => $cartId,
                    'variant_id' => $variantId,
                    'qty' => $quantity
                ]);
                
                if (!$result) {
                    $errorInfo = $stmt->errorInfo();
                    throw new Exception("Failed to add item to cart: " . ($errorInfo[2] ?? 'Unknown error'));
                }
                
                // Removed reserved_quantity update as per requirement
            }
            
            $this->conn->commit();
            return [
                'success' => true, 
                'message' => 'Product added to cart',
                'cart_item_id' => $existingItem['id'] ?? $this->conn->lastInsertId()
            ];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $errorMessage = 'Error adding to cart: ' . $e->getMessage();
            error_log($errorMessage);
            
            // Log the full backtrace for debugging
            error_log("Stack trace: " . $e->getTraceAsString());
            
            return [
                'success' => false, 
                'message' => 'Failed to add product to cart',
                'error_details' => $errorMessage,
                'debug' => [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity
                ]
            ];
        }
    }
    

    public function removeFromCart($userId, $productId, $variantId = null) {
        if (!$variantId) {
            throw new Exception("Variant ID is required");
        }
        
        // Start transaction for atomic operation
        $this->conn->beginTransaction();
        
        try {
            // Get cart and cart item details before removing with locks
            $sql = "SELECT ci.id, ci.qty, pv.stock_quantity, pv.reserved_quantity, pv.version
                    FROM carts c
                    JOIN cart_items ci ON c.id = ci.cart_id
                    JOIN product_variants pv ON ci.variant_id = pv.id
                    WHERE c.user_id = :user_id AND c.status = 'active' AND ci.variant_id = :variant_id AND pv.product_id = :product_id
                    FOR UPDATE";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'variant_id' => $variantId,
                'product_id' => $productId
            ]);
            
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($cartItem) {
                // Remove item from cart_items
                $sqlDelete = "DELETE FROM cart_items WHERE id = :id";
                $stmtDelete = $this->conn->prepare($sqlDelete);
                $stmtDelete->execute(['id' => $cartItem['id']]);
                
                // Release reserved inventory
                $sqlRelease = "UPDATE product_variants
               SET reserved_quantity = GREATEST(reserved_quantity - :qty, 0),
                   version = version + 1
               WHERE id = :variant_id";
                $stmtRelease = $this->conn->prepare($sqlRelease);
                $stmtRelease->execute([
                    'qty' => $cartItem['qty'],
                    'variant_id' => $variantId
                ]);
                
                // Log inventory change
                $this->logInventoryChange($variantId, 'release', -$cartItem['qty'], 
                    $cartItem['stock_quantity'], $cartItem['reserved_quantity'] - $cartItem['qty'], 
                    $cartItem['id'], 'Item removed from cart');
                
                // Check if cart is empty and delete it if needed
                $this->cleanupEmptyCart($userId);
            }
            
            $this->conn->commit();
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e; // Re-throw to be handled by controller
        }
    }

    private function getOrCreateCart($userId) {
        // Check if user has an active cart
        $sql = "SELECT id FROM carts WHERE user_id = :user_id AND status = 'active' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cart) {
            return $cart['id'];
        }
        
        // Create new cart if none exists
        $sql = "INSERT INTO carts (user_id, status) VALUES (:user_id, 'active')";
        $stmt = $this->conn->prepare($sql);
        
        try {
            $stmt->execute(['user_id' => $userId]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating cart: " . $e->getMessage());
            throw new Exception("Could not create cart: " . $e->getMessage());
        }
    }
    
    private function cleanupEmptyCart($userId) {
        try {
            $sql = "DELETE c FROM carts c
                    LEFT JOIN cart_items ci ON c.id = ci.cart_id
                    WHERE c.user_id = :user_id
                    AND ci.id IS NULL";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            
            return $stmt->rowCount() > 0; // Trả về true nếu có bản ghi bị xóa
        } catch (PDOException $e) {
            error_log("Error cleaning up empty cart: " . $e->getMessage());
            return false;
        }
    }

    public function clearCart($userId, $externalConn = null) {
        $useExternalConnection = ($externalConn !== null);
        $conn = $useExternalConnection ? $externalConn : $this->conn;
        
        // Only start a new transaction if we're not using an external connection
        if (!$useExternalConnection) {
            $conn->beginTransaction();
        }
        
        try {
            // Get all cart items with variant information before clearing
            $sqlGetItems = "SELECT ci.id, ci.qty, pv.id as variant_id, pv.stock_quantity, pv.reserved_quantity, pv.version
                           FROM carts c
                           JOIN cart_items ci ON c.id = ci.cart_id
                           JOIN product_variants pv ON ci.variant_id = pv.id
                           WHERE c.user_id = :user_id AND c.status = 'active'
                           FOR UPDATE";
                           
            $stmtGetItems = $conn->prepare($sqlGetItems);
            $stmtGetItems->execute(['user_id' => $userId]);
            $cartItems = $stmtGetItems->fetchAll(PDO::FETCH_ASSOC);
            
            // Release reserved inventory for all items
            foreach ($cartItems as $item) {
                $sqlRelease = "UPDATE product_variants 
                              SET reserved_quantity = GREATEST(reserved_quantity - :qty, 0),
                                  version = version + 1
                              WHERE id = :variant_id 
                              AND version = :version";
                
                $stmtRelease = $conn->prepare($sqlRelease);
                $stmtRelease->execute([
                    'qty' => $item['qty'],
                    'variant_id' => $item['variant_id'],
                    'version' => $item['version']
                ]);
                
                if ($stmtRelease->rowCount() === 0) {
                    throw new Exception("Failed to release inventory for variant ID: " . $item['variant_id']);
                }
                
                // Log inventory change
                $this->logInventoryChange(
                    $item['variant_id'],
                    'release',
                    $item['qty'],
                    $item['stock_quantity'] + $item['qty'],
                    $item['reserved_quantity'] - $item['qty'],
                    null,
                    'Released from cart clearance'
                );
            }
            
            // Delete all items from the cart
            $sqlDeleteItems = "DELETE ci FROM cart_items ci 
                             JOIN carts c ON ci.cart_id = c.id 
                             WHERE c.user_id = :user_id AND c.status = 'active'";
            
            $stmtDelete = $conn->prepare($sqlDeleteItems);
            $stmtDelete->execute(['user_id' => $userId]);
            
            // Mark cart as converted
            $sqlUpdateCart = "UPDATE carts SET status = 'converted', updated_at = NOW() 
                             WHERE user_id = :user_id AND status = 'active'";
            
            $stmtUpdate = $conn->prepare($sqlUpdateCart);
            $stmtUpdate->execute(['user_id' => $userId]);
            
            // Only commit if we started the transaction in this method
            if (!$useExternalConnection) {
                $conn->commit();
            }
            
            return true;
            
        } catch (Exception $e) {
            // If anything goes wrong, roll back the transaction only if we started it
            if (!$useExternalConnection) {
                $conn->rollBack();
            }
            error_log("Error clearing cart: " . $e->getMessage());
            
            // Re-throw the exception to be handled by the caller
            throw $e;
        }
    }

    private function logInventoryChange($variantId, $actionType, $quantityChange, $stockAfter, $reservedAfter, $referenceId, $note) {
        $sql = "INSERT INTO inventory_logs (variant_id, action_type, quantity_change, stock_after, reserved_after, reference_id, note)
                VALUES (:variant_id, :action_type, :quantity_change, :stock_after, :reserved_after, :reference_id, :note)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'variant_id' => $variantId,
            'action_type' => $actionType,
            'quantity_change' => $quantityChange,
            'stock_after' => $stockAfter,
            'reserved_after' => $reservedAfter,
            'reference_id' => $referenceId,
            'note' => $note
        ]);
    }
    
    public function getCartTotalQuantity($userId) {
        $sql = "SELECT SUM(ci.qty) AS total_quantity
                FROM carts c
                JOIN cart_items ci ON c.id = ci.cart_id
                JOIN product_variants pv ON ci.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = :user_id AND c.status = 'active' AND p.status = 'active'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_quantity'] ?? 0; // Return 0 if cart is empty
    }
    public function updateCartItemQuantity($userId, $productId, $quantity, $variantId = null)
{
    if (!$variantId) {
        throw new Exception("Variant ID is required");
    }

    $this->conn->beginTransaction();

    try {

        // Lock cart item + variant
        $sql = "SELECT ci.id, ci.qty, pv.stock_quantity, pv.reserved_quantity
                FROM carts c
                JOIN cart_items ci ON c.id = ci.cart_id
                JOIN product_variants pv ON ci.variant_id = pv.id
                WHERE c.user_id = :user_id
                  AND c.status = 'active'
                  AND ci.variant_id = :variant_id
                  AND pv.product_id = :product_id
                FOR UPDATE";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'variant_id' => $variantId,
            'product_id' => $productId
        ]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception("Item not found in cart");
        }

        $currentQty = (int)$item['qty'];
        $stockQuantity = (int)$item['stock_quantity'];
        $reservedQuantity = (int)$item['reserved_quantity'];

        // Available stock (add back current reserved)
        $availableStock = $stockQuantity + $currentQty;

        if ($quantity > $availableStock) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Insufficient stock',
                'available_stock' => $availableStock
            ];
        }

        $quantityDifference = $quantity - $currentQty;

        // ===============================
        // REMOVE ITEM
        // ===============================
        if ($quantity <= 0) {

            $stmtDelete = $this->conn->prepare(
                "DELETE FROM cart_items WHERE id = :id"
            );
            $stmtDelete->execute(['id' => $item['id']]);

            // Removed reserved_quantity and version updates as per requirement

            $this->cleanupEmptyCart($userId);
        }

        // ===============================
        // UPDATE ITEM
        // ===============================
        else {

            $stmtUpdate = $this->conn->prepare(
                "UPDATE cart_items SET qty = :qty WHERE id = :id"
            );
            $stmtUpdate->execute([
                'qty' => $quantity,
                'id' => $item['id']
            ]);

            // Removed reserved_quantity and version updates as per requirement
        }

        $this->conn->commit();

        return [
            'success' => true,
            'message' => 'Cart updated successfully'
        ];

    } catch (Exception $e) {

        $this->conn->rollBack();

        return [
            'success' => false,
            'message' => 'Failed to update cart: ' . $e->getMessage()
        ];
    }
}

    
    public function getCartTotal($userId, $userRole = null) {
    $priceColumn = 'price';

    $sql = "SELECT SUM(pv.price * ci.qty) as total 
            FROM carts c
            JOIN cart_items ci ON c.id = ci.cart_id
            JOIN product_variants pv ON ci.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE c.user_id = :user_id AND c.status = 'active' AND p.status = 'active'";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($result['total']) ? (float)$result['total'] : 0;
}


    
}
?>

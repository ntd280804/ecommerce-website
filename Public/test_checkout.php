<?php
require_once(__DIR__ . '/../Config/Database.php');
require_once(__DIR__ . '/Models/Order_Model.php');
require_once(__DIR__ . '/Models/Cart_Model.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Checkout Debug Test</h1>";

// Test database connection
try {
    $db = Database::connect();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Check if stored procedure exists
try {
    $checkSp = $db->query("SHOW PROCEDURE STATUS WHERE Db = DATABASE() AND Name = 'sp_create_order'");
    if ($checkSp->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Stored procedure sp_create_order exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Stored procedure sp_create_order does NOT exist</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error checking stored procedure: " . $e->getMessage() . "</p>";
}

// Test user session (simulate logged in user)
$userId = 1; // Assuming user ID 1 exists
echo "<p>Testing with user ID: $userId</p>";

// Get cart items
try {
    $cartModel = new CartModel($db);
    $cartItems = $cartModel->getCartItems($userId);
    
    if (empty($cartItems)) {
        echo "<p style='color: orange;'>⚠ Cart is empty for user ID $userId</p>";
        echo "<p>Adding test item to cart...</p>";
        
        // Try to add a test item
        $result = $cartModel->addToCart($userId, 1, 1, 1); // Product 1, Variant 1, Quantity 1
        if ($result['success']) {
            echo "<p style='color: green;'>✓ Test item added to cart</p>";
            $cartItems = $cartModel->getCartItems($userId);
        } else {
            echo "<p style='color: red;'>✗ Failed to add test item: " . $result['message'] . "</p>";
        }
    }
    
    if (!empty($cartItems)) {
        echo "<p style='color: green;'>✓ Cart items found:</p>";
        echo "<pre>" . print_r($cartItems, true) . "</pre>";
        
        $totalAmount = $cartModel->getCartTotal($userId);
        echo "<p>Cart total: $totalAmount</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error getting cart items: " . $e->getMessage() . "</p>";
}

// Test order creation
if (!empty($cartItems)) {
    echo "<h2>Testing Order Creation</h2>";
    
    try {
        $orderModel = new OrderModel($db);
        
        $testData = [
            'receiver_name' => 'Test User',
            'receiver_phone' => '0123456789',
            'receiver_address' => '123 Test Street',
            'payment_method' => 'cod'
        ];
        
        echo "<p>Creating order with data:</p>";
        echo "<pre>" . print_r($testData, true) . "</pre>";
        
        $orderId = $orderModel->createOrder(
            $userId,
            $totalAmount,
            $cartItems,
            $testData['receiver_name'],
            $testData['receiver_phone'],
            $testData['receiver_address'],
            $testData['payment_method']
        );
        
        if ($orderId) {
            echo "<p style='color: green;'>✓ Order created successfully with ID: $orderId</p>";
            
            // Check if order exists in database
            $order = $orderModel->getOrderById($orderId);
            if ($order) {
                echo "<p style='color: green;'>✓ Order verified in database</p>";
                echo "<pre>" . print_r($order, true) . "</pre>";
            } else {
                echo "<p style='color: red;'>✗ Order not found in database after creation</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to create order - no ID returned</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Order creation failed: " . $e->getMessage() . "</p>";
        echo "<p>Stack trace:</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

// Check recent error logs
echo "<h2>Recent Error Logs</h2>";
$logFile = ini_get('error_log');
if ($logFile && file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $recentLogs = substr($logs, -2000); // Last 2000 characters
    echo "<pre>$recentLogs</pre>";
} else {
    echo "<p>No error log file found or logging not configured</p>";
}

echo "<h2>Database Tables Check</h2>";
$tables = ['orders', 'order_items', 'carts', 'cart_items', 'users'];
foreach ($tables as $table) {
    try {
        $result = $db->query("SELECT COUNT(*) as count FROM $table");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p>✓ Table '$table' exists with $count records</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Table '$table' error: " . $e->getMessage() . "</p>";
    }
}

?>

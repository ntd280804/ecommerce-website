<?php
require_once(__DIR__ . '/../../Config/Database.php'); 

class ProductVariantModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect();
    }
    
    // Lấy tất cả variants của một sản phẩm
    public function getByProductId($product_id) {
        $sql = "SELECT pv.*, p.name as product_name, p.slug as product_slug
                FROM product_variants pv 
                JOIN products p ON pv.product_id = p.id
                WHERE pv.product_id = ?
                ORDER BY pv.id ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy chi tiết một variant
    public function getById($variant_id) {
        $sql = "SELECT pv.*, p.name as product_name, p.slug as product_slug
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                WHERE pv.id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$variant_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lấy tất cả ảnh của một variant
    public function getImages($variant_id) {
        // Since variant_images table doesn't exist, return the single image_url
        $sql = "SELECT image_url as image_url, 0 as sort_order
                FROM product_variants 
                WHERE id = ? AND image_url IS NOT NULL";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$variant_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return empty array if no image_url exists
        return $result ?: [];
    }
    
    // Lấy variant có giá thấp nhất của sản phẩm
    public function getLowestPriceVariant($product_id) {
        $sql = "SELECT * FROM product_variants 
                WHERE product_id = ? 
                ORDER BY price ASC LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lấy variant có giá cao nhất của sản phẩm  
    public function getHighestPriceVariant($product_id) {
        $sql = "SELECT * FROM product_variants 
                WHERE product_id = ? 
                ORDER BY price DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Kiểm tra tồn kho
    public function checkStock($variant_id, $quantity) {
        $sql = "SELECT stock_quantity FROM product_variants 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$variant_id]);
        $variant = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $variant && $variant['stock_quantity'] >= $quantity;
    }
    
    // Giảm tồn kho
    public function decreaseStock($variant_id, $quantity) {
        $sql = "UPDATE product_variants 
                SET stock_quantity = stock_quantity - ? 
                WHERE id = ? AND stock_quantity >= ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$quantity, $variant_id, $quantity]);
    }
    
    // Tăng tồn kho
    public function increaseStock($variant_id, $quantity) {
        $sql = "UPDATE product_variants 
                SET stock_quantity = stock_quantity + ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$quantity, $variant_id]);
    }
    
    // Tạo variant mới
    public function create($data) {
        $sql = "INSERT INTO product_variants 
                (product_id, sku, price, stock_quantity) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['product_id'],
            $data['sku'],
            $data['price'],
            $data['stock_quantity'] ?? 0
        ]);
    }
    
    // Thêm ảnh cho variant (not supported - only single image_url in product_variants)
    public function addImage($variant_id, $image_url, $sort_order = 0) {
        // Update the single image_url column instead
        $sql = "UPDATE product_variants 
                SET image_url = ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$image_url, $variant_id]);
    }
}
?>

<?php
require_once("../Config/Database.php"); 

class ProductVariantModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect();
    }
    
    // Lấy tất cả variants của một sản phẩm
    public function getByProductId($product_id) {
        $sql = "SELECT pv.*
                FROM product_variants pv 
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
    
    // Lấy ảnh cho một variant (trả về mảng rỗng vì không sử dụng variant_images)
    public function getImages($variant_id) {
        return [];
    }
    
    // Tạo variant mới
    public function create($data) {
        $sql = "INSERT INTO product_variants 
                (product_id, name, sku, price, stock_quantity, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'active')";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['product_id'],
            $data['name'],
            $data['sku'],
            $data['price'],
            $data['stock_quantity'] ?? 0,
            $data['image_url'] ?? null
        ]);
    }
    
    // Cập nhật variant
    public function update($id, $data) {
        $sql = "UPDATE product_variants 
                SET name = ?, sku = ?, price = ?, stock_quantity = ?, image_url = ?
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['sku'],
            $data['price'],
            $data['stock_quantity'] ?? 0,
            $data['image_url'] ?? null,
            $id
        ]);
    }
    
    // Xóa variant
    public function delete($id) {
        $sql = "UPDATE product_variants SET status = 'inactive' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Thêm ảnh cho variant
    public function addImage($variant_id, $image_url, $sort_order = 0) {
        $sql = "INSERT INTO variant_images (variant_id, image_url, sort_order) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$variant_id, $image_url, $sort_order]);
    }
}
?>

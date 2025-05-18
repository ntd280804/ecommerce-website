<?php
require_once("../Config/Database.php"); 

class ProductModel {
    private $conn;
    public $name;
    public $slug;
    public $summary;
    public $description;
    public $stock;
    public $price;
    public $discounted_price;
    public $category_id;
    public $brand_id;
    public $countfiles;
    public $images = '';
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }

    public function getAvatarImages($arrstr,$height)
    {
        $arr = explode(';', $arrstr);
        return "<img src='$arr[0]' height='$height' alt='Product Image' />";
    }
    public function getAllImages($arrstr, $height = 100)
    {
        $arr = explode(';', $arrstr);
        $html = '';
        foreach ($arr as $img) {
            if (!empty($img)) {
                $html .= "<img src='" . htmlspecialchars($img) . "' height='$height' style='margin-right:10px;' alt='Product Image' />";
            }
        }
        return $html;
    }
    public function getProductReviews($product_id) {
        $sql = "SELECT r.comment, u.name as user_name, r.created_at 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? 
                ORDER BY r.created_at DESC 
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getProductRating($product_id) {
        $sql = "SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating 
                FROM reviews WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByCategory($category) {
        $sql = "SELECT * FROM products WHERE status = 'active' AND category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getImagesById($id) {
        // Truy vấn lấy hình ảnh của sản phẩm theo id
        $sql = "SELECT images FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);  // Sử dụng $this->conn thay vì DB::prepare()
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        // Trả về danh sách hình ảnh của sản phẩm
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['images'] ?? ''; // Trả về chuỗi hình ảnh nếu có, nếu không trả về chuỗi rỗng
    }
    
    public function getBrandNameById($brand_id) {
        $stmt = $this->conn->prepare("SELECT name FROM brands WHERE id = :id");
        $stmt->bindParam(':id', $brand_id, PDO::PARAM_INT);
        $stmt->execute();
        $brand = $stmt->fetch(PDO::FETCH_ASSOC);
        return $brand ? $brand['name'] : 'N/A';
    }
    
    public function getCategoryNameById($category_id) {
        $stmt = $this->conn->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ? $category['name'] : 'N/A';
    }
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllActive() {
        $sql = "SELECT * FROM products where status='Active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getAllActiveWithPagination($limit, $offset) {
        $sql = "SELECT * FROM products WHERE status = 'Active' LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllActive() {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE status = 'Active'";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getByCategoryWithPagination($category, $limit, $offset) {
        $sql = "SELECT * FROM products WHERE category_id = :category AND status = 'Active' LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByCategory($category) {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE category_id = :category AND status = 'Active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTopRated() {
        $sql = "SELECT p.*, COUNT(r.id) AS review_count
                FROM products p
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.status = 'Active'
                GROUP BY p.id
                ORDER BY review_count DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}


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
    public $disscounted_price;
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
}


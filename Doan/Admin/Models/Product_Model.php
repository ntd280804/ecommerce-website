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

    public function insert() {

        $sql = "INSERT INTO products (name,slug, summary, description, stock, price, disscounted_price,images, category_id, brand_id)
                VALUES (:name,:slug, :summary, :description, :stock, :price, :disscounted_price,:images, :category_id, :brand_id)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':disscounted_price', $this->disscounted_price);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':brand_id', $this->brand_id);

        return $stmt->execute();
    }
    
    // Gợi ý thêm:
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvatarImages($arrstr,$height)
{
    $arr = explode(';', $arrstr);
    return "<img src='$arr[0]' height='$height' alt='Product Image' />";
}

}


<?php
require_once("../Config/Database.php"); 

class ProductModel {
    private $conn;
    public $name;
    public $slug;
    public $summary;
    public $description;
    public $price;
    public $category_id;
    public $countfiles;
    public $images = '';
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }

    public function insert() {

        $sql = "INSERT INTO products (name, slug, summary, description, price, images, category_id)
        VALUES (:name, :slug, :summary, :description, :price, :images, :category_id)";

        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);

        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':category_id', $this->category_id);

        return $stmt->execute();
    }
    
    // Gợi ý thêm:
    public function updateStatus($id, $newStatus) {
        // Set the status to the provided value (no toggling)
        $sql = "UPDATE products SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
      

    public function getByStatus($status) {
        require_once __DIR__ . '/ProductVariant_Model.php';
        $variantModel = new ProductVariantModel();
        
        // Assuming you are using PDO to interact with database
        if ($status === 'all') {
            $query = "SELECT * FROM products";
        } else {
            $query = "SELECT * FROM products WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add variants and price range to each product
        foreach ($products as &$product) {
            $variants = $variantModel->getByProductId($product['id']);
            $product['variants'] = $variants;
            
            // Calculate price range from variants
            if (!empty($variants)) {
                $prices = array_column($variants, 'price');
                $minPrice = min($prices);
                $maxPrice = max($prices);
                
                if ($minPrice == $maxPrice) {
                    $product['price_range'] = number_format($minPrice, 0, ',', '.') . ' VNĐ';
                } else {
                    $product['price_range'] = number_format($minPrice, 0, ',', '.') . ' - ' . number_format($maxPrice, 0, ',', '.') . ' VNĐ';
                }
                
                // Calculate total stock
                $product['total_stock'] = array_sum(array_column($variants, 'stock_quantity'));
            } else {
                // Fallback to product price if no variants
                $product['price_range'] = number_format($product['price'], 0, ',', '.') . ' VNĐ';
                $product['total_stock'] = 0;
            }
        }
        
        return $products;
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
    public function update($id) {
        $sql = "UPDATE products SET 
                    name = :name, 
                    slug = :slug, 
                    summary = :summary, 
                    description = :description,
                    images = :images, 
                    category_id = :category_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function generateStaticFromFriendlyURL($friendlyUrl, $outputPath) {
    // 1. Dùng cURL để gọi URL như trình duyệt thật
    $ch = curl_init($friendlyUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // theo dõi redirect nếu có
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);

    if ($html === false) {
        echo "❌ Không thể lấy nội dung từ $friendlyUrl. Lỗi: " . curl_error($ch) . "\n";
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    // 2. Tạo thư mục nếu chưa có
    $folder = dirname($outputPath);
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // 3. Ghi file tĩnh
    if (file_put_contents($outputPath, $html) !== false) {
        echo "✅ Đã tạo static: $outputPath\n";
        return true;
    } else {
        echo "❌ Ghi file thất bại: $outputPath\n";
        return false;
    }
}

    public function countProductsUsingImage($imagePath) {
        // Thay vì real_escape_string, dùng PDO chuẩn bị câu truy vấn và bind tham số
        $sql = "SELECT COUNT(*) as total FROM products WHERE FIND_IN_SET(:imagePath, REPLACE(images, ';', ',')) > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }
    
    
}

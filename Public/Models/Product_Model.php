<?php
require_once("../Config/Database.php"); 
require_once("ProductVariant_Model.php");

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
        $this->conn = Database::connect();
    }

// ...existing code...
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
    
    public function getByCategory($category) {
        $sql = "SELECT * FROM products WHERE status = 'active' AND category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getImagesByslug($slug) {
        // Truy vấn lấy hình ảnh của sản phẩm theo id
        $sql = "SELECT images FROM products WHERE slug = :slug";
        $stmt = $this->conn->prepare($sql);  // Sử dụng $this->conn thay vì DB::prepare()
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
    
        // Trả về danh sách hình ảnh của sản phẩm
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['images'] ?? ''; // Trả về chuỗi hình ảnh nếu có, nếu không trả về chuỗi rỗng
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
    public function getByslug($slug) {
        // First get the basic product info
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.slug = :slug AND p.status = 'active'";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            return null;
        }
        
        // Get all variants for this product
        $sqlVariants = "SELECT * FROM product_variants 
                       WHERE product_id = :product_id 
                       AND status = 'active'
                       ORDER BY price ASC";
        $stmtVariants = $this->conn->prepare($sqlVariants);
        $stmtVariants->bindParam(':product_id', $product['id'], PDO::PARAM_INT);
        $stmtVariants->execute();
        $variants = $stmtVariants->fetchAll(PDO::FETCH_ASSOC);
        
        // Add variants to the product data
        $product['variants'] = $variants;
        
        // Calculate min and max prices
        if (!empty($variants)) {
            $prices = array_column($variants, 'price');
            $product['min_price'] = min($prices);
            $product['max_price'] = max($prices);
            $product['has_variants'] = count($variants) > 1;
        } else {
            $product['min_price'] = $product['price'];
            $product['max_price'] = $product['price'];
            $product['has_variants'] = false;
        }
        
        return $product;
    }
    
    public function getByCategoryWithPagination($slug, $limit, $offset, $sort = 'default') {
    $limit = (int)$limit;
    $offset = (int)$offset;

    // Xử lý sắp xếp
    $orderBy = "";
    if ($sort == 'asc') {
        $orderBy = "ORDER BY p.name ASC";
    } elseif ($sort == 'desc') {
        $orderBy = "ORDER BY p.name DESC";
    }

    $sql = "SELECT p.*
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE c.slug = :slug AND p.status = 'active'
            GROUP BY p.id
            $orderBy
            LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    public function searchProductsWithPagination($keyword, $limit, $offset, $sort = 'default') {
        $param = '%' . strtolower($keyword) . '%';
        $limit = (int)$limit;
        $offset = (int)$offset;

        // Xử lý sắp xếp
        $orderBy = "";
        if ($sort == 'asc') {
            $orderBy = "ORDER BY p.name ASC";
        } elseif ($sort == 'desc') {
            $orderBy = "ORDER BY p.name DESC";
        }

        // Truy vấn
        $sql = "SELECT p.*
                FROM products p
                WHERE 
                p.status = 'active' AND
                (LOWER(p.slug) LIKE ? 
                OR LOWER(REPLACE(p.slug, '-', ' ')) LIKE ? 
                OR LOWER(p.name) LIKE ? 
                OR LOWER(p.description) LIKE ?)
                $orderBy
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute([$param, $param, $param, $param]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearchProducts($keyword) {
        $keyword = strtolower($keyword);
        $param = '%' . $keyword . '%';
    
        $sql = "SELECT COUNT(*) FROM products 
                WHERE status = 'active' AND
                (LOWER(name) LIKE ? 
                   OR LOWER(REPLACE(slug, '-', ' ')) LIKE ? 
                   OR LOWER(description) LIKE ? 
                   OR LOWER(summary) LIKE ?)";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$param, $param, $param, $param]);
        return $stmt->fetchColumn();
    }
    
    public function countByCategory($slug) {
    $sql = "SELECT COUNT(*) AS total 
            FROM products 
            WHERE category_id = (SELECT id FROM categories WHERE slug = :slug) 
              AND status = 'active'";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

    // === METHODS CHO PRODUCT VARIANTS ===
    
    // Lấy variants của sản phẩm kèm giá min/max
    public function getProductWithVariants($slug) {
        $variantModel = new ProductVariantModel();
        
        // Lấy thông tin sản phẩm
        $sql = "SELECT * FROM products WHERE slug = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$slug]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) return null;
        
        // Lấy variants
        $variants = $variantModel->getByProductId($product['id']);
        
        // Lấy giá min/max
        $minPrice = $variantModel->getLowestPriceVariant($product['id']);
        $maxPrice = $variantModel->getHighestPriceVariant($product['id']);
        
        $product['variants'] = $variants;
        $product['min_price'] = $minPrice ? $minPrice['price'] : 0;
        $product['max_price'] = $maxPrice ? $maxPrice['price'] : 0;
        $product['price_range'] = $this->getPriceRange($product['min_price'], $product['max_price']);
        
        return $product;
    }
    
    // Format price range
    private function getPriceRange($min, $max) {
        if ($min == $max) {
            return number_format($min, 0, ',', '.') . ' VNĐ';
        } else {
            return number_format($min, 0, ',', '.') . ' - ' . number_format($max, 0, ',', '.') . ' VNĐ';
        }
    }
    
    // Get product variants by product ID
    private function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants 
                WHERE product_id = :product_id 
                AND status = 'active'
                ORDER BY price ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Cập nhật getAllActiveWithPagination để làm việc với variants
    public function getAllActiveWithPagination($limit, $offset, $sort = 'default') {
        $limit = (int)$limit;
        $offset = (int)$offset;
    
        $orderBy = "";
        if ($sort == 'asc') {
            $orderBy = "ORDER BY min_price ASC";
        } elseif ($sort == 'desc') {
            $orderBy = "ORDER BY min_price DESC";
        }
    
        $sql = "SELECT p.*, 
                       (SELECT MIN(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as min_price,
                       (SELECT MAX(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as max_price
                FROM products p
                WHERE p.status = 'active' 
                AND EXISTS (SELECT 1 FROM product_variants pv WHERE pv.product_id = p.id)
                GROUP BY p.id
                $orderBy
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add variants and price range for each product
        foreach ($products as &$product) {
            $product['variants'] = $this->getProductVariants($product['id']);
            $product['price_range'] = $this->getPriceRange($product['min_price'], $product['max_price']);
        }
        
        return $products;
    }
    
    // Cập nhật các method khác để làm việc với variants
    public function getTopDiscounted($limit = 5) {
        $sql = "SELECT p.*, 
                       (SELECT MIN(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as min_price,
                       (SELECT MAX(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as max_price
                FROM products p
                WHERE p.status = 'active' 
                AND EXISTS (SELECT 1 FROM product_variants pv WHERE pv.product_id = p.id)
                ORDER BY (max_price - min_price) DESC
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as &$product) {
            $product['price_range'] = $this->getPriceRange($product['min_price'], $product['max_price']);
        }
        
        return $products;
    }
    
    public function getTopRated($limit = 5) {
        $sql = "SELECT p.*, 
                       (SELECT MIN(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as min_price,
                       (SELECT MAX(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) as max_price
                FROM products p
                WHERE p.status = 'active'
                AND EXISTS (SELECT 1 FROM product_variants pv WHERE pv.product_id = p.id)
                GROUP BY p.id
                ORDER BY p.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as &$product) {
            $product['price_range'] = $this->getPriceRange($product['min_price'], $product['max_price']);
        }
        
        return $products;
    }
    
    // Cập nhật countAllActive để chỉ đếm sản phẩm có variants
    public function countAllActive() {
        $sql = "SELECT COUNT(*) AS total FROM products p 
                WHERE p.status = 'active' 
                AND EXISTS (SELECT 1 FROM product_variants pv WHERE pv.product_id = p.id)";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}


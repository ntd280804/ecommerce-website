<?php
require_once("../Config/Database.php"); 

class ProductModel {
    private $conn;
    public $name;
    public $slug;
    public $summary;
    public $description;
    public $price;
    public $vip1_price;
    public $vip2_price;
    public $category_id;
    public $brand_id;
    public $countfiles;
    public $images = '';
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
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
    public function getProductReviews($slug) {
    // Lấy ID sản phẩm theo slug
    $stmt = $this->conn->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) return [];

    $product_id = $product['id'];

    $sql = "SELECT r.comment, r.rating, u.name AS user_name, r.created_at 
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC 
            LIMIT 5";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$product_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getProductRating($slug) {
    $stmt = $this->conn->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) return ['total_reviews' => 0, 'avg_rating' => 0];

    $product_id = $product['id'];

    $sql = "SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating 
            FROM reviews 
            WHERE product_id = ?";
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
    public function getByslug($slug) {
        $sql = "SELECT * FROM products WHERE slug = :slug";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getTopDiscounted($limit = 5) {
    $sql = "SELECT * 
            FROM products 
            WHERE status = 'active' 
            ORDER BY price DESC 
            LIMIT :limit";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    
    public function getAllActiveWithPagination($limit, $offset, $sort = 'default') {
        $limit = (int)$limit;
        $offset = (int)$offset;
    
        // Xử lý sắp xếp
        $orderBy = "";
        if ($sort == 'asc') {
            $orderBy = "ORDER BY p.price ASC";
        } elseif ($sort == 'desc') {
            $orderBy = "ORDER BY p.price DESC";
        } elseif ($sort == 'rating_asc') {
            $orderBy = "ORDER BY avg_rating ASC";
        } elseif ($sort == 'rating_desc') {
            $orderBy = "ORDER BY avg_rating DESC";
        }
    
        // Truy vấn lấy sản phẩm kèm avg_rating
        $sql = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating
                FROM products p
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.status = 'Active' 
                GROUP BY p.id
                $orderBy
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByCategoryWithPagination($slug, $limit, $offset, $sort = 'default') {
    $limit = (int)$limit;
    $offset = (int)$offset;

    // Xử lý sắp xếp
    $orderBy = "";
    if ($sort == 'asc') {
        $orderBy = "ORDER BY p.price ASC";
    } elseif ($sort == 'desc') {
        $orderBy = "ORDER BY p.price DESC";
    } elseif ($sort == 'rating_asc') {
        $orderBy = "ORDER BY avg_rating ASC";
    } elseif ($sort == 'rating_desc') {
        $orderBy = "ORDER BY avg_rating DESC";
    }

    $sql = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating
            FROM products p
            LEFT JOIN reviews r ON p.id = r.product_id
            JOIN categories c ON p.category_id = c.id
            WHERE c.slug = :slug AND p.status = 'Active'
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
            $orderBy = "ORDER BY p.price ASC";
        } elseif ($sort == 'desc') {
            $orderBy = "ORDER BY p.price DESC";
        } elseif ($sort == 'rating_asc') {
            $orderBy = "ORDER BY avg_rating ASC";
        } elseif ($sort == 'rating_desc') {
            $orderBy = "ORDER BY avg_rating DESC";
        }

        // Truy vấn
        $sql = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating
                FROM products p
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE 
                LOWER(p.slug) LIKE ? 
                OR LOWER(REPLACE(p.slug, '-', ' ')) LIKE ? 
                OR LOWER(p.name) LIKE ? 
                OR LOWER(p.description) LIKE ?
                GROUP BY p.id
                $orderBy
                LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$param, $param, $param, $param]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
    
    public function countAllActive() {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE status = 'Active'";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    
    
    
    public function countSearchProducts($keyword) {
        $keyword = strtolower($keyword);
        $param = '%' . $keyword . '%';
    
        $sql = "SELECT COUNT(*) FROM products 
                WHERE LOWER(name) LIKE ? 
                   OR LOWER(REPLACE(slug, '-', ' ')) LIKE ? 
                   OR LOWER(description) LIKE ? 
                   OR LOWER(summary) LIKE ?";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$param, $param, $param, $param]);
        return $stmt->fetchColumn();
    }
    
    public function countByCategory($slug) {
    $sql = "SELECT COUNT(*) AS total 
            FROM products 
            WHERE category_id = (SELECT id FROM categories WHERE slug = :slug) 
              AND status = 'Active'";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}


    public function getTopRated() {
        $sql = "SELECT p.*, 
                       COUNT(r.id) AS review_count,
                       COALESCE(AVG(r.rating), 0) AS avg_rating
                FROM products p
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.status = 'Active'
                GROUP BY p.id
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT 5";   // <-- giới hạn 5 sản phẩm đầu
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
}


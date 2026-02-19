<?php
require_once './Models/Category_Model.php';
require_once './Models/Product_Model.php';
class ProductController {
    public function index() {
        
    
        // If 'status' is not set in the URL, redirect to the URL with 'status=all'
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=product&action=index&status=all");
            exit();
        }
    
        // Get the status, defaulting to 'all' if not set
        $productmodel = new ProductModel();

        $status = $_GET['status'] ?? 'all'; 
        $products = $productmodel->getByStatus($status);

        include './Views/ListProducts.php';
    }
    
    public function toggleStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];
            $Productmodel = new ProductModel();
            
            // Call a method to update the product's status
            if ($Productmodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=product&action=index");
                exit();
            } else {
                echo "Error updating product status.";
            }
        }
    }
    public function add() {

        $categorymodel = new CategoryModel();
        $categories = $categorymodel->getByStatus('all'); // Lấy danh sách danh mục từ model
        include './Views/AddProduct.php';
    }
    public function store() {
        require_once './Models/ProductVariant_Model.php';
        require_once '../Config/Database.php';
        
        $product = new ProductModel;
        $variantModel = new ProductVariantModel();
        $this->conn = Database::connect();
    
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
        $product->summary = $_POST['summary'];
        $product->description = $_POST['description'];
        $product->category_id = $_POST['category_id'];
        $product->status = 'active';
        
        // Handle product main images (optional now)
        $product->images = '';
        if (!empty($_FILES['images']['name'][0])) {
            $product->countfiles = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $product->countfiles; $i++) {
                $filename = $_FILES['images']['name'][$i];
                $location = "../Uploads/" . uniqid() . $filename;
                $extension = pathinfo($location, PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                
                $valid_extensions = array("jpg", "jpeg", "png");
                
                if (in_array(strtolower($extension), $valid_extensions)) {
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $location)) {
                        $product->images .= $location . ";";
                    }
                }
            }
            $product->images = substr($product->images, 0, -1);
        }

        // Insert product first
        if ($product->insert()) {
            $db = Database::connect();
            $productId = $db->lastInsertId();
            
            // Handle variants
            $variantNames = $_POST['variant_name'];
            $variantSkus = $_POST['variant_sku'];
            $variantPrices = $_POST['variant_price'];
            $variantStocks = $_POST['variant_stock'];
            
            $successCount = 0;
            
            foreach ($variantNames as $index => $name) {
                $variantData = [
                    'product_id' => $productId,
                    'name' => $name,
                    'sku' => $variantSkus[$index],
                    'price' => $variantPrices[$index],
                    'stock_quantity' => $variantStocks[$index] ?? 0
                ];
                
                // Handle variant image
                if (!empty($_FILES['variant_images']['name'][$index])) {
                    $variantImage = $_FILES['variant_images']['name'][$index];
                    $imageLocation = "../Uploads/" . uniqid() . $variantImage;
                    $imageExtension = pathinfo($imageLocation, PATHINFO_EXTENSION);
                    $imageExtension = strtolower($imageExtension);
                    
                    if (in_array($imageExtension, ["jpg", "jpeg", "png"])) {
                        if (move_uploaded_file($_FILES['variant_images']['tmp_name'][$index], $imageLocation)) {
                            $variantData['image_url'] = $imageLocation;
                        }
                    }
                }
                
                if ($variantModel->create($variantData)) {
                    $successCount++;
                }
            }
            
            if ($successCount > 0) {
                // Generate static page
                $slug = $product->slug;
                $friendlyUrl = "http://localhost/Public/product/$slug.html";
                $outputPath = __DIR__ . "/../../Public/product/$slug.html";
                $product->generateStaticFromFriendlyURL($friendlyUrl, $outputPath);
                
                $_SESSION['success_message'] = "Đã thêm sản phẩm và $successCount phân loại thành công!";
                header("Location: ./index.php?controller=product&action=index");
                exit();
            } else {
                echo "Lỗi khi thêm phân loại sản phẩm.";
            }
        } else {
            echo "Lỗi khi thêm sản phẩm.";
        }
    }
    public function update() {
        require_once './Models/ProductVariant_Model.php';
        
        $product = new ProductModel;
        $variantModel = new ProductVariantModel();
        $id = $_POST['id'];
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
        $product->summary = $_POST['summary'];
        $product->description = $_POST['description'];
        $product->category_id = $_POST['category_id'];
    
        // Lấy danh sách ảnh được gửi từ form
        $imageList = isset($_POST['imageList']) ? explode(';', $_POST['imageList']) : [];
        $oldImages = $product->getImagesById($id);
        $oldImages = explode(";", $oldImages);
    
        // Khởi tạo danh sách ảnh mới
        $newImages = [];
        
        // Duyệt qua danh sách ảnh cũ để giữ lại những ảnh không bị xóa
        foreach ($oldImages as $oldImage) {
            if (in_array($oldImage, $imageList)) {
                $newImages[] = $oldImage;
            } else {
                if ($product->countProductsUsingImage($oldImage) <= 1) {
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }
            }
        }
        
    
        // Xử lý các ảnh mới được upload
        if (!empty($_FILES['images']['name'][0])) {
            $product->countfiles = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $product->countfiles; $i++) {
                $filename = $_FILES['images']['name'][$i];
                $location = "../Uploads/" . uniqid() . $filename;
                $extension = pathinfo($location, PATHINFO_EXTENSION);
                $extension = strtolower($extension);
    
                // Các định dạng file hợp lệ
                $valid_extensions = ["jpg", "jpeg", "png"];
    
                if (in_array($extension, $valid_extensions)) {
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $location)) {
                        $newImages[] = $location;
                    }
                }
            }
        }
    
        // Gán lại danh sách ảnh sau khi xử lý
        $product->images = implode(";", $newImages);
    
        // Cập nhật cơ sở dữ liệu
        if ($product->update($id)) {
            // Handle variants update
            if (isset($_POST['variant_name'])) {
                // Get existing variants
                $existingVariants = $variantModel->getByProductId($id);
                $existingVariantIds = array_column($existingVariants, 'id');
                
                $variantNames = $_POST['variant_name'];
                $variantSkus = $_POST['variant_sku'] ?? [];
                $variantPrices = $_POST['variant_price'];
                $variantStocks = $_POST['variant_stock'] ?? [];
                
                // Update or create variants
                foreach ($variantNames as $index => $name) {
                    $variantData = [
                        'name' => $name,
                        'sku' => $variantSkus[$index] ?? '',
                        'price' => $variantPrices[$index],
                        'stock_quantity' => $variantStocks[$index] ?? 0
                    ];
                    
                    // Handle variant image
                    if (!empty($_FILES['variant_images']['name'][$index])) {
                        $variantImage = $_FILES['variant_images']['name'][$index];
                        $imageLocation = "../Uploads/" . uniqid() . $variantImage;
                        $imageExtension = pathinfo($imageLocation, PATHINFO_EXTENSION);
                        $imageExtension = strtolower($imageExtension);
                        
                        if (in_array($imageExtension, ["jpg", "jpeg", "png"])) {
                            if (move_uploaded_file($_FILES['variant_images']['tmp_name'][$index], $imageLocation)) {
                                $variantData['image_url'] = $imageLocation;
                            }
                        }
                    }
                    
                    // Check if this is an existing variant
                    $variantId = $_POST['variant_id'][$index] ?? null;
                    if ($variantId && in_array($variantId, $existingVariantIds)) {
                        // Update existing variant
                        $variantModel->update($variantId, $variantData);
                    } else {
                        // Create new variant
                        $variantData['product_id'] = $id;
                        $variantModel->create($variantData);
                    }
                }
            }
            
            $slug = $product->slug; // Hoặc $_POST['slug'] sau khi lưu DB
            $friendlyUrl = "http://localhost/Public/product/$slug.html";
            $outputPath = __DIR__ . "/../../Public/product/$slug.html"; // đường dẫn vật lý lưu file

            $product->generateStaticFromFriendlyURL($friendlyUrl, $outputPath);
            $this->index();
        } else {
            echo "Lỗi khi cập nhật sản phẩm.";
        }
    }
    
    
    
    
    public function edit() {
        $categorymodel = new CategoryModel();
        $categories = $categorymodel->getByStatus('all'); // Lấy danh sách danh mục từ model
        $productmodel = new ProductModel();
    
        $id = $_GET['id']; // Lấy id từ URL
        $product = $productmodel->getById($id); // Gọi hàm lấy dữ liệu trong model
    
        include './Views/EditProduct.php'; // Truyền biến $product sang View
    }
    
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && isset($_POST['selected_ids'])) {
            $action = $_POST['bulk_action'];
            $selectedIds = $_POST['selected_ids'];
            $productModel = new ProductModel();
            
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($selectedIds as $id) {
                switch ($action) {
                    case 'activate':
                        if ($productModel->updateStatus($id, 'inactive')) {
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                        break;
                        
                    case 'inactive':
                        if ($productModel->updateStatus($id, 'active')) {
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                        break;
                        
                    case 'delete':
                        if ($productModel->delete($id)) {
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                        break;
                }
            }
            
            // Set session message
            $_SESSION['bulk_message'] = "Đã xử lý thành công $successCount sản phẩm. " . ($errorCount > 0 ? "Lỗi: $errorCount sản phẩm." : "");
            
            header("Location: ./index.php?controller=product&action=index");
            exit();
        }
    }
}

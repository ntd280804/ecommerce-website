<?php
require_once './Models/Brand_Model.php';
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
            
            // Call a method to update the brand's status
            if ($Productmodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=product&action=index");
                exit();
            } else {
                echo "Error updating product status.";
            }
        }
    }
    public function add() {

        $brandmodel = new BrandModel();
        $brands = $brandmodel->getByStatus('all'); // Lấy danh sách thương hiệu từ model
        
        $categorymodel = new CategoryModel();
        $categories = $categorymodel->getByStatus('all'); // Lấy danh sách danh mục từ model
        include './Views/AddProduct.php';
    }
    public function store() {
        $product = new ProductModel;
    
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
        $product->summary = $_POST['summary'];
        $product->description = $_POST['description'];
        $product->stock = $_POST['stock'];
        $product->price = $_POST['price'];
        $product->discounted_price = $_POST['discounted_price'];
        $product->category_id = $_POST['category_id'];
        $product->brand_id = $_POST['brand_id'];
        $product->countfiles = count($_FILES['images']['name']);
        
        $product->images = '';
        for ($i = 0; $i < $product->countfiles; $i++) {
            $filename = $_FILES['images']['name'][$i];

            ## Location
            $location = "../Uploads/" . uniqid() . $filename;
            //pathinfo ( string $path [, int $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME ] ) : mixed
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);

            ## File upload allowed extensions
            $valid_extensions = array("jpg", "jpeg", "png");

            $response = 0;
            ## Check file extension
            if (in_array(strtolower($extension), $valid_extensions)) {

                // them vao CSDL - them thah cong moi upload anh len
                ## Upload file
                //$_FILES['file']['tmp_name']: $_FILES['file']['tmp_name'] - The temporary filename of the file in which the uploaded file was stored on the server.
                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $location)) {

                    $product->images .= $location . ";";
                }
            }

        }
        $product->images = substr($product->images, 0, -1);

        // echo substr($images, 0, -1); exit;
        if ($product->insert()) {
            $this->index(); //
            // header("Location: ./index.php?controller=product&action=index"); // Chuyển hướng về danh sách sản phẩm
        } else {
            echo "Lỗi khi thêm sản phẩm.";
        }
    }
    public function update() {
        $product = new ProductModel;
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
        $product->summary = $_POST['summary'];
        $product->description = $_POST['description'];
        $product->stock = $_POST['stock'];
        $product->price = $_POST['price'];
        $product->discounted_price = $_POST['discounted_price'];
        $product->category_id = $_POST['category_id'];
        $product->brand_id = $_POST['brand_id'];
    
        // Lấy danh sách các file hình ảnh cũ từ CSDL
        $oldImages = $product->getImagesById($id);
        $oldImages = explode(";", $oldImages); // Chia các đường dẫn file hình ảnh cũ
    
        // Lưu các file mới
        $product->countfiles = count($_FILES['images']['name']);
        $product->images = '';
        $newImages = [];
    
        for ($i = 0; $i < $product->countfiles; $i++) {
            $filename = $_FILES['images']['name'][$i];
    
            // Định vị lưu trữ
            $location = "../Uploads/" . uniqid() . $filename;
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
    
            // Các định dạng file hợp lệ
            $valid_extensions = array("jpg", "jpeg", "png");
    
            if (in_array(strtolower($extension), $valid_extensions)) {
                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $location)) {
                    $product->images .= $location . ";";
                    $newImages[] = $location; // Lưu đường dẫn hình ảnh mới vào mảng
                }
            }
        }
    
        $product->images = substr($product->images, 0, -1); // Cắt dấu ";" cuối cùng
    
        // Xóa các file cũ không còn trong danh sách mới
        $imagesToDelete = array_diff($oldImages, $newImages); // Các hình ảnh cũ không còn trong mảng mới
        foreach ($imagesToDelete as $image) {
            if (file_exists($image)) {
                unlink($image); // Xóa file cũ
            }
        }
    
        // Cập nhật cơ sở dữ liệu
        if ($product->update($id)) {
            $this->index(); // Quay lại danh sách sản phẩm
        } else {
            echo "Lỗi khi cập nhật sản phẩm.";
        }
    }
    
    
    public function edit() {
        $brandmodel = new BrandModel();
        $brands = $brandmodel->getByStatus('all'); // Lấy danh sách thương hiệu từ model
        $categorymodel = new CategoryModel();
        $categories = $categorymodel->getByStatus('all'); // Lấy danh sách danh mục từ model
        $productmodel = new ProductModel();
    
        $id = $_GET['id']; // Lấy id từ URL
        $product = $productmodel->getById($id); // Gọi hàm lấy dữ liệu trong model
    
        include './Views/EditProduct.php'; // Truyền biến $brand sang View
    }
    
    
}

<?php
class ProductController {
    public function index() {
        require_once './Models/Product_Model.php';
        $productModel = new ProductModel();
        $products = $productModel->getAll(); // Lấy danh sách sản phẩm từ model
        include './Views/ListProducts.php';
    }
    public function add() {
        require_once './Models/Brand_Model.php';
        $brandModel = new BrandModel();
        $brands = $brandModel->getAll(); // Lấy danh sách thương hiệu từ model
        require_once './Models/Category_Model.php';
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll(); // Lấy danh sách danh mục từ model
        include './Views/AddProduct.php';
    }
    public function store() {
        require_once './Models/Product_Model.php';
        $product = new ProductModel;
    
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
        $product->summary = $_POST['summary'];
        $product->description = $_POST['description'];
        $product->stock = $_POST['stock'];
        $product->price = $_POST['price'];
        $product->disscounted_price = $_POST['disscounted_price'];
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
    
}

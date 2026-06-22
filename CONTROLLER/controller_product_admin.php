<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Product.php';

class controller_product
{
    private $conn;
    private $productModel;

    // Khởi tạo controller
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->productModel = new Product($conn);
    }

    // Lấy thông tin chi tiết sản phẩm theo ID
    public function getProductDetail($product_id)
    {
        return $this->productModel->getById($product_id);
    }

    // Cập nhật thông tin sản phẩm
    public function updateProduct()
    {
        // Kiểm tra yêu cầu cập nhật
        if (
            !isset($_POST['action']) ||
            $_POST['action'] !== 'update'
        ) {
            return false;
        }

        // Lấy ID sản phẩm
        $product_id = (int)$_POST['product_id'];

        // Kiểm tra sản phẩm tồn tại
        $product = $this->productModel->getById($product_id);

        if (!$product) {
            return false;
        }

        // Cập nhật dữ liệu sản phẩm
        return $this->productModel->update(
            $product_id,
            trim($_POST['product_name']),
            trim($_POST['description']),
            (int)$_POST['category_id'],
            (int)$product['account_id'],
            trim($_POST['thumbnail_url']),
            (float)$_POST['price'],
            trim($_POST['preview_url'])
        );
    }

    // Xóa sản phẩm
    public function deleteProduct($product_id)
    {
        return $this->productModel->delete($product_id);
    }
}

// Khởi tạo controller
$controller = new controller_product($conn);

// API lấy chi tiết sản phẩm (AJAX)
if (
    isset($_GET['action']) &&
    $_GET['action'] === 'detail'
) {

    $id = (int)$_GET['id'];

    // Lấy dữ liệu sản phẩm
    $product = $controller->getProductDetail($id);

    // Trả dữ liệu JSON
    header('Content-Type: application/json');

    echo json_encode([
        'success' => true,
        'product' => $product
    ]);

    exit;
}

/* Xử lý cập nhật sản phẩm */
if (
    isset($_POST['action']) &&
    $_POST['action'] === 'update'
) {

    $controller->updateProduct();

    // Quay về trang quản lý sản phẩm
    header("Location: ../VIEW/page/products-admin.php");
    exit;
}

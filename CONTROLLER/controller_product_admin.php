<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Product.php';

class controller_product
{
    private $conn;
    private $productModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->productModel = new Product($conn);
    }

    public function getProductDetail($product_id)
{
    return $this->productModel->getById($product_id);
}

    public function updateProduct()
    {
        if (
            !isset($_POST['action']) ||
            $_POST['action'] !== 'update'
        ) {
            return false;
        }

        $product_id = (int)$_POST['product_id'];

        $product = $this->productModel->getById($product_id);

        if (!$product) {
            return false;
        }
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
    public function deleteProduct($product_id)
    {
        return $this->productModel->delete($product_id);
    }
}

$controller = new controller_product($conn);
if (
    isset($_GET['action']) &&
    $_GET['action'] === 'detail'
) {

    $id = (int)$_GET['id'];

    $product = $controller->getProductDetail($id);

    header('Content-Type: application/json');

    echo json_encode([
        'success' => true,
        'product' => $product
    ]);

    exit;
}
/* Cập nhật sản phẩm */
if (
    isset($_POST['action']) &&
    $_POST['action'] === 'update'
) {

    $controller->updateProduct();

    header("Location: ../VIEW/page/products-admin.php");
    exit;
}

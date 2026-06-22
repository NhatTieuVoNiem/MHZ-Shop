<?php
session_start();

require_once '../MODEL/connect.php';
require_once '../MODEL/Product.php';

// Khởi tạo Product Model
$productModel = new Product($conn);

// Lấy dữ liệu từ form chỉnh sửa sản phẩm
$product_id   = (int)$_POST['product_id'];
$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$price        = (float)$_POST['price'];

// Lấy thông tin sản phẩm hiện tại từ cơ sở dữ liệu
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE product_id = ?
");

$stmt->bind_param("i", $product_id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

// Kiểm tra sản phẩm có tồn tại hay không
if (!$product) {
    die("Không tìm thấy sản phẩm");
}

// Cập nhật thông tin sản phẩm
$productModel->update(
    $product_id,
    $product_name,
    $description,
    $product['category_id'],
    $product['account_id'],
    $product['thumbnail_url'],
    $price
);

// Chuyển hướng về trang chi tiết sản phẩm
header("Location: ../VIEW/page/productsDetails.php?id=" . $product_id);
exit;

<?php
session_start();

require_once '../MODEL/connect.php';
require_once '../MODEL/Product.php';

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['account_id'])) {
    header("Location: ../VIEW/page/login.php");
    exit;
}

// Khởi tạo Product Model
$productModel = new Product($conn);

// Lấy dữ liệu sản phẩm từ form
$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$category_id  = (int)$_POST['category_id'];
$price        = (float)$_POST['price'];
$account_id   = $_SESSION['account_id'];

// Biến lưu đường dẫn ảnh đại diện sản phẩm
$thumbnail_url = '';

// Upload ảnh sản phẩm
if (!empty($_FILES['thumbnail']['name'])) {

    // Thư mục lưu ảnh
    $folder = "../VIEW/uploads/products/";

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // Tạo tên file duy nhất tránh trùng lặp
    $filename = time() . "_" . basename($_FILES['thumbnail']['name']);

    // Đường dẫn lưu file trên server
    $target = $folder . $filename;

    // Di chuyển file upload vào thư mục lưu trữ
    move_uploaded_file(
        $_FILES['thumbnail']['tmp_name'],
        $target
    );

    // Lưu đường dẫn vào database
    $thumbnail_url = "uploads/products/" . $filename;
}

// Thêm sản phẩm mới vào cơ sở dữ liệu
$productModel->create(
    $product_name,
    $description,
    $category_id,
    $account_id,
    $thumbnail_url,
    $price
);

// Chuyển hướng đến trang chi tiết sản phẩm
header("Location: ../VIEW/page/productsDetails.php?id=" . $product_id);
exit;

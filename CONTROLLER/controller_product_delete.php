<?php
session_start();

require_once '../MODEL/connect.php';
require_once '../MODEL/Product.php';

// Kiểm tra ID sản phẩm được truyền từ URL
if (!isset($_GET['id'])) {
    die("Thiếu ID");
}

// Lấy ID sản phẩm cần xóa
$product_id = (int)$_GET['id'];

// Khởi tạo Product Model
$productModel = new Product($conn);

// Xóa sản phẩm theo ID
$productModel->delete($product_id);

// Chuyển hướng về trang quản lý sản phẩm
header("Location: ../VIEW/page/product_manager.php");
exit;

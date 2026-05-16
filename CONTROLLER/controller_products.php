<?php
// controller_products.php
require_once("../MODEL/connect.php");
require_once("../MODEL/Product.php");

$productModel = new Product($conn);

// Lấy dữ liệu từ form lọc
$keyword  = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$price    = $_GET['price'] ?? '';
$sort     = $_GET['sort'] ?? '';

// Gọi model để lấy danh sách sản phẩm (nếu cần xử lý trước)
$products = $productModel->filterProducts($keyword, $category, $price, $sort);

// Lưu kết quả vào session để chuyển sang trang products.php
session_start();
$_SESSION['products'] = $products->fetch_all(MYSQLI_ASSOC);

// Chuyển hướng về trang products.php
header("Location: ../VIEW/page/products.php?keyword=$keyword&category=$category&price=$price&sort=$sort");
exit;

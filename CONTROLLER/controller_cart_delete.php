<?php
session_start();

// Kết nối cơ sở dữ liệu
require_once("../MODEL/connect.php");

// Model quản lý chi tiết giỏ hàng
require_once("../MODEL/CartItem.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['account_id'])) {
    header("Location: ../VIEW/page/login.php");
    exit;
}

// Lấy ID sản phẩm trong giỏ hàng cần xóa
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Khởi tạo model CartItem
$cartItemModel = new CartItem($conn);

// Xóa sản phẩm khỏi giỏ hàng
$cartItemModel->delete($id);

// Chuyển hướng về trang giỏ hàng sau khi xóa
header("Location: ../VIEW/page/cart.php");
exit;

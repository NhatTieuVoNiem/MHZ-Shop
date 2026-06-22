<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/CartItem.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['account_id'])) {
    header("Location: ../VIEW/page/login.php");
    exit;
}

// Khởi tạo model giỏ hàng
$cartItemModel = new CartItem($conn);

// Lấy ID sản phẩm trong giỏ hàng và hành động từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

// Lấy thông tin chi tiết mục trong giỏ hàng
$item = $cartItemModel->getById($id);

// Nếu không tìm thấy sản phẩm thì quay lại trang giỏ hàng
if (!$item) {
    header("Location: ../VIEW/page/cart.php");
    exit;
}

// Tăng số lượng sản phẩm
if ($action == 'increase') {

    $cartItemModel->increaseQuantity($id);
}
// Giảm số lượng sản phẩm
elseif ($action == 'decrease') {

    // Nếu số lượng lớn hơn 1 thì giảm đi 1
    if ($item['quantity'] > 1) {

        $cartItemModel->decreaseQuantity($id);
    }
    // Nếu số lượng bằng 1 thì xóa khỏi giỏ hàng
    else {

        $cartItemModel->delete($id);
    }
}

// Quay lại trang giỏ hàng sau khi xử lý
header("Location: ../VIEW/page/cart.php");
exit;

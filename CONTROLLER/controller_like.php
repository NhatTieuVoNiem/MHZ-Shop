<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/ProductLike.php");

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

// Lấy ID tài khoản từ session
$account_id = $_SESSION['account_id'];

// Lấy ID sản phẩm từ form gửi lên
$product_id = isset($_POST['product_id'])
    ? (int)$_POST['product_id']
    : 0;

// Khởi tạo model quản lý lượt thích sản phẩm
$likeModel = new ProductLike($conn);

// Kiểm tra người dùng đã thích sản phẩm này chưa
$liked = $likeModel->checkLike(
    $account_id,
    $product_id
);

// Nếu đã thích thì hủy thích
if ($liked) {

    $likeModel->removeLike(
        $account_id,
        $product_id
    );
}
// Nếu chưa thích thì thêm lượt thích mới
else {

    $likeModel->create(
        $account_id,
        $product_id
    );
}

// Quay lại trang trước đó sau khi xử lý
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

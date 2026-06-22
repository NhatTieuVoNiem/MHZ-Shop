<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/Cart.php");
require_once("../MODEL/CartItem.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

// Lấy ID tài khoản hiện tại
$account_id = $_SESSION['account_id'];

// Lấy ID sản phẩm từ form gửi lên
$product_id = isset($_POST['product_id'])
    ? (int)$_POST['product_id']
    : 0;

// Kiểm tra sản phẩm hợp lệ
if ($product_id <= 0) {
    die("Sản phẩm không hợp lệ");
}

// Khởi tạo model giỏ hàng và chi tiết giỏ hàng
$cartModel = new Cart($conn);
$cartItemModel = new CartItem($conn);

// Lấy giỏ hàng của người dùng
$cart = $cartModel->getByAccountId($account_id);

// Nếu chưa có giỏ hàng thì tạo mới
if (!$cart) {

    $cartModel->create($account_id);

    $cart = $cartModel->getByAccountId($account_id);
}

// Lấy ID giỏ hàng
$cart_id = $cart['cart_id'];

// Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
$item = $cartItemModel->getByCartAndProduct(
    $cart_id,
    $product_id
);

// Nếu sản phẩm đã tồn tại thì tăng số lượng
if ($item) {

    $cartItemModel->increaseQuantity(
        $item['cart_item_id']
    );
}
// Nếu sản phẩm chưa tồn tại thì thêm mới vào giỏ hàng
else {

    $cartItemModel->create(
        $cart_id,
        $product_id,
        1
    );
}

// Chuyển hướng về trang giỏ hàng
header("Location: ../VIEW/page/cart.php");
exit;

<?php

define('BASE_PATH', dirname(__DIR__, 2));
define('BASE_URL', '../');

session_start();

// Kiểm tra đăng nhập admin
if (
    !isset($_SESSION['account_id']) ||
    $_SESSION['role_id'] != 1
) {
    header("Location: login.php");
    exit();
}

// Kiểm tra ID đơn hàng
if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {
    header("Location: orders.php");
    exit();
}

$order_id = (int) $_GET['id'];

// Kết nối DB
require_once("../../MODEL/connect.php");
require_once("../../MODEL/Order.php");

$orderModel = new Order($conn);

// Kiểm tra đơn hàng tồn tại
$order = $orderModel->getById($order_id);

if (!$order) {
    $_SESSION['error'] = "Đơn hàng không tồn tại!";
    header("Location: orders.php");
    exit();
}

// Xóa đơn hàng
if ($orderModel->delete($order_id)) {

    $_SESSION['success'] =
        "Đã xóa đơn hàng #ORD-" . $order_id;
} else {

    $_SESSION['error'] =
        "Không thể xóa đơn hàng!";
}

header("Location: orders.php");
exit();

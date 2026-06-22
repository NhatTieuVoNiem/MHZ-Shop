<?php

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../MODEL/connect.php';

// Nạp model đơn hàng
require_once __DIR__ . '/../MODEL/Order.php';

// Khởi tạo đối tượng Order
$orderModel = new Order($conn);

// Lấy ID đơn hàng từ URL
$orderId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

// Lấy thông tin đơn hàng theo ID
$order = $orderModel->getById($orderId);

// Lấy danh sách sản phẩm thuộc đơn hàng
$items = $orderModel->getOrderItems($orderId);

// Danh sách trạng thái đơn hàng
$statusList = [
    'pending'    => 'Chờ xác nhận',
    'processing' => 'Đang xử lý',
    'completed'  => 'Hoàn thành',
    'cancelled'  => 'Đã huỷ'
];

// Chuyển mã trạng thái sang nội dung hiển thị
$statusText =
    $statusList[$order['status'] ?? 'pending']
    ?? 'Không xác định';

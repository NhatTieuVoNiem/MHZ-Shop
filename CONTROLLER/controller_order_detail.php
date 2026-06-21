<?php

require_once("../MODEL/connect.php");
require_once("../MODEL/Order.php");

$orderModel = new Order($conn);

$orderId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$order = $orderModel->getById($orderId);

$items = $orderModel->getOrderItems($orderId);

$statusList = [
    'pending'    => 'Chờ xác nhận',
    'processing' => 'Đang xử lý',
    'completed'  => 'Hoàn thành',
    'cancelled'  => 'Đã huỷ'
];

$statusText =
    $statusList[$order['status'] ?? 'pending']
    ?? 'Không xác định';
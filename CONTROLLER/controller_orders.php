<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Order.php';

$orderModel = new Order($conn);

// thống kê
$totalOrders = $orderModel->countAll();

$completedOrders = $orderModel->countByStatus('completed');
$processingOrders = $orderModel->countByStatus('processing');
$cancelledOrders = $orderModel->countByStatus('cancelled');

// danh sách
$orders = $orderModel->getOrdersWithDetails();

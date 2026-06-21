<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/CartItem.php");

if (!isset($_SESSION['account_id'])) {
    header("Location: ../VIEW/page/login.php");
    exit;
}

$cartItemModel = new CartItem($conn);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

$item = $cartItemModel->getById($id);

if (!$item) {
    header("Location: ../VIEW/page/cart.php");
    exit;
}

if ($action == 'increase') {

    $cartItemModel->increaseQuantity($id);

} elseif ($action == 'decrease') {

    if ($item['quantity'] > 1) {

        $cartItemModel->decreaseQuantity($id);

    } else {

        $cartItemModel->delete($id);

    }
}

header("Location: ../VIEW/page/cart.php");
exit;